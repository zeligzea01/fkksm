<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LandingSection;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserProgress;
use App\Support\SecurityHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));
        $accessType = $request->query('access_type');

        $query = Course::with(['instructor', 'modules.materials', 'assessments'])
            ->withCount(['enrollments', 'materials', 'modules', 'assessments']);

        if (!$user->isAdmin()) {
            $query->published();
        }

        if ($search !== '') {
            $escapedSearch = SecurityHelper::escapeLike($search);
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('title', 'like', "%{$escapedSearch}%")
                  ->orWhere('summary', 'like', "%{$escapedSearch}%")
                  ->orWhere('category', 'like', "%{$escapedSearch}%");
            });
        }

        if ($category !== '' && $category !== 'all') {
            $escapedCat = SecurityHelper::escapeLike($category);
            $query->where('category', 'like', "%{$escapedCat}%");
        }

        if (in_array($accessType, ['free', 'paid'], true)) {
            $query->where('access_type', $accessType);
        }

        $courses = $query->latest()->paginate(12)->withQueryString();
        $categoriesQuery = Course::select('category')->distinct()->whereNotNull('category');
        if (!$user->isAdmin()) {
            $categoriesQuery->published();
        }
        $categories = $categoriesQuery->pluck('category');

        $enrollmentsByCourse = $user->enrollments()->whereIn('status', ['active', 'completed'])->get()->keyBy('course_id');
        $userEnrolledCourseIds = $enrollmentsByCourse->keys()->toArray();

        $completedMaterialIds = UserProgress::where('user_id', $user->id)
            ->pluck('material_id')
            ->flip()
            ->toArray();

        return view('lms.courses.index', compact(
            'courses',
            'categories',
            'userEnrolledCourseIds',
            'enrollmentsByCourse',
            'completedMaterialIds',
            'search',
            'category',
            'accessType'
        ));
    }

    public function show(Course $course): View
    {
        $user = Auth::user();
        $isOwnerOrAdmin = $user->isAdmin() || $course->instructor_id === $user->id;
        if ($course->status !== 'published' && !$isOwnerOrAdmin) {
            abort(404);
        }

        $course->load([
            'instructor',
            'modules.materials' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order'),
            'assessments' => fn ($q) => $q->where('is_published', true)->orderBy('id'),
        ]);

        $enrollment = $user->enrollments()->where('course_id', $course->id)->whereIn('status', ['active', 'completed'])->first();
        $isEnrolled = (bool) $enrollment;

        $orderedMaterials = $course->modules->flatMap->materials->values();
        $completedMaterialIds = UserProgress::where('user_id', $user->id)
            ->whereIn('material_id', $orderedMaterials->pluck('id'))
            ->pluck('material_id')
            ->toArray();

        $unlockedMaterialIds = [];
        foreach ($orderedMaterials as $idx => $mat) {
            if ($isOwnerOrAdmin || $idx === 0 || in_array($orderedMaterials[$idx - 1]->id, $completedMaterialIds, true)) {
                $unlockedMaterialIds[] = $mat->id;
            }
        }

        $allMaterialsCompleted = $orderedMaterials->isEmpty() || count($completedMaterialIds) >= $orderedMaterials->count();
        $finalAssessmentUnlocked = $isOwnerOrAdmin || $allMaterialsCompleted;

        return view('lms.courses.show', compact(
            'course',
            'isEnrolled',
            'enrollment',
            'completedMaterialIds',
            'unlockedMaterialIds',
            'finalAssessmentUnlocked'
        ));
    }

    public function create(): View
    {
        abort_unless(Auth::user()->isInstructor(), 403);
        $instructors = User::whereIn('role', ['instructor', 'admin', 'super_admin'])->get();
        $skillBadgeCatalog = Course::availableSkillBadgeIcons();
        return view('lms.courses.create', compact('instructors', 'skillBadgeCatalog'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->isInstructor(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'requirements' => ['nullable', 'string', 'max:500'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'duration_hours' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'access_type' => ['required', 'in:free,paid'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'status' => ['required', 'in:draft,published,archived'],
            'featured_section' => ['nullable', 'in:catalog,bestseller,prakerja,webinar'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'reviews_count' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'thumbnail_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:4096'],
        ]);

        $thumbnailPath = SecurityHelper::sanitizeUrl($validated['thumbnail'] ?? null, '', true);
        $thumbnailPath = $thumbnailPath !== '' ? $thumbnailPath : null;

        if ($request->hasFile('thumbnail_file')) {
            $stored = SecurityHelper::storeSafeImage($request->file('thumbnail_file'), 'courses');
            if (!$stored) {
                return back()->withErrors(['thumbnail_file' => 'Format gambar tidak valid. Gunakan JPEG, PNG, atau WEBP yang sah.'])->withInput();
            }
            $thumbnailPath = $stored;
        }

        $badges = $this->parseSkillBadges($request);
        $slug = Str::slug($validated['title']) . '-' . Str::random(5);
        $assignedInstructorId = $user->isAdmin()
            ? ($validated['instructor_id'] ?? $user->id)
            : $user->id;

        $course = Course::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'] ?? 'Industri MM2100',
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'requirements' => $validated['requirements'] ?? null,
            'instructor_id' => $assignedInstructorId,
            'duration_hours' => $validated['duration_hours'] ?? 2.0,
            'access_type' => $validated['access_type'],
            'price' => $validated['access_type'] === 'paid' ? ($validated['price'] ?? 0) : 0,
            'original_price' => $validated['original_price'] ?? null,
            'status' => $validated['status'],
            'featured_section' => $validated['featured_section'] ?? 'catalog',
            'rating' => $validated['rating'] ?? 4.9,
            'reviews_count' => $validated['reviews_count'] ?? 120,
            'thumbnail' => $thumbnailPath,
            'skill_badges' => $badges,
        ]);

        LandingSection::clearCache();
        ActivityLog::record(
            $user->id,
            'course_created',
            "Kursus baru ditambahkan: {$course->title} (Status: {$course->status})"
        );

        return redirect()->route('lms.courses.show', $course)->with('success', 'Kursus berhasil dibuat. Anda dapat menambahkan modul dan materi sekarang.');
    }

    public function edit(Course $course): View
    {
        $user = Auth::user();
        abort_unless($user->isAdmin() || ($user->isInstructor() && $course->instructor_id === $user->id), 403);
        $instructors = User::whereIn('role', ['instructor', 'admin', 'super_admin'])->get();
        $skillBadgeCatalog = Course::availableSkillBadgeIcons();
        return view('lms.courses.edit', compact('course', 'instructors', 'skillBadgeCatalog'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->isAdmin() || ($user->isInstructor() && $course->instructor_id === $user->id), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'requirements' => ['nullable', 'string', 'max:500'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'duration_hours' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'access_type' => ['required', 'in:free,paid'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'status' => ['required', 'in:draft,published,archived'],
            'featured_section' => ['nullable', 'in:catalog,bestseller,prakerja,webinar'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'reviews_count' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'thumbnail_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:4096'],
        ]);

        $thumbnailPath = SecurityHelper::sanitizeUrl($validated['thumbnail'] ?? $course->thumbnail, (string) $course->thumbnail, true);
        if ($request->hasFile('thumbnail_file')) {
            $stored = SecurityHelper::storeSafeImage($request->file('thumbnail_file'), 'courses');
            if (!$stored) {
                return back()->withErrors(['thumbnail_file' => 'Format gambar tidak valid. Gunakan JPEG, PNG, atau WEBP yang sah.'])->withInput();
            }
            $thumbnailPath = $stored;
        }

        $badges = $this->parseSkillBadges($request);
        $assignedInstructorId = $user->isAdmin()
            ? ($validated['instructor_id'] ?? $course->instructor_id)
            : $course->instructor_id;

        $course->update([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? $course->category,
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'requirements' => $validated['requirements'] ?? $course->requirements,
            'instructor_id' => $assignedInstructorId,
            'duration_hours' => $validated['duration_hours'] ?? $course->duration_hours,
            'access_type' => $validated['access_type'],
            'price' => $validated['access_type'] === 'paid' ? ($validated['price'] ?? 0) : 0,
            'original_price' => $validated['original_price'] ?? $course->original_price,
            'status' => $validated['status'],
            'featured_section' => $validated['featured_section'] ?? $course->featured_section,
            'rating' => $validated['rating'] ?? $course->rating,
            'reviews_count' => $validated['reviews_count'] ?? $course->reviews_count,
            'thumbnail' => $thumbnailPath,
            'skill_badges' => $badges,
        ]);

        LandingSection::clearCache();
        ActivityLog::record(
            $user->id,
            'course_updated',
            "Kursus diperbarui: {$course->title} (Status: {$course->status})"
        );

        return redirect()->route('lms.courses.show', $course)->with('success', 'Informasi kursus berhasil diperbarui.');
    }

    public function enroll(Course $course): RedirectResponse
    {
        if ($course->status !== 'published') {
            return redirect()->route('lms.courses.index')->with('error', 'Kursus belum dipublikasikan atau telah diarsipkan.');
        }

        $user = Auth::user();

        if ($course->access_type === 'paid' && !$user->isAdmin() && $course->instructor_id !== $user->id) {
            $hasActivePaidSub = $user->subscriptions()
                ->where('status', 'active')
                ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
                ->whereHas('plan', fn ($q) => $q->where('price', '>', 0))
                ->exists();

            $hasCourseVoucher = $user->voucherRedemptions()
                ->whereHas('voucher', fn ($q) => $q->where('course_id', $course->id))
                ->exists();

            if (!$hasActivePaidSub && !$hasCourseVoucher) {
                return redirect()->route('lms.vouchers.index')
                    ->with('warning', 'Kursus ini berbayar. Masukkan voucher pelatihan atau aktifkan paket berbayar di menu Subscription.');
            }
        }

        $outcome = DB::transaction(function () use ($user, $course) {
            $existing = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                if ($existing->status === 'cancelled') {
                    $existing->update(['status' => 'active']);
                    return 'reactivated';
                }
                return 'already';
            }

            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);

            return 'created';
        });

        if ($outcome === 'already') {
            return redirect()->route('lms.classroom.show', $course)->with('info', 'Anda sudah terdaftar pada kursus ini.');
        }

        if ($outcome === 'created') {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pendaftaran Kursus Berhasil',
                'body' => "Anda resmi terdaftar di program: {$course->title}.",
                'channel' => 'in_app',
            ]);
        }

        ActivityLog::record(
            $user->id,
            'course_enrolled',
            "Mendaftar kursus: {$course->title}"
        );

        return redirect()->route('lms.classroom.show', $course)
            ->with('success', $outcome === 'reactivated'
                ? 'Pendaftaran Anda telah diaktifkan kembali.'
                : "Selamat! Anda telah terdaftar di kursus: {$course->title}");
    }

    private function parseSkillBadges(Request $request): array
    {
        $icons = (array) $request->input('badge_icons', []);
        $names = (array) $request->input('badge_names', []);
        $allowedIcons = array_column(Course::availableSkillBadgeIcons(), 'icon');
        $badges = [];

        foreach ($icons as $i => $icon) {
            $icon = basename(trim((string) $icon));
            $name = mb_substr(trim((string) ($names[$i] ?? '')), 0, 120);
            if ($icon !== '' && $name !== '' && in_array($icon, $allowedIcons, true)) {
                $badges[] = ['icon' => $icon, 'name' => $name];
            }
        }

        return $badges;
    }
}

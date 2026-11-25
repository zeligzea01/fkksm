<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $accessType = $request->query('access_type');

        $query = Course::with(['instructor', 'modules.materials'])
            ->withCount(['enrollments', 'materials']);

        if (!Auth::user()->isAdmin()) {
            $query->published();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($accessType && $accessType !== 'all') {
            $query->where('access_type', $accessType);
        }

        $courses = $query->latest()->paginate(12)->withQueryString();
        $categories = Course::select('category')->distinct()->whereNotNull('category')->pluck('category');

        $userEnrolledCourseIds = Auth::user()->enrollments()->pluck('course_id')->toArray();

        return view('lms.courses.index', compact('courses', 'categories', 'userEnrolledCourseIds', 'search', 'category', 'accessType'));
    }

    public function show(Course $course): View
    {
        $course->load([
            'instructor',
            'modules.materials' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order'),
            'assessments' => fn ($q) => $q->where('is_published', true),
        ]);

        $isEnrolled = Auth::user()->enrollments()->where('course_id', $course->id)->exists();
        $enrollment = $isEnrolled ? Auth::user()->enrollments()->where('course_id', $course->id)->first() : null;

        return view('lms.courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    public function create(): View
    {
        abort_unless(Auth::user()->isInstructor(), 403);
        $instructors = User::whereIn('role', ['instructor', 'admin', 'super_admin'])->get();
        return view('lms.courses.create', compact('instructors'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isInstructor(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'duration_hours' => ['nullable', 'numeric', 'min:0'],
            'access_type' => ['required', 'in:free,paid'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        $course = Course::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'] ?? 'Industri MM2100',
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'instructor_id' => $validated['instructor_id'] ?? Auth::id(),
            'duration_hours' => $validated['duration_hours'] ?? 2.0,
            'access_type' => $validated['access_type'],
            'price' => $validated['access_type'] === 'paid' ? ($validated['price'] ?? 0) : 0,
            'status' => $validated['status'],
        ]);

        ActivityLog::record(
            Auth::id(),
            'course_created',
            "Kursus baru ditambahkan: {$course->title} (Status: {$course->status})"
        );

        return redirect()->route('lms.courses.show', $course)->with('success', 'Kursus berhasil dibuat. Anda dapat menambahkan modul dan materi sekarang.');
    }

    public function edit(Course $course): View
    {
        abort_unless(Auth::user()->isInstructor(), 403);
        $instructors = User::whereIn('role', ['instructor', 'admin', 'super_admin'])->get();
        return view('lms.courses.edit', compact('course', 'instructors'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        abort_unless(Auth::user()->isInstructor(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'duration_hours' => ['nullable', 'numeric', 'min:0'],
            'access_type' => ['required', 'in:free,paid'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $course->update([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? $course->category,
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'instructor_id' => $validated['instructor_id'] ?? $course->instructor_id,
            'duration_hours' => $validated['duration_hours'] ?? $course->duration_hours,
            'access_type' => $validated['access_type'],
            'price' => $validated['access_type'] === 'paid' ? ($validated['price'] ?? 0) : 0,
            'status' => $validated['status'],
        ]);

        ActivityLog::record(
            Auth::id(),
            'course_updated',
            "Kursus diperbarui: {$course->title} (Status: {$course->status})"
        );

        return redirect()->route('lms.courses.show', $course)->with('success', 'Informasi kursus berhasil diperbarui.');
    }

    public function enroll(Course $course): RedirectResponse
    {
        $user = Auth::user();

        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $existing->update(['status' => 'active']);
                return redirect()->route('lms.classroom.show', $course)->with('info', 'Pendaftaran Anda telah diaktifkan kembali.');
            }
            return redirect()->route('lms.classroom.show', $course)->with('info', 'Anda sudah terdaftar pada kursus ini.');
        }

        if ($course->access_type === 'paid') {
            return redirect()->route('lms.vouchers.index')->with('warning', 'Kursus ini berbayar. Masukkan voucher pelatihan dari perusahaan atau hubungi pengurus FKKSM.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        ActivityLog::record(
            $user->id,
            'course_enrolled',
            "Mendaftar kursus: {$course->title}"
        );

        return redirect()->route('lms.classroom.show', $course)->with('success', "Selamat! Anda telah terdaftar di kursus: {$course->title}");
    }
}


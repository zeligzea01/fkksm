<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Material;
use App\Support\SecurityHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        abort_unless($user->isInstructor(), 403);

        $query = Course::with(['modules.materials', 'instructor', 'assessments'])->latest();
        if (!$user->isAdmin()) {
            $query->where('instructor_id', $user->id);
        }

        $courses = $query->paginate(10);

        return view('lms.materials.index', compact('courses'));
    }

    public function storeModule(Request $request, Course $course): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->isAdmin() || ($user->isInstructor() && $course->instructor_id === $user->id), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'summary' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ]);

        $maxOrder = $course->modules()->max('sort_order') ?? 0;

        $module = CourseModule::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ($maxOrder + 1),
            'is_active' => true,
        ]);

        ActivityLog::record($user->id, 'module_created', "Modul baru '{$module->title}' ditambahkan ke {$course->title}");

        return back()->with('success', "Modul '{$module->title}' berhasil ditambahkan.");
    }

    public function storeMaterial(Request $request, Course $course): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->isAdmin() || ($user->isInstructor() && $course->instructor_id === $user->id), 403);

        $validated = $request->validate([
            'module_id' => [
                'nullable',
                Rule::exists('course_modules', 'id')->where(fn ($q) => $q->where('course_id', $course->id)),
            ],
            'title' => ['required', 'string', 'max:180'],
            'content_type' => ['required', 'in:video,text,ebook,practice,link,file'],
            'content_text' => ['nullable', 'string', 'max:50000'],
            'content_url' => ['nullable', 'url:http,https', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm', 'mimetypes:video/mp4,video/webm', 'max:20480'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:300'],
            'quiz_question' => ['nullable', 'string', 'max:500'],
            'quiz_options' => ['nullable', 'array'],
            'quiz_options.*' => ['nullable', 'string', 'max:300'],
            'quiz_answer' => ['nullable', 'integer', 'min:0', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ], [
            'video_file.mimes' => 'Format video harus berupa MP4 atau WEBM.',
            'video_file.max' => 'Ukuran berkas video maksimal 20 MB agar tetap ringan.',
        ]);

        $maxOrder = $course->materials()->max('sort_order') ?? 0;
        $safeUrl = !empty($validated['content_url'])
            ? SecurityHelper::sanitizeUrl($validated['content_url'], '', false)
            : null;

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = SecurityHelper::storeSafeVideo($request->file('video_file'), 'videos');
            if (!$videoPath) {
                return back()->withErrors(['video_file' => 'Berkas video tidak valid. Gunakan file MP4 atau WEBM.'])->withInput();
            }
        } elseif ($validated['content_type'] === 'video') {
            $videoPath = SecurityHelper::ensureSampleVideoExists();
        }

        $quizPayload = null;
        if ($validated['content_type'] === 'practice') {
            $qText = trim((string) ($validated['quiz_question'] ?? ''));
            $rawOpts = array_values(array_filter(array_map('trim', (array) ($validated['quiz_options'] ?? [])), fn ($v) => $v !== ''));
            if ($qText !== '' && count($rawOpts) >= 2) {
                $ansIdx = min((int) ($validated['quiz_answer'] ?? 0), count($rawOpts) - 1);
                $quizPayload = [[
                    'question' => $qText,
                    'options' => $rawOpts,
                    'answer' => max(0, $ansIdx),
                ]];
            }
        }

        $material = Material::create([
            'course_id' => $course->id,
            'module_id' => $validated['module_id'] ?? null,
            'title' => $validated['title'],
            'content_type' => $validated['content_type'],
            'content_text' => $validated['content_text'] ?? null,
            'content_url' => $safeUrl !== '' ? $safeUrl : null,
            'video_path' => $videoPath,
            'duration_minutes' => $validated['duration_minutes'] ?? 5,
            'quiz_payload' => $quizPayload,
            'sort_order' => $validated['sort_order'] ?? ($maxOrder + 1),
            'is_published' => true,
        ]);

        ActivityLog::record($user->id, 'material_created', "Materi baru '{$material->title}' ({$material->stage_label}) ditambahkan ke {$course->title}");

        return back()->with('success', "Materi '{$material->title}' ({$material->stage_label}) berhasil ditambahkan ke kursus.");
    }

    public function destroyMaterial(Material $material): RedirectResponse
    {
        $user = Auth::user();
        $course = $material->course;
        abort_unless($user->isAdmin() || ($user->isInstructor() && $course && $course->instructor_id === $user->id), 403);

        $title = $material->title;
        $material->delete();

        ActivityLog::record($user->id, 'record_deleted', "Materi '{$title}' dihapus.");

        return back()->with('success', "Materi '{$title}' berhasil dihapus.");
    }

    public function destroyModule(CourseModule $module): RedirectResponse
    {
        $user = Auth::user();
        $course = $module->course;
        abort_unless($user->isAdmin() || ($user->isInstructor() && $course && $course->instructor_id === $user->id), 403);

        $title = $module->title;
        $module->delete();

        ActivityLog::record($user->id, 'record_deleted', "Modul '{$title}' beserta materinya dihapus.");

        return back()->with('success', "Modul '{$title}' berhasil dihapus.");
    }
}

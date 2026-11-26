<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $assessments = Assessment::with(['course', 'submissions.user'])->latest()->paginate(15);
            $pendingSubmissions = AssessmentSubmission::with(['assessment.course', 'user'])
                ->where('status', 'submitted')
                ->latest()
                ->get();
            $courses = Course::published()->get();

            return view('lms.assessments.index', compact('assessments', 'pendingSubmissions', 'courses'));
        }

        if ($user->isInstructor()) {
            $myCourseIds = Course::where('instructor_id', $user->id)->pluck('id');
            $assessments = Assessment::whereIn('course_id', $myCourseIds)
                ->with(['course', 'submissions.user'])
                ->latest()
                ->paginate(15);
            $pendingSubmissions = AssessmentSubmission::with(['assessment.course', 'user'])
                ->whereHas('assessment', fn ($q) => $q->whereIn('course_id', $myCourseIds))
                ->where('status', 'submitted')
                ->latest()
                ->get();
            $courses = Course::where('instructor_id', $user->id)->get();

            return view('lms.assessments.index', compact('assessments', 'pendingSubmissions', 'courses'));
        }

        // Student assessments (only active/completed enrolled courses)
        $enrolledCourseIds = $user->enrollments()->whereIn('status', ['active', 'completed'])->pluck('course_id');
        $assessments = Assessment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->with(['course', 'submissions' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->paginate(15);

        return view('lms.assessments.index', compact('assessments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->isInstructor(), 403);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:180'],
            'assessment_type' => ['required', 'in:quiz,assignment,evaluation'],
            'instructions' => ['nullable', 'string', 'max:10000'],
            'due_at' => ['nullable', 'date'],
        ]);

        $course = Course::findOrFail($validated['course_id']);
        if (!$user->isAdmin() && $course->instructor_id !== $user->id) {
            abort(403, 'Anda tidak dapat menambahkan evaluasi untuk kelas instruktur lain.');
        }

        $assessment = Assessment::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'assessment_type' => $validated['assessment_type'],
            'instructions' => $validated['instructions'] ?? null,
            'is_published' => true,
            'due_at' => $validated['due_at'] ?? null,
        ]);

        ActivityLog::record($user->id, 'assessment_created', "Evaluasi baru dibuat: {$assessment->title}");

        return back()->with('success', "Evaluasi '{$assessment->title}' berhasil dibuat.");
    }

    public function show(Assessment $assessment): View|RedirectResponse
    {
        $user = Auth::user();
        $assessment->load('course.modules.materials');
        $this->authorizeAssessmentAccess($user, $assessment);

        if (!$user->isAdmin() && !$user->isInstructor() && $assessment->course) {
            $publishedMaterialIds = $assessment->course->materials()->where('is_published', true)->pluck('id');
            if ($publishedMaterialIds->isNotEmpty()) {
                $completedCount = \App\Models\UserProgress::where('user_id', $user->id)
                    ->whereIn('material_id', $publishedMaterialIds)
                    ->count();
                if ($completedCount < $publishedMaterialIds->count()) {
                    return redirect()->route('lms.classroom.show', $assessment->course)
                        ->with('warning', 'Latihan Akhir masih terkunci. Selesaikan seluruh tahapan Video, Materi, dan Latihan Soal di ruang belajar terlebih dahulu.');
                }
            }
        }

        $assessment->load([
            'submissions' => fn ($q) => $user->isInstructor()
                ? $q->with('user')
                : $q->where('user_id', $user->id),
        ]);
        $mySubmission = $user->isInstructor() ? null : $assessment->submissions->first();

        return view('lms.assessments.show', compact('assessment', 'mySubmission'));
    }

    public function submit(Request $request, Assessment $assessment): RedirectResponse
    {
        $user = Auth::user();
        $assessment->load('course.modules.materials');
        $this->authorizeAssessmentAccess($user, $assessment);

        if (!$user->isAdmin() && !$user->isInstructor() && $assessment->course) {
            $publishedMaterialIds = $assessment->course->materials()->where('is_published', true)->pluck('id');
            if ($publishedMaterialIds->isNotEmpty()) {
                $completedCount = \App\Models\UserProgress::where('user_id', $user->id)
                    ->whereIn('material_id', $publishedMaterialIds)
                    ->count();
                if ($completedCount < $publishedMaterialIds->count()) {
                    return redirect()->route('lms.classroom.show', $assessment->course)
                        ->with('warning', 'Latihan Akhir masih terkunci. Selesaikan seluruh tahapan Video, Materi, dan Latihan Soal di ruang belajar terlebih dahulu.');
                }
            }
        }

        $validated = $request->validate([
            'response_text' => ['required', 'string', 'min:5', 'max:20000'],
        ]);

        AssessmentSubmission::updateOrCreate(
            ['assessment_id' => $assessment->id, 'user_id' => $user->id],
            [
                'response_text' => $validated['response_text'],
                'status' => 'submitted',
                'submitted_at' => now(),
            ]
        );

        ActivityLog::record($user->id, 'assessment_submitted', "Mengumpulkan tugas/evaluasi: {$assessment->title}");

        return redirect()->route('lms.assessments.show', $assessment)
            ->with('success', 'Jawaban/tugas Anda telah berhasil dikumpulkan dan sedang menunggu penilaian instruktur.');
    }

    private function authorizeAssessmentAccess($user, Assessment $assessment): void
    {
        if ($user->isAdmin()) {
            return;
        }
        if ($user->isInstructor()) {
            abort_unless($assessment->course && $assessment->course->instructor_id === $user->id, 403, 'Akses terbatas untuk kelas Anda.');
            return;
        }

        abort_unless($assessment->is_published && $assessment->course && $assessment->course->status === 'published', 404);
        $isEnrolled = $user->enrollments()
            ->where('course_id', $assessment->course_id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
        abort_unless($isEnrolled, 403, 'Silakan mendaftar pada kursus terkait untuk mengakses evaluasi.');
    }

    public function grade(Request $request, AssessmentSubmission $submission): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->isInstructor(), 403);

        $course = $submission->assessment->course;
        if (!$user->isAdmin() && $course && $course->instructor_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menilai kelas instruktur lain.');
        }

        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:reviewed,returned'],
        ], [
            'score.required' => 'Nilai evaluasi wajib diisi.',
            'score.min' => 'Nilai minimal adalah 0.',
            'score.max' => 'Nilai maksimal adalah 100.',
            'status.required' => 'Status penilaian wajib ditentukan.',
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'] ?? null,
            'status' => $validated['status'],
            'reviewed_at' => now(),
        ]);

        ActivityLog::record(
            Auth::id(),
            'assessment_graded',
            "Menilai evaluasi {$submission->assessment->title} untuk peserta {$submission->user->name} dengan nilai {$submission->score}"
        );

        return back()->with('success', "Penilaian untuk peserta {$submission->user->name} berhasil disimpan.");
    }
}


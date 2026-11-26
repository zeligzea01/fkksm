<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\UserProgress;
use App\Support\SecurityHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClassroomController extends Controller
{
    public function show(Course $course, Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $isOwnerOrAdmin = $user->isAdmin() || $course->instructor_id === $user->id;

        if ($course->status !== 'published' && !$isOwnerOrAdmin) {
            abort(404);
        }

        // Check active/completed enrollment (cancelled does not grant access) or owner/admin access
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        if (!$enrollment && !$isOwnerOrAdmin) {
            return redirect()->route('lms.courses.show', $course)
                ->with('warning', 'Silakan mendaftar terlebih dahulu untuk mengakses materi ruang kelas.');
        }

        SecurityHelper::ensureSampleVideoExists();

        $course->load([
            'modules.materials' => fn ($q) => $q->when(!$isOwnerOrAdmin, fn ($mq) => $mq->where('is_published', true))->orderBy('sort_order'),
            'assessments' => fn ($q) => $q->where('is_published', true)->orderBy('id'),
        ]);

        $orderedMaterials = $this->resolveOrderedMaterials($course, $isOwnerOrAdmin);
        $materialIds = $orderedMaterials->pluck('id');

        $completedMaterialIds = $materialIds->isNotEmpty()
            ? UserProgress::where('user_id', $user->id)->whereIn('material_id', $materialIds)->pluck('material_id')->toArray()
            : [];

        // Build sequential unlock map: material[k] is unlocked iff k === 0 or material[k-1] is completed (or owner/admin)
        $unlockedMaterialIds = [];
        foreach ($orderedMaterials as $index => $mat) {
            if ($isOwnerOrAdmin || $index === 0) {
                $unlockedMaterialIds[] = $mat->id;
            } else {
                $prevMat = $orderedMaterials[$index - 1];
                if (in_array($prevMat->id, $completedMaterialIds, true)) {
                    $unlockedMaterialIds[] = $mat->id;
                }
            }
        }

        $totalMaterials = $orderedMaterials->count();
        $completedMaterialsCount = count($completedMaterialIds);
        $allMaterialsCompleted = $totalMaterials === 0 || $completedMaterialsCount >= $totalMaterials;

        $finalAssessment = $course->assessments->first();
        $finalSubmission = $finalAssessment
            ? AssessmentSubmission::where('assessment_id', $finalAssessment->id)->where('user_id', $user->id)->first()
            : null;
        $passingScore = $finalAssessment ? (int) ($finalAssessment->passing_score ?: 70) : 70;
        $finalAssessmentPassed = $finalSubmission && (
            $finalSubmission->score !== null
                ? (float) $finalSubmission->score >= $passingScore
                : in_array($finalSubmission->status, ['submitted', 'reviewed'], true)
        );
        $finalAssessmentUnlocked = $isOwnerOrAdmin || $allMaterialsCompleted;

        // Calculate overall course progress (including Latihan Akhir when present)
        $totalSteps = $totalMaterials + ($finalAssessment ? 1 : 0);
        $completedCount = $completedMaterialsCount + ($finalAssessment && $finalAssessmentPassed ? 1 : 0);
        $progressPercent = $totalSteps > 0 ? (int) round(($completedCount / $totalSteps) * 100) : 0;

        $certificate = Certificate::where('user_id', $user->id)->where('course_id', $course->id)->first();

        // Determine active view mode: 'material', 'final_assessment', or 'certificate'
        $requestedStep = (string) $request->query('step', '');
        $requestedMaterialId = (int) $request->query('material', 0);
        $activeStepMode = 'material';
        $currentMaterial = null;

        if ($requestedStep === 'final' && $finalAssessment) {
            if (!$finalAssessmentUnlocked) {
                $firstIncomplete = $orderedMaterials->first(fn ($m) => !in_array($m->id, $completedMaterialIds, true))
                    ?? $orderedMaterials->first();
                return redirect()->route('lms.classroom.show', ['course' => $course->id, 'material' => $firstIncomplete?->id])
                    ->with('warning', 'Latihan Akhir masih terkunci. Harap selesaikan seluruh tahapan Video, Materi, dan Latihan Soal secara berurutan terlebih dahulu.');
            }
            $activeStepMode = 'final_assessment';
        } elseif ($requestedStep === 'certificate') {
            if (!$certificate && !$isOwnerOrAdmin) {
                return redirect()->route('lms.classroom.show', ['course' => $course->id])
                    ->with('warning', 'Sertifikat hanya terbuka setelah Anda menyelesaikan seluruh tahapan materi dan lulus Latihan Akhir.');
            }
            $activeStepMode = 'certificate';
        } else {
            if ($requestedMaterialId > 0) {
                $candidate = $orderedMaterials->firstWhere('id', $requestedMaterialId);
                if ($candidate && !in_array($candidate->id, $unlockedMaterialIds, true)) {
                    $firstIncomplete = $orderedMaterials->first(fn ($m) => !in_array($m->id, $completedMaterialIds, true))
                        ?? $orderedMaterials->first();
                    return redirect()->route('lms.classroom.show', ['course' => $course->id, 'material' => $firstIncomplete?->id])
                        ->with('warning', 'Tahap materi tersebut masih terkunci. Selesaikan tahapan sebelumnya (Video → Materi → Latihan Soal) satu per satu secara berurutan.');
                }
                $currentMaterial = $candidate;
            }

            if (!$currentMaterial) {
                $firstIncomplete = $orderedMaterials->first(fn ($m) => !in_array($m->id, $completedMaterialIds, true));
                if (!$firstIncomplete && $finalAssessment && !$finalAssessmentPassed && $finalAssessmentUnlocked) {
                    $activeStepMode = 'final_assessment';
                } else {
                    $currentMaterial = $firstIncomplete ?? $orderedMaterials->first();
                }
            }
        }

        // Compute Previous and Next navigation descriptors for the bottom bar
        $prevNav = null;
        $nextNav = null;

        if ($activeStepMode === 'material' && $currentMaterial) {
            $currentIndex = $orderedMaterials->search(fn ($m) => $m->id === $currentMaterial->id);
            if ($currentIndex !== false && $currentIndex > 0) {
                $prevMat = $orderedMaterials[$currentIndex - 1];
                $prevNav = [
                    'url' => route('lms.classroom.show', ['course' => $course->id, 'material' => $prevMat->id]),
                    'label' => $prevMat->title,
                ];
            }
            if ($currentIndex !== false && $currentIndex < ($orderedMaterials->count() - 1)) {
                $nextMat = $orderedMaterials[$currentIndex + 1];
                $nextNav = [
                    'url' => route('lms.classroom.show', ['course' => $course->id, 'material' => $nextMat->id]),
                    'label' => $nextMat->title,
                    'unlocked' => in_array($nextMat->id, $unlockedMaterialIds, true),
                ];
            } elseif ($finalAssessment) {
                $nextNav = [
                    'url' => route('lms.classroom.show', ['course' => $course->id, 'step' => 'final']),
                    'label' => $finalAssessment->title,
                    'unlocked' => $finalAssessmentUnlocked,
                ];
            }
        } elseif ($activeStepMode === 'final_assessment') {
            $lastMat = $orderedMaterials->last();
            if ($lastMat) {
                $prevNav = [
                    'url' => route('lms.classroom.show', ['course' => $course->id, 'material' => $lastMat->id]),
                    'label' => $lastMat->title,
                ];
            }
            if ($certificate) {
                $nextNav = [
                    'url' => route('lms.classroom.show', ['course' => $course->id, 'step' => 'certificate']),
                    'label' => 'Sertifikat Kompetensi',
                    'unlocked' => true,
                ];
            }
        } elseif ($activeStepMode === 'certificate') {
            if ($finalAssessment) {
                $prevNav = [
                    'url' => route('lms.classroom.show', ['course' => $course->id, 'step' => 'final']),
                    'label' => $finalAssessment->title,
                ];
            } elseif ($orderedMaterials->isNotEmpty()) {
                $lastMat = $orderedMaterials->last();
                $prevNav = [
                    'url' => route('lms.classroom.show', ['course' => $course->id, 'material' => $lastMat->id]),
                    'label' => $lastMat->title,
                ];
            }
        }

        return view('lms.classroom.show', compact(
            'course',
            'orderedMaterials',
            'currentMaterial',
            'completedMaterialIds',
            'unlockedMaterialIds',
            'progressPercent',
            'completedCount',
            'totalMaterials',
            'totalSteps',
            'enrollment',
            'certificate',
            'finalAssessment',
            'finalSubmission',
            'finalAssessmentUnlocked',
            'finalAssessmentPassed',
            'activeStepMode',
            'prevNav',
            'nextNav'
        ));
    }

    public function completeMaterial(Request $request, Course $course, Material $material): RedirectResponse
    {
        $user = Auth::user();
        $isOwnerOrAdmin = $user->isAdmin() || $course->instructor_id === $user->id;

        if ($material->course_id !== $course->id) {
            abort(404);
        }
        if ((!$material->is_published || $course->status !== 'published') && !$isOwnerOrAdmin) {
            abort(404);
        }

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        if (!$enrollment && !$isOwnerOrAdmin) {
            abort(403, 'Anda wajib terdaftar pada kursus ini sebelum menyelesaikan materi.');
        }

        $course->load([
            'modules.materials' => fn ($q) => $q->when(!$isOwnerOrAdmin, fn ($mq) => $mq->where('is_published', true))->orderBy('sort_order'),
            'assessments' => fn ($q) => $q->where('is_published', true)->orderBy('id'),
        ]);

        $orderedMaterials = $this->resolveOrderedMaterials($course, $isOwnerOrAdmin);
        $currentIndex = $orderedMaterials->search(fn ($m) => $m->id === $material->id);

        // Enforce strict sequential completion: all prior materials must be completed first
        if (!$isOwnerOrAdmin && $currentIndex !== false && $currentIndex > 0) {
            $priorMaterialIds = $orderedMaterials->slice(0, $currentIndex)->pluck('id');
            $completedPriorCount = UserProgress::where('user_id', $user->id)
                ->whereIn('material_id', $priorMaterialIds)
                ->count();

            if ($completedPriorCount < $priorMaterialIds->count()) {
                $firstIncomplete = $orderedMaterials->first(function ($m) use ($user) {
                    return !UserProgress::where('user_id', $user->id)->where('material_id', $m->id)->exists();
                });

                return redirect()->route('lms.classroom.show', ['course' => $course->id, 'material' => $firstIncomplete?->id])
                    ->with('warning', 'Harap selesaikan tahapan pembelajaran secara berurutan (Video → Materi → Latihan Soal) satu per satu.');
            }
        }

        // Stage-specific validation for Latihan Soal (practice quiz)
        $quizScoreSummary = null;
        if (in_array($material->content_type, ['practice', 'quiz'], true) && !empty($material->quiz_payload)) {
            $questions = array_values((array) $material->quiz_payload);
            $totalQuestions = count($questions);

            if ($totalQuestions > 0) {
                $answers = (array) $request->input('answers', []);
                if (count($answers) < $totalQuestions) {
                    return redirect()->route('lms.classroom.show', ['course' => $course->id, 'material' => $material->id])
                        ->withErrors(['answers' => 'Harap jawab seluruh pertanyaan Latihan Soal sebelum melanjutkan ke tahap berikutnya.'])
                        ->withInput();
                }

                $correctCount = 0;
                foreach ($questions as $qIndex => $qData) {
                    $selected = isset($answers[$qIndex]) ? (int) $answers[$qIndex] : -1;
                    $expected = (int) ($qData['answer'] ?? 0);
                    if ($selected === $expected) {
                        $correctCount++;
                    }
                }

                $scorePercent = (int) round(($correctCount / $totalQuestions) * 100);
                if ($scorePercent < 70) {
                    return redirect()->route('lms.classroom.show', ['course' => $course->id, 'material' => $material->id])
                        ->with('warning', "Skor Latihan Soal Anda {$scorePercent}% ({$correctCount}/{$totalQuestions} benar). Minimal nilai kelulusan Latihan Soal adalah 70%. Silakan periksa kembali jawaban Anda.")
                        ->withInput();
                }

                $quizScoreSummary = " (Skor Latihan Soal: {$scorePercent}% - {$correctCount}/{$totalQuestions} Benar)";
            }
        }

        $hasFinalAssessment = $course->assessments->isNotEmpty();

        $message = DB::transaction(function () use ($user, $course, $material, $orderedMaterials, $hasFinalAssessment, $quizScoreSummary) {
            UserProgress::firstOrCreate(
                ['user_id' => $user->id, 'material_id' => $material->id],
                ['completed_at' => now()]
            );

            ActivityLog::record(
                $user->id,
                'material_completed',
                "Menyelesaikan tahap {$material->stage_label}: {$material->title} pada kursus {$course->title}"
            );

            $courseMaterialIds = $orderedMaterials->pluck('id');
            $totalMaterials = $courseMaterialIds->count();
            $completedCount = $totalMaterials > 0
                ? UserProgress::where('user_id', $user->id)->whereIn('material_id', $courseMaterialIds)->count()
                : 0;

            $msg = "Tahap {$material->stage_label} '{$material->title}' berhasil diselesaikan{$quizScoreSummary}.";

            if ($totalMaterials > 0 && $completedCount >= $totalMaterials) {
                if ($hasFinalAssessment) {
                    $msg = "Selamat! Seluruh modul pembelajaran selesai{$quizScoreSummary}. Tahap Latihan Akhir kini telah terbuka!";
                } else {
                    $certNo = $this->issueCourseCertificateIfEligible($user->id, $course);
                    if ($certNo) {
                        $msg = "Selamat! Anda telah menyelesaikan seluruh materi kursus dan Sertifikat Kompetensi Anda (#{$certNo}) berhasil diterbitkan!";
                    }
                }
            }

            return $msg;
        });

        // Determine next item in sequence
        $nextMaterial = ($currentIndex !== false && $currentIndex < ($orderedMaterials->count() - 1))
            ? $orderedMaterials[$currentIndex + 1]
            : null;

        if ($nextMaterial) {
            $redirectParams = ['course' => $course->id, 'material' => $nextMaterial->id];
        } elseif ($hasFinalAssessment) {
            $redirectParams = ['course' => $course->id, 'step' => 'final'];
        } else {
            $redirectParams = ['course' => $course->id];
        }

        return redirect()->route('lms.classroom.show', $redirectParams)
            ->with('success', $message);
    }

    public function submitFinalAssessment(Request $request, Course $course, Assessment $assessment): RedirectResponse
    {
        $user = Auth::user();
        $isOwnerOrAdmin = $user->isAdmin() || $course->instructor_id === $user->id;

        if ($assessment->course_id !== $course->id) {
            abort(404);
        }

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        if (!$enrollment && !$isOwnerOrAdmin) {
            abort(403, 'Anda wajib terdaftar pada kursus ini.');
        }

        $course->load([
            'modules.materials' => fn ($q) => $q->when(!$isOwnerOrAdmin, fn ($mq) => $mq->where('is_published', true))->orderBy('sort_order'),
        ]);

        $orderedMaterials = $this->resolveOrderedMaterials($course, $isOwnerOrAdmin);
        $totalMaterials = $orderedMaterials->count();
        $completedMaterialsCount = $totalMaterials > 0
            ? UserProgress::where('user_id', $user->id)->whereIn('material_id', $orderedMaterials->pluck('id'))->count()
            : 0;

        if (!$isOwnerOrAdmin && $totalMaterials > 0 && $completedMaterialsCount < $totalMaterials) {
            return redirect()->route('lms.classroom.show', ['course' => $course->id])
                ->with('warning', 'Selesaikan seluruh Video, Materi, dan Latihan Soal pada setiap modul sebelum mengerjakan Latihan Akhir.');
        }

        $questions = !empty($assessment->quiz_payload) ? array_values((array) $assessment->quiz_payload) : [];
        $passingScore = (int) ($assessment->passing_score ?: 70);
        $calculatedScore = 100;
        $summaryLines = [];

        if (!empty($questions)) {
            $answers = (array) $request->input('answers', []);
            if (count($answers) < count($questions)) {
                return redirect()->route('lms.classroom.show', ['course' => $course->id, 'step' => 'final'])
                    ->withErrors(['answers' => 'Harap pilih jawaban untuk seluruh soal Latihan Akhir sebelum mengumpulkan.'])
                    ->withInput();
            }

            $correctCount = 0;
            foreach ($questions as $idx => $q) {
                $chosen = isset($answers[$idx]) ? (int) $answers[$idx] : -1;
                $expected = (int) ($q['answer'] ?? 0);
                $isCorrect = $chosen === $expected;
                if ($isCorrect) {
                    $correctCount++;
                }
                $chosenText = $q['options'][$chosen] ?? 'Tidak dijawab';
                $summaryLines[] = 'Soal ' . ($idx + 1) . ': ' . $chosenText . ($isCorrect ? ' (Benar)' : ' (Salah)');
            }

            $calculatedScore = (int) round(($correctCount / count($questions)) * 100);

            if ($calculatedScore < $passingScore) {
                AssessmentSubmission::updateOrCreate(
                    ['assessment_id' => $assessment->id, 'user_id' => $user->id],
                    [
                        'response_text' => implode("\n", $summaryLines) . "\nCatatan Refleksi: " . trim((string) $request->input('response_text', '-')),
                        'score' => $calculatedScore,
                        'status' => 'returned',
                        'feedback' => "Skor Latihan Akhir {$calculatedScore}% belum mencapai batas kelulusan {$passingScore}%. Silakan ulangi pengerjaan.",
                        'submitted_at' => now(),
                    ]
                );

                return redirect()->route('lms.classroom.show', ['course' => $course->id, 'step' => 'final'])
                    ->with('warning', "Skor Latihan Akhir Anda {$calculatedScore}% ({$correctCount}/" . count($questions) . " benar). Batas minimum kelulusan adalah {$passingScore}%. Silakan pelajari kembali dan coba lagi.")
                    ->withInput();
            }
        } else {
            $request->validate([
                'response_text' => ['required', 'string', 'min:10', 'max:20000'],
            ], [
                'response_text.required' => 'Jawaban evaluasi akhir wajib diisi.',
                'response_text.min' => 'Jawaban evaluasi akhir minimal 10 karakter.',
            ]);
        }

        $reflection = trim((string) $request->input('response_text', ''));
        $compiledResponse = !empty($summaryLines)
            ? implode("\n", $summaryLines) . ($reflection !== '' ? "\n\nRefleksi Penerapan:\n" . $reflection : '')
            : $reflection;

        $certNo = DB::transaction(function () use ($assessment, $user, $course, $compiledResponse, $calculatedScore) {
            AssessmentSubmission::updateOrCreate(
                ['assessment_id' => $assessment->id, 'user_id' => $user->id],
                [
                    'response_text' => $compiledResponse,
                    'score' => $calculatedScore,
                    'status' => 'reviewed',
                    'feedback' => 'Lulus Evaluasi Latihan Akhir dengan nilai ' . $calculatedScore . '/100.',
                    'submitted_at' => now(),
                    'reviewed_at' => now(),
                ]
            );

            ActivityLog::record(
                $user->id,
                'assessment_submitted',
                "Lulus Latihan Akhir: {$assessment->title} pada kursus {$course->title} (Skor: {$calculatedScore})"
            );

            return $this->issueCourseCertificateIfEligible($user->id, $course);
        });

        $msg = $certNo
            ? "Selamat! Anda lulus Latihan Akhir dengan nilai {$calculatedScore}% dan Sertifikat Kompetensi (#{$certNo}) telah diterbitkan!"
            : "Selamat! Anda lulus Latihan Akhir dengan nilai {$calculatedScore}%.";

        return redirect()->route('lms.classroom.show', ['course' => $course->id, 'step' => 'certificate'])
            ->with('success', $msg);
    }

    /**
     * Stream a local video file from storage/app/public/videos with HTTP Range (206 Partial Content) support.
     */
    public function streamVideo(Request $request, string $filename): BinaryFileResponse
    {
        SecurityHelper::ensureSampleVideoExists();

        $safeName = basename($filename);
        $fullPath = storage_path('app/public/videos/' . $safeName);

        if (!File::exists($fullPath)) {
            $fullPath = storage_path('app/public/videos/fkksm-sample.mp4');
        }

        if (!File::exists($fullPath)) {
            abort(404, 'Berkas video tidak ditemukan di storage.');
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mime = $ext === 'webm' ? 'video/webm' : 'video/mp4';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function resolveOrderedMaterials(Course $course, bool $isOwnerOrAdmin): Collection
    {
        $orderedMaterials = $course->modules->flatMap->materials->values();
        if ($orderedMaterials->isEmpty()) {
            $orderedMaterials = $course->materials()
                ->when(!$isOwnerOrAdmin, fn ($q) => $q->where('is_published', true))
                ->orderBy('sort_order')
                ->get();
        }

        return $orderedMaterials->values();
    }

    private function issueCourseCertificateIfEligible(int $userId, Course $course): ?string
    {
        Enrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

        $existingCertificate = Certificate::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->lockForUpdate()
            ->first();

        if ($existingCertificate) {
            return $existingCertificate->certificate_no;
        }

        $certNo = 'CERT-MM2100-' . date('Y') . '-' . strtoupper(Str::random(6));
        Certificate::create([
            'certificate_no' => $certNo,
            'user_id' => $userId,
            'course_id' => $course->id,
            'issued_by' => $course->instructor_id ?? $userId,
            'issued_at' => now(),
        ]);

        ActivityLog::record(
            $userId,
            'certificate_issued',
            "Menerbitkan sertifikat {$certNo} untuk kursus {$course->title}"
        );

        return $certNo;
    }
}

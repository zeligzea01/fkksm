<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\UserProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function show(Course $course, Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        // Check enrollment or admin/instructor access
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment && !$user->isAdmin() && $course->instructor_id !== $user->id) {
            return redirect()->route('lms.courses.show', $course)->with('warning', 'Silakan mendaftar terlebih dahulu untuk mengakses materi ruang kelas.');
        }

        $course->load([
            'modules.materials' => fn ($q) => $q->orderBy('sort_order'),
            'assessments' => fn ($q) => $q->where('is_published', true),
        ]);

        // Get completed material IDs for this user
        $completedMaterialIds = UserProgress::where('user_id', $user->id)
            ->whereIn('material_id', $course->materials()->pluck('id'))
            ->pluck('material_id')
            ->toArray();

        // Total materials count
        $totalMaterials = $course->materials()->count();
        $completedCount = count($completedMaterialIds);
        $progressPercent = $totalMaterials > 0 ? (int)round(($completedCount / $totalMaterials) * 100) : 0;

        // Current active material
        $materialId = $request->query('material');
        $currentMaterial = null;

        if ($materialId) {
            $currentMaterial = Material::where('course_id', $course->id)->find($materialId);
        }

        if (!$currentMaterial) {
            // Find first uncompleted material or first material
            $firstUncompleted = $course->materials()->whereNotIn('id', $completedMaterialIds)->orderBy('sort_order')->first();
            $currentMaterial = $firstUncompleted ?? $course->materials()->orderBy('sort_order')->first();
        }

        $certificate = Certificate::where('user_id', $user->id)->where('course_id', $course->id)->first();

        return view('lms.classroom.show', compact(
            'course',
            'currentMaterial',
            'completedMaterialIds',
            'progressPercent',
            'completedCount',
            'totalMaterials',
            'enrollment',
            'certificate'
        ));
    }

    public function completeMaterial(Course $course, Material $material): RedirectResponse
    {
        $user = Auth::user();

        // Check progress existence
        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id],
            ['completed_at' => now()]
        );

        ActivityLog::record(
            $user->id,
            'material_completed',
            "Menyelesaikan materi: {$material->title} pada kursus {$course->title}"
        );

        // Check if all materials in course are completed
        $totalMaterials = $course->materials()->count();
        $completedCount = UserProgress::where('user_id', $user->id)
            ->whereIn('material_id', $course->materials()->pluck('id'))
            ->count();

        $message = "Materi '{$material->title}' ditandai selesai.";

        if ($totalMaterials > 0 && $completedCount >= $totalMaterials) {
            // Mark enrollment as completed
            Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

            // Issue Certificate if not exists
            $existingCertificate = Certificate::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if (!$existingCertificate) {
                $certNo = 'CERT-MM2100-' . date('Y') . '-' . strtoupper(Str::random(6));
                $newCert = Certificate::create([
                    'certificate_no' => $certNo,
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'issued_by' => $course->instructor_id ?? Auth::id(),
                    'issued_at' => now(),
                ]);

                ActivityLog::record(
                    $user->id,
                    'certificate_issued',
                    "Menerbitkan sertifikat {$certNo} untuk kursus {$course->title}"
                );

                $message = "Selamat! Anda telah menyelesaikan seluruh materi kursus dan Sertifikat Kompetensi Anda (#{$certNo}) berhasil diterbitkan!";
            }
        }

        // Navigate to next material
        $nextMaterial = Material::where('course_id', $course->id)
            ->where('sort_order', '>', $material->sort_order)
            ->orderBy('sort_order')
            ->first();

        $redirectParams = $nextMaterial ? ['material' => $nextMaterial->id] : [];

        return redirect()->route('lms.classroom.show', array_merge(['course' => $course->id], $redirectParams))
            ->with('success', $message);
    }
}


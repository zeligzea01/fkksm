<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LandingSection;
use App\Models\Lead;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'total_users' => User::count(),
                'total_courses' => Course::count(),
                'active_enrollments' => Enrollment::where('status', 'active')->count(),
                'issued_certificates' => Certificate::where('is_revoked', false)->count(),
                'pending_leads' => Lead::where('status', 'new')->count(),
                'submissions_to_review' => AssessmentSubmission::where('status', 'submitted')->count(),
                'active_subscriptions' => Subscription::where('status', 'active')->count(),
                'landing_sections' => count(LandingSection::defaults()),
            ];

            $recentActivities = ActivityLog::with('user')->latest()->take(8)->get();
            $recentCourses = Course::with('instructor')->latest()->take(5)->get();
            $recentLeads = Lead::latest()->take(5)->get();

            return view('lms.dashboard', compact('stats', 'recentActivities', 'recentCourses', 'recentLeads'));
        }

        if ($user->isInstructor()) {
            $instructorCourseIds = Course::where('instructor_id', $user->id)->pluck('id');
            $stats = [
                'my_courses' => $instructorCourseIds->count(),
                'total_students' => Enrollment::whereIn('course_id', $instructorCourseIds)->distinct('user_id')->count('user_id'),
                'pending_submissions' => AssessmentSubmission::whereHas('assessment', fn ($q) => $q->whereIn('course_id', $instructorCourseIds))
                    ->where('status', 'submitted')
                    ->count(),
            ];

            $myCourses = Course::where('instructor_id', $user->id)->withCount(['enrollments', 'materials'])->get();
            $pendingSubmissions = AssessmentSubmission::with(['user', 'assessment.course'])
                ->whereHas('assessment', fn ($q) => $q->whereIn('course_id', $instructorCourseIds))
                ->where('status', 'submitted')
                ->latest()
                ->take(10)
                ->get();

            return view('lms.dashboard', compact('stats', 'myCourses', 'pendingSubmissions'));
        }

        // Student View - Integrated telemetry matching Figma Dashboard (Layar 1)
        $enrollments = Enrollment::with(['course.modules.materials', 'course.instructor'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->latest('enrolled_at')
            ->get();

        $progressRecords = UserProgress::with(['material.course'])
            ->where('user_id', $user->id)
            ->latest('completed_at')
            ->get();

        $completedMaterialIds = $progressRecords->pluck('material_id')->flip()->toArray();
        $latestProgress = $progressRecords->first();

        // Compute aggregate telemetry reusing loaded module->materials relation
        $totalMaterials = 0;
        $completedMaterials = 0;
        $totalModules = 0;
        $completedModules = 0;
        $featuredEnrollment = $enrollments->firstWhere('status', 'active') ?? $enrollments->first();
        $recommendedMaterials = collect();

        foreach ($enrollments as $enr) {
            if (!$enr->course) {
                continue;
            }
            $courseMats = $enr->course->modules->flatMap->materials->where('is_published', true);
            $totalMaterials += $courseMats->count();
            $completedMaterials += $courseMats->filter(fn ($m) => isset($completedMaterialIds[$m->id]))->count();

            foreach ($enr->course->modules as $mod) {
                $totalModules++;
                $modMats = $mod->materials->where('is_published', true);
                if ($modMats->isNotEmpty() && $modMats->every(fn ($m) => isset($completedMaterialIds[$m->id]))) {
                    $completedModules++;
                }
            }

            foreach ($courseMats as $mat) {
                if ($recommendedMaterials->count() < 3) {
                    $mat->setRelation('course', $enr->course);
                    $recommendedMaterials->push($mat);
                }
            }
        }

        $enrolledCourseIds = $enrollments->pluck('course_id');

        $assessments = Assessment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->with(['course', 'submissions' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->get();

        $totalQuizzes = $assessments->count();
        $completedQuizzes = $assessments->filter(fn ($a) => $a->submissions->isNotEmpty())->count();
        $progressPercent = $totalMaterials > 0 ? (int) round(($completedMaterials / $totalMaterials) * 100) : 0;

        // Build "Uji pemahamanmu" list (up to 3 assessments)
        $upcomingQuizzes = $assessments->take(3);

        $availableCourses = Course::published()
            ->whereNotIn('id', $enrolledCourseIds)
            ->with('instructor')
            ->withCount('materials')
            ->take(4)
            ->get();

        $certificates = Certificate::with('course')
            ->where('user_id', $user->id)
            ->where('is_revoked', false)
            ->get();

        $activeSubscription = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('ends_at')
            ->first();

        return view('lms.dashboard', compact(
            'enrollments',
            'featuredEnrollment',
            'availableCourses',
            'certificates',
            'completedMaterialIds',
            'latestProgress',
            'totalMaterials',
            'completedMaterials',
            'totalModules',
            'completedModules',
            'totalQuizzes',
            'completedQuizzes',
            'progressPercent',
            'recommendedMaterials',
            'upcomingQuizzes',
            'activeSubscription'
        ));
    }
}

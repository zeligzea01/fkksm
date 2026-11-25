<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AssessmentSubmission;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lead;
use App\Models\User;
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
            ];

            $recentActivities = ActivityLog::with('user')->latest()->take(10)->get();
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

        // Student View - Eager loaded without N+1 queries
        $enrollments = Enrollment::with(['course.materials', 'course.instructor'])
            ->where('user_id', $user->id)
            ->latest('enrolled_at')
            ->get();

        // Pre-fetch completed material IDs once to eliminate N+1 queries in blade loop
        $completedMaterialIds = \App\Models\UserProgress::where('user_id', $user->id)
            ->pluck('material_id')
            ->flip()
            ->toArray();

        $enrolledCourseIds = $enrollments->pluck('course_id');
        $availableCourses = Course::published()
            ->whereNotIn('id', $enrolledCourseIds)
            ->with('instructor')
            ->take(6)
            ->get();

        $certificates = Certificate::with('course')
            ->where('user_id', $user->id)
            ->where('is_revoked', false)
            ->get();

        return view('lms.dashboard', compact('enrollments', 'availableCourses', 'certificates', 'completedMaterialIds'));
    }
}


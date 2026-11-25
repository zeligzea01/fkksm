<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::with(['modules.materials', 'instructor'])
            ->latest()
            ->paginate(10);

        return view('lms.materials.index', compact('courses'));
    }

    public function storeModule(Request $request, Course $course): RedirectResponse
    {
        abort_unless(Auth::user()->isInstructor(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'summary' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $maxOrder = $course->modules()->max('sort_order') ?? 0;

        $module = CourseModule::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ($maxOrder + 1),
            'is_active' => true,
        ]);

        ActivityLog::record(Auth::id(), 'module_created', "Modul baru '{$module->title}' ditambahkan ke {$course->title}");

        return back()->with('success', "Modul '{$module->title}' berhasil ditambahkan.");
    }

    public function storeMaterial(Request $request, Course $course): RedirectResponse
    {
        abort_unless(Auth::user()->isInstructor(), 403);

        $validated = $request->validate([
            'module_id' => ['nullable', 'exists:course_modules,id'],
            'title' => ['required', 'string', 'max:180'],
            'content_type' => ['required', 'in:text,link,file'],
            'content_text' => ['nullable', 'string'],
            'content_url' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $maxOrder = $course->materials()->max('sort_order') ?? 0;

        $material = Material::create([
            'course_id' => $course->id,
            'module_id' => $validated['module_id'] ?? null,
            'title' => $validated['title'],
            'content_type' => $validated['content_type'],
            'content_text' => $validated['content_text'] ?? null,
            'content_url' => $validated['content_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ($maxOrder + 1),
            'is_published' => true,
        ]);

        ActivityLog::record(Auth::id(), 'material_created', "Materi baru '{$material->title}' ditambahkan ke {$course->title}");

        return back()->with('success', "Materi '{$material->title}' berhasil ditambahkan ke kursus.");
    }

    public function destroyMaterial(Material $material): RedirectResponse
    {
        abort_unless(Auth::user()->isInstructor(), 403);
        $title = $material->title;
        $material->delete();

        ActivityLog::record(Auth::id(), 'record_deleted', "Materi '{$title}' dihapus.");

        return back()->with('success', "Materi '{$title}' berhasil dihapus.");
    }

    public function destroyModule(CourseModule $module): RedirectResponse
    {
        abort_unless(Auth::user()->isInstructor(), 403);
        $title = $module->title;
        $module->delete();

        ActivityLog::record(Auth::id(), 'record_deleted', "Modul '{$title}' beserta materinya dihapus.");

        return back()->with('success', "Modul '{$title}' berhasil dihapus.");
    }
}


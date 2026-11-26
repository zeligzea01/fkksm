<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\LandingSection;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function index(): View
    {
        // Dynamic landing sections controlled by Admin CMS
        $sections = LandingSection::getAllForHome();

        // Optimized pagination with eager loading
        $courses = Course::published()
            ->with(['instructor', 'materials'])
            ->withCount('materials')
            ->latest()
            ->paginate(8)
            ->withQueryString();

        // Cached portal stats to reduce redundant database queries (TTL: 1 hour)
        $portalStats = Cache::remember('fkksm_portal_stats', 3600, function () {
            return [
                'total_courses' => Course::published()->count(),
                'total_students' => User::where('role', 'student')->count(),
                'total_certificates' => Certificate::where('is_revoked', false)->count(),
                'total_members' => 1250,
            ];
        });

        $totalCourses = $portalStats['total_courses'];
        $totalStudents = $portalStats['total_students'];
        $totalCertificates = $portalStats['total_certificates'];
        $totalMembers = $portalStats['total_members'];

        $vouchers = Cache::remember('fkksm_active_vouchers', 3600, function () {
            return Voucher::where('status', 'active')->take(3)->get();
        });

        $siteSettings = [
            'contact_email' => Setting::get('contact_email', 'sekretariat@fkksm-mm2100.or.id'),
            'contact_phone' => Setting::get('contact_phone', '+62 21 8980 1234'),
            'address' => Setting::get('address', 'Kawasan Industri MM2100, Cikarang Barat, Bekasi 17530'),
        ];

        return view('landing-pages.index', compact(
            'sections',
            'courses',
            'totalCourses',
            'totalStudents',
            'totalCertificates',
            'totalMembers',
            'vouchers',
            'siteSettings'
        ));
    }

    public function berita(Request $request): View
    {
        $category = $request->query('category', 'all');
        return view('landing-pages.berita', compact('category'));
    }

    public function members(): View
    {
        return view('landing-pages.members');
    }

    public function hubungi(): View
    {
        return view('landing-pages.hubungi');
    }

    public function profileFkksm(): View
    {
        return view('profile.fkksm');
    }

    public function submitLead(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:160'],
            'email' => ['required_without:phone', 'nullable', 'email', 'max:190'],
            'phone' => ['required_without:email', 'nullable', 'string', 'max:50'],
            'interest' => ['nullable', 'string', 'max:180'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'source' => ['nullable', 'string', 'max:140'],
        ], [
            'full_name.required' => 'Nama lengkap dan instansi wajib diisi.',
            'email.required_without' => 'Harap isi minimal salah satu kontak: Email atau Nomor Telepon.',
            'phone.required_without' => 'Harap isi minimal salah satu kontak: Telepon atau Email.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $lead = Lead::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'interest' => $validated['interest'] ?? 'Informasi Keanggotaan FKKSM MM2100',
            'notes' => $validated['notes'] ?? null,
            'source' => $validated['source'] ?? 'Website FKKSM MM2100 Portal',
            'status' => 'new',
            'created_by' => auth()->id(),
        ]);

        ActivityLog::record(
            auth()->id(),
            'lead_created',
            "Calon peserta/industri baru: {$lead->full_name} ({$lead->email} / {$lead->phone})"
        );

        return back()->with('success', 'Terima kasih! Pesan dan formulir Anda telah kami terima. Tim sekretariat FKKSM MM2100 akan segera menghubungi Anda.');
    }

    public function verifyCertificate(Request $request, ?string $code = null): View
    {
        $rawCode = trim((string) ($code ?? $request->query('code', '')));
        $searchCode = $rawCode !== '' ? mb_substr($rawCode, 0, 80) : null;
        $certificate = null;

        if ($searchCode) {
            $certificate = Certificate::with(['user', 'course', 'issuer'])
                ->where('certificate_no', $searchCode)
                ->first();
        }

        return view('landing-pages.certificate-verify', compact('certificate', 'searchCode'));
    }
}

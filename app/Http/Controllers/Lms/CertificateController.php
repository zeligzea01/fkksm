<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Certificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $certificates = Certificate::with(['user', 'course', 'issuer'])
                ->latest('issued_at')
                ->paginate(20);
        } else {
            $certificates = Certificate::with(['course', 'issuer'])
                ->where('user_id', $user->id)
                ->latest('issued_at')
                ->paginate(20);
        }

        return view('lms.certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate): View
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $certificate->user_id !== $user->id) {
            abort(403);
        }

        $certificate->load(['user', 'course.instructor', 'issuer']);

        return view('lms.certificates.show', compact('certificate'));
    }

    public function toggleRevoke(Certificate $certificate): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $certificate->is_revoked = !$certificate->is_revoked;
        $certificate->save();

        $action = $certificate->is_revoked ? 'certificate_revoked' : 'certificate_restored';
        $statusText = $certificate->is_revoked ? 'dicabut' : 'dipulihkan';

        ActivityLog::record(
            Auth::id(),
            $action,
            "Sertifikat #{$certificate->certificate_no} {$statusText} oleh administrator."
        );

        return back()->with('success', "Status sertifikat #{$certificate->certificate_no} berhasil {$statusText}.");
    }
}


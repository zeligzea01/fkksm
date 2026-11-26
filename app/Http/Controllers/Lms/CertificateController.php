<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Support\SecurityHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'latest');

        $query = Certificate::with(['user', 'course', 'issuer']);

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($search !== '') {
            $escaped = SecurityHelper::escapeLike($search);
            $query->where(function ($q) use ($escaped) {
                $q->where('certificate_no', 'like', "%{$escaped}%")
                    ->orWhereHas('course', fn ($cq) => $cq->where('title', 'like', "%{$escaped}%"));
            });
        }

        if ($sort === 'oldest') {
            $query->oldest('issued_at');
        } else {
            $query->latest('issued_at');
        }

        $certificates = $query->paginate(18)->withQueryString();

        return view('lms.certificates.index', compact('certificates', 'search', 'sort'));
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

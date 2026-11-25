<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $status = $request->query('status');
        $search = $request->query('search');

        $query = Lead::latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('interest', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(20)->withQueryString();
        $counts = [
            'all' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'closed' => Lead::where('status', 'closed')->count(),
        ];

        return view('lms.leads.index', compact('leads', 'counts', 'status', 'search'));
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,qualified,converted,closed'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldStatus = $lead->status;
        $lead->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $lead->notes,
        ]);

        ActivityLog::record(
            Auth::id(),
            'lead_updated',
            "Status calon peserta {$lead->full_name} diubah dari {$oldStatus} ke {$lead->status}"
        );

        return back()->with('success', "Status lead '{$lead->full_name}' berhasil diperbarui.");
    }
}


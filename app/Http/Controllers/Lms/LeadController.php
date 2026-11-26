<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Lead;
use App\Support\SecurityHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $status = trim((string) $request->query('status', ''));
        $search = trim((string) $request->query('search', ''));

        $query = Lead::latest();

        if ($status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $escaped = SecurityHelper::escapeLike($search);
            $query->where(function ($q) use ($escaped) {
                $q->where('full_name', 'like', "%{$escaped}%")
                  ->orWhere('email', 'like', "%{$escaped}%")
                  ->orWhere('phone', 'like', "%{$escaped}%")
                  ->orWhere('interest', 'like', "%{$escaped}%");
            });
        }

        $leads = $query->paginate(20)->withQueryString();
        $grouped = Lead::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'all' => (int) $grouped->sum(),
            'new' => (int) ($grouped['new'] ?? 0),
            'contacted' => (int) ($grouped['contacted'] ?? 0),
            'qualified' => (int) ($grouped['qualified'] ?? 0),
            'converted' => (int) ($grouped['converted'] ?? 0),
            'closed' => (int) ($grouped['closed'] ?? 0),
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


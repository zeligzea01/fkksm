<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Support\SecurityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $search = trim((string) $request->query('search', ''));
        $action = $request->query('action');
        $userId = $request->query('user_id');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        if ($dateFrom && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $dateFrom)) {
            $dateFrom = null;
        }
        if ($dateTo && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $dateTo)) {
            $dateTo = null;
        }

        $query = ActivityLog::with('user')->latest();

        if ($search !== '') {
            $escaped = SecurityHelper::escapeLike($search);
            $query->where(function ($q) use ($escaped) {
                $q->where('details', 'like', "%{$escaped}%")
                  ->orWhere('ip_address', 'like', "%{$escaped}%")
                  ->orWhere('action', 'like', "%{$escaped}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$escaped}%")->orWhere('email', 'like', "%{$escaped}%"));
            });
        }

        if ($action && $action !== 'all') {
            $query->where('action', $action);
        }

        if ($userId && $userId !== 'all') {
            $query->where('user_id', $userId);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $query->paginate(30)->withQueryString();

        // Audit Summary Stats
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'unique_users' => ActivityLog::distinct('user_id')->whereNotNull('user_id')->count('user_id'),
            'failed_logins' => ActivityLog::where('action', 'login_failed')->count(),
            'security_events' => ActivityLog::whereIn('action', ['password_changed', 'login_failed', 'record_deleted', 'certificate_revoked'])->count(),
        ];

        $actions = ActivityLog::select('action')->distinct()->pluck('action');
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('lms.audit.index', compact('logs', 'stats', 'actions', 'users', 'search', 'action', 'userId', 'dateFrom', 'dateTo'));
    }
}


<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Support\SecurityHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $search = trim((string) $request->query('search', ''));
        $role = $request->query('role');
        $status = $request->query('status');

        $query = User::withCount(['enrollments', 'certificates'])->latest();

        if ($search !== '') {
            $escaped = SecurityHelper::escapeLike($search);
            $query->where(function ($q) use ($escaped) {
                $q->where('name', 'like', "%{$escaped}%")
                  ->orWhere('email', 'like', "%{$escaped}%")
                  ->orWhere('username', 'like', "%{$escaped}%");
            });
        }

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('lms.users.index', compact('users', 'search', 'role', 'status'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        if ($user->role === 'super_admin') {
            return back()->with('error', 'Akun Administrator Utama dilindungi dan tidak dapat diubah statusnya.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang Anda gunakan.');
        }

        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        ActivityLog::record(
            Auth::id(),
            'record_updated',
            "Status pengguna {$user->name} (#{$user->id}) diubah menjadi {$user->status}."
        );

        return back()->with('success', "Status akun {$user->name} berhasil diubah menjadi {$user->status}.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        $validated = $request->validate([
            'role' => ['required', 'in:super_admin,admin,instructor,student,company'],
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $validated['role']]);

        ActivityLog::record(
            Auth::id(),
            'record_updated',
            "Perubahan hak akses peran {$user->name} dari {$oldRole} menjadi {$user->role}."
        );

        return back()->with('success', "Peran untuk {$user->name} berhasil diperbarui.");
    }
}


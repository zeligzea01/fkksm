<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        return view('lms.account.index', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email', ''))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => trim($validated['name']),
            'email' => $validated['email'],
        ]);

        ActivityLog::record($user->id, 'profile_updated', 'Memperbarui informasi profil akun.');

        return back()->with('success', 'Profil akun Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->must_change_password = false;
        $user->setRememberToken(Str::random(60));
        $user->save();

        $request->session()->regenerate();
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        ActivityLog::record($user->id, 'password_changed', 'Mengubah kata sandi akun dan menghentikan sesi perangkat lain.');

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui.');
    }

    public function markNotificationsRead(): RedirectResponse
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}


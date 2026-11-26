<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services, rate limiters, and shared LMS composers.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        RateLimiter::for('login', function (Request $request) {
            $loginKey = Str::lower(trim((string) $request->input('login', ''))) . '|' . $request->ip();

            return Limit::perMinute(5)->by($loginKey)->response(function () {
                return back()->withErrors([
                    'login' => 'Terlalu banyak percobaan login yang gagal. Silakan tunggu 1 menit sebelum mencoba lagi.',
                ])->onlyInput('login');
            });
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(5)->by((string) $request->ip())->response(function () {
                return back()->withErrors([
                    'email' => 'Batas percobaan pendaftaran tercapai. Silakan tunggu 1 menit.',
                ])->withInput();
            });
        });

        RateLimiter::for('lead-submit', function (Request $request) {
            return Limit::perMinute(10)->by((string) $request->ip())->response(function () {
                return back()->with('error', 'Terlalu banyak pengiriman pesan dalam waktu singkat. Mohon tunggu sebentar.');
            });
        });

        RateLimiter::for('voucher-ops', function (Request $request) {
            $key = ($request->user()?->id ?? 'guest') . '|' . $request->ip();

            return Limit::perMinute(15)->by($key);
        });

        RateLimiter::for('sso', function (Request $request) {
            return Limit::perMinute(20)->by((string) $request->ip());
        });

        View::composer('layouts.lms', function ($view) {
            $authUser = Auth::user();
            if ($authUser) {
                $userNotifications = Notification::query()
                    ->select(['id', 'title', 'body', 'read_at', 'created_at'])
                    ->where('user_id', $authUser->id)
                    ->latest()
                    ->take(6)
                    ->get();
                $unreadNotifCount = $userNotifications->whereNull('read_at')->count();
            } else {
                $userNotifications = collect();
                $unreadNotifCount = 0;
            }

            $view->with([
                'authUser' => $authUser,
                'userNotifications' => $userNotifications,
                'unreadNotifCount' => $unreadNotifCount,
            ]);
        });
    }
}

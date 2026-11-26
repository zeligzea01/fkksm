<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $plans = Plan::where('status', 'active')->orderBy('price')->get();

        if ($user->isAdmin()) {
            $allPlans = Plan::withCount('subscriptions')->orderBy('price')->get();
            $subscriptions = Subscription::with(['user', 'plan'])
                ->latest()
                ->paginate(20);

            return view('lms.subscriptions.index', compact('allPlans', 'plans', 'subscriptions'));
        }

        $freePlan = $plans->firstWhere('price', '<=', 0);
        $paidPlans = $plans->filter(fn ($p) => (float) $p->price > 0)->values();

        $activeSubscription = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('ends_at')
            ->first();

        $subscriptionHistory = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('lms.subscriptions.index', compact(
            'plans',
            'freePlan',
            'paidPlans',
            'activeSubscription',
            'subscriptionHistory'
        ));
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'plan_id' => ['nullable', 'exists:plans,id'],
            'mode' => ['nullable', 'in:free,paid,renew'],
        ]);

        if (!empty($validated['plan_id'])) {
            $plan = Plan::where('id', $validated['plan_id'])->where('status', 'active')->first();
            if (!$plan) {
                return back()->with('error', 'Paket layanan yang dipilih sedang tidak aktif atau tidak tersedia.');
            }
        } elseif (($validated['mode'] ?? '') === 'free') {
            $plan = Plan::where('status', 'active')->where('price', '<=', 0)->first();
        } else {
            $plan = Plan::where('status', 'active')->where('price', '>', 0)->first();
        }

        if (!$plan) {
            $plan = Plan::firstOrCreate(
                ['name' => 'Akses Gratis Komunitas'],
                [
                    'description' => 'Akses kursus dasar K3 dan materi publik FKKSM MM2100.',
                    'price' => 0,
                    'duration_days' => 365,
                    'status' => 'active',
                ]
            );
        }

        $days = $plan->duration_days ?: 30;
        $mode = $validated['mode'] ?? '';

        $message = DB::transaction(function () use ($user, $plan, $days, $mode) {
            $existingActive = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            if ($mode === 'renew' && $existingActive) {
                $baseEnd = $existingActive->ends_at && $existingActive->ends_at->isFuture()
                    ? $existingActive->ends_at
                    : now();
                $existingActive->update([
                    'ends_at' => $baseEnd->copy()->addDays($days),
                ]);
                $msg = "Masa aktif langganan '{$existingActive->plan->name}' berhasil diperpanjang {$days} hari.";
            } else {
                Subscription::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->update(['status' => 'cancelled']);

                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => now()->addDays($days),
                    'created_by' => $user->id,
                ]);
                $msg = "Paket layanan '{$plan->name}' berhasil diaktifkan hingga " . now()->addDays($days)->format('d M Y') . ".";
            }

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Aktivasi Subscription Berhasil',
                'body' => $msg,
                'channel' => 'in_app',
            ]);

            ActivityLog::record($user->id, 'subscription_updated', $msg);

            return $msg;
        });

        return back()->with('success', $message);
    }

    public function cancel(Subscription $subscription): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $subscription->user_id !== $user->id) {
            abort(403);
        }

        $subscription->update(['status' => 'cancelled']);

        ActivityLog::record(
            $user->id,
            'subscription_cancelled',
            "Menghentikan subscription #{$subscription->id} untuk paket {$subscription->plan->name}."
        );

        return back()->with('success', 'Akses paket layanan telah dihentikan.');
    }

    public function storePlan(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:3650'],
        ]);

        $plan = Plan::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'status' => 'active',
        ]);

        ActivityLog::record(Auth::id(), 'plan_created', "Menambahkan paket subscription baru: {$plan->name}");

        return back()->with('success', "Paket layanan '{$plan->name}' berhasil ditambahkan.");
    }

    public function togglePlan(Plan $plan): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $plan->status = $plan->status === 'active' ? 'inactive' : 'active';
        $plan->save();

        ActivityLog::record(Auth::id(), 'plan_updated', "Status paket '{$plan->name}' diubah menjadi {$plan->status}.");

        return back()->with('success', "Status paket '{$plan->name}' diperbarui.");
    }

    public function extendSubscription(Subscription $subscription): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $days = $subscription->plan?->duration_days ?: 30;
        $baseEnd = $subscription->ends_at && $subscription->ends_at->isFuture() ? $subscription->ends_at : now();
        $subscription->update([
            'status' => 'active',
            'ends_at' => $baseEnd->copy()->addDays($days),
        ]);

        ActivityLog::record(Auth::id(), 'subscription_extended', "Admin memperpanjang subscription #{$subscription->id} sebanyak {$days} hari.");

        return back()->with('success', "Subscription #{$subscription->id} berhasil diperpanjang {$days} hari.");
    }
}


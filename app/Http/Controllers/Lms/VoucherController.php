<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Voucher;
use App\Models\VoucherRedemption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $vouchers = Voucher::with(['course', 'creator'])
                ->withCount('redemptions')
                ->latest()
                ->paginate(20);
            $courses = Course::published()->get();

            return view('lms.vouchers.index', compact('vouchers', 'courses'));
        }

        // Student redeemed vouchers
        $redemptions = VoucherRedemption::with('voucher.course')
            ->where('user_id', $user->id)
            ->latest('redeemed_at')
            ->get();

        $activePromos = Voucher::with('course')
            ->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->latest()
            ->take(4)
            ->get();

        return view('lms.vouchers.index', compact('redemptions', 'activePromos'));
    }

    /**
     * AJAX lookup endpoint for "Periksa kode" matching Figma Layar 3B.
     */
    public function check(Request $request): JsonResponse
    {
        $code = strtoupper(trim((string) $request->input('code', '')));
        if ($code === '') {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Masukkan kode voucher terlebih dahulu.',
                'benefit_type_label' => '—',
                'program' => '—',
                'expires_at' => '—',
            ]);
        }

        $voucher = Voucher::with('course')->where('code', $code)->first();

        if (!$voucher) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Kode tidak ditemukan. Periksa kembali penulisan kode Anda.',
                'benefit_type_label' => 'Tidak valid',
                'program' => '—',
                'expires_at' => '—',
            ]);
        }

        $alreadyRedeemed = VoucherRedemption::where('voucher_id', $voucher->id)
            ->where('user_id', Auth::id())
            ->exists();

        $benefitLabel = match ($voucher->benefit_type) {
            'free_access' => 'Akses gratis penuh',
            'discount' => 'Potongan biaya Rp ' . number_format((float) $voucher->discount_value, 0, ',', '.'),
            'program_access' => 'Akses khusus program',
            default => ucfirst($voucher->benefit_type),
        };

        $programName = $voucher->course ? $voucher->course->title : ($voucher->title ?? 'Semua Program FKKSM');
        $expiresLabel = $voucher->expires_at ? $voucher->expires_at->format('d M Y') : 'Tidak terbatas';

        if ($alreadyRedeemed) {
            return response()->json([
                'status' => 'used',
                'message' => 'Kode sudah Anda gunakan sebelumnya.',
                'benefit_type_label' => $benefitLabel,
                'program' => $programName,
                'expires_at' => $expiresLabel,
            ]);
        }

        if ($voucher->status === 'expired' || ($voucher->expires_at && $voucher->expires_at->isPast())) {
            return response()->json([
                'status' => 'expired',
                'message' => 'Masa berlaku kode voucher sudah berakhir.',
                'benefit_type_label' => $benefitLabel,
                'program' => $programName,
                'expires_at' => $expiresLabel,
            ]);
        }

        if (!$voucher->isUsable()) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Voucher sedang nonaktif atau kuota penukaran telah terpenuhi.',
                'benefit_type_label' => $benefitLabel,
                'program' => $programName,
                'expires_at' => $expiresLabel,
            ]);
        }

        return response()->json([
            'status' => 'available',
            'message' => 'Kode valid dan tersedia untuk ditukarkan.',
            'benefit_type_label' => $benefitLabel,
            'program' => $programName,
            'expires_at' => $expiresLabel,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:vouchers,code', 'regex:/^[A-Za-z0-9_-]+$/'],
            'title' => ['required', 'string', 'max:180'],
            'benefit_type' => ['required', 'in:free_access,discount,program_access'],
            'benefit_description' => ['nullable', 'string', 'max:500'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $voucher = Voucher::create([
            'code' => strtoupper($validated['code']),
            'title' => $validated['title'],
            'benefit_type' => $validated['benefit_type'],
            'benefit_description' => $validated['benefit_description'] ?? null,
            'course_id' => $validated['course_id'] ?? null,
            'discount_value' => $validated['discount_value'] ?? 0,
            'max_uses' => $validated['max_uses'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        Cache::forget('fkksm_active_vouchers');
        ActivityLog::record(Auth::id(), 'voucher_created', "Membuat voucher promo: {$voucher->code} ({$voucher->title})");

        return back()->with('success', "Voucher '{$voucher->code}' berhasil dibuat.");
    }

    public function toggleStatus(Voucher $voucher): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $voucher->status = $voucher->status === 'active' ? 'paused' : 'active';
        $voucher->save();

        Cache::forget('fkksm_active_vouchers');
        ActivityLog::record(Auth::id(), 'voucher_updated', "Status voucher {$voucher->code} diubah menjadi {$voucher->status}.");

        return back()->with('success', "Status voucher '{$voucher->code}' berhasil diubah menjadi " . strtoupper($voucher->status) . ".");
    }

    public function redeem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:80'],
        ]);

        $code = strtoupper(trim($validated['code']));
        $userId = Auth::id();

        $result = DB::transaction(function () use ($code, $userId) {
            $voucher = Voucher::where('code', $code)->lockForUpdate()->first();

            if (!$voucher || !$voucher->isUsable()) {
                return ['error' => 'Kode voucher tidak valid, sudah habis kuota, atau telah kedaluwarsa.'];
            }

            $alreadyRedeemed = VoucherRedemption::where('voucher_id', $voucher->id)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->exists();

            if ($alreadyRedeemed) {
                return ['error' => 'Anda sudah pernah menukarkan kode voucher ini.'];
            }

            VoucherRedemption::create([
                'voucher_id' => $voucher->id,
                'user_id' => $userId,
                'redeemed_at' => now(),
            ]);

            $message = "Voucher '{$voucher->code}' berhasil ditukarkan!";

            if ($voucher->course_id) {
                $course = Course::where('id', $voucher->course_id)->where('status', 'published')->first();
                if ($course) {
                    $enrollment = Enrollment::firstOrCreate(
                        ['user_id' => $userId, 'course_id' => $course->id],
                        ['status' => 'active', 'enrolled_at' => now()]
                    );
                    if ($enrollment->status === 'cancelled') {
                        $enrollment->update(['status' => 'active']);
                    }
                    $message .= " Anda telah otomatis terdaftar di kursus: '{$course->title}'.";
                }
            }

            Notification::create([
                'user_id' => $userId,
                'title' => "Voucher {$voucher->code} Berhasil Ditukarkan",
                'body' => $message,
                'channel' => 'in_app',
            ]);

            ActivityLog::record($userId, 'voucher_redeemed', "Menukarkan voucher: {$voucher->code}");

            return ['success' => $message];
        });

        if (isset($result['error'])) {
            return back()->withErrors(['code' => $result['error']]);
        }

        return redirect()->route('lms.vouchers.index')->with('success', $result['success']);
    }
}

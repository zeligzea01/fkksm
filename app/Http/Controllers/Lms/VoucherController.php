<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Voucher;
use App\Models\VoucherRedemption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('lms.vouchers.index', compact('redemptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:vouchers,code', 'regex:/^[A-Z0-9_-]+$/'],
            'title' => ['required', 'string', 'max:180'],
            'benefit_type' => ['required', 'in:free_access,discount,program_access'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $voucher = Voucher::create([
            'code' => strtoupper($validated['code']),
            'title' => $validated['title'],
            'benefit_type' => $validated['benefit_type'],
            'course_id' => $validated['course_id'] ?? null,
            'discount_value' => $validated['discount_value'] ?? 0,
            'max_uses' => $validated['max_uses'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        ActivityLog::record(Auth::id(), 'voucher_created', "Membuat voucher promo: {$voucher->code} ({$voucher->title})");

        return back()->with('success', "Voucher '{$voucher->code}' berhasil dibuat.");
    }

    public function redeem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = strtoupper(trim($validated['code']));
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher || !$voucher->isUsable()) {
            return back()->withErrors(['code' => 'Kode voucher tidak valid, sudah habis kuota, atau telah kedaluwarsa.']);
        }

        $userId = Auth::id();

        // Check if user already redeemed
        $alreadyRedeemed = VoucherRedemption::where('voucher_id', $voucher->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyRedeemed) {
            return back()->withErrors(['code' => 'Anda sudah pernah menukarkan kode voucher ini.']);
        }

        // Record redemption
        VoucherRedemption::create([
            'voucher_id' => $voucher->id,
            'user_id' => $userId,
            'redeemed_at' => now(),
        ]);

        $message = "Voucher '{$voucher->code}' berhasil ditukarkan!";

        // If voucher links to course, enroll immediately
        if ($voucher->course_id) {
            $course = Course::find($voucher->course_id);
            if ($course) {
                Enrollment::firstOrCreate(
                    ['user_id' => $userId, 'course_id' => $course->id],
                    ['status' => 'active', 'enrolled_at' => now()]
                );
                $message .= " Anda telah otomatis terdaftar di kursus: '{$course->title}'.";
            }
        }

        ActivityLog::record($userId, 'voucher_redeemed', "Menukarkan voucher: {$voucher->code}");

        return redirect()->route('lms.vouchers.index')->with('success', $message);
    }
}


<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $settings = [
            'app_title' => Setting::get('app_title', 'FKKSM MM2100 Learning Management System'),
            'notification_channel' => Setting::get('notification_channel', 'in_app'),
            'notification_events' => Setting::get('notification_events', 'course_enrollment,assessment_submission,new_lead'),
            'security_session_minutes' => Setting::get('security_session_minutes', '120'),
            'certificate_signature_name' => Setting::get('certificate_signature_name', 'Dr. Ir. H. Bambang Sudarsono, M.M.'),
            'certificate_signature_title' => Setting::get('certificate_signature_title', 'Ketua Umum FKKSM MM2100'),
            'contact_email' => Setting::get('contact_email', 'sekretariat@fkksm-mm2100.org'),
            'contact_phone' => Setting::get('contact_phone', '+62 21 8980 1234'),
            'address' => Setting::get('address', 'Kawasan Industri MM2100, Cikarang Barat, Bekasi, Jawa Barat 17530'),
        ];

        return view('lms.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'app_title' => ['required', 'string', 'max:180'],
            'notification_channel' => ['required', 'in:in_app,email,other'],
            'notification_events' => ['nullable', 'array'],
            'security_session_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'certificate_signature_name' => ['required', 'string', 'max:160'],
            'certificate_signature_title' => ['required', 'string', 'max:160'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['required', 'string'],
            'address' => ['required', 'string'],
        ]);

        $userId = Auth::id();

        Setting::set('app_title', $validated['app_title'], $userId);
        Setting::set('notification_channel', $validated['notification_channel'], $userId);
        Setting::set('notification_events', implode(',', $validated['notification_events'] ?? []), $userId);
        Setting::set('security_session_minutes', (string)$validated['security_session_minutes'], $userId);
        Setting::set('certificate_signature_name', $validated['certificate_signature_name'], $userId);
        Setting::set('certificate_signature_title', $validated['certificate_signature_title'], $userId);
        Setting::set('contact_email', $validated['contact_email'], $userId);
        Setting::set('contact_phone', $validated['contact_phone'], $userId);
        Setting::set('address', $validated['address'], $userId);

        \App\Models\LandingSection::clearCache();
        ActivityLog::record($userId, 'settings_saved', 'Pengaturan sistem diperbarui oleh administrator.');

        return back()->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}


@extends('layouts.lms')

@section('title', 'Konfigurasi Portal & Keamanan | FKKSM Admin')
@section('header_eyebrow', 'Pengaturan Sistem Utama')
@section('header_title', 'Konfigurasi Portal & Sertifikat')

@section('content')
<div class="w-full max-w-5xl 2xl:max-w-6xl mx-auto space-y-6">
    <!-- Link Shortcut to Dynamic Landing CMS -->
    <div class="bg-[#E7F6F8] rounded-xl border border-[#C7E8EE] p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div>
            <strong class="text-slate-900 block">Ingin mengubah banner, kursus terlaris, skill badges, atau FAQ di halaman Home (/)?</strong>
            <span class="text-slate-600">Gunakan Editor CMS terintegrasi untuk mengatur seluruh konten halaman utama.</span>
        </div>
        <a href="{{ route('lms.landing-cms.index') }}" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold shrink-0 lms-transition">
            Buka Kelola Landing Page →
        </a>
    </div>

    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200">
        <form action="{{ route('lms.settings.update') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div>
                <h3 class="font-semibold text-sm text-slate-900 mb-3 pb-2 border-b border-slate-100">Identitas Aplikasi</h3>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Nama Aplikasi / Portal *</label>
                    <input type="text" name="app_title" value="{{ old('app_title', $settings['app_title']) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC]">
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-sm text-slate-900 mb-3 pb-2 border-b border-slate-100">Parameter Tanda Tangan Sertifikat Digital</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Nama Pejabat Penandatangan *</label>
                        <input type="text" name="certificate_signature_name" value="{{ old('certificate_signature_name', $settings['certificate_signature_name']) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Jabatan Resmi *</label>
                        <input type="text" name="certificate_signature_title" value="{{ old('certificate_signature_title', $settings['certificate_signature_title']) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-sm text-slate-900 mb-3 pb-2 border-b border-slate-100">Keamanan &amp; Notifikasi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Kanal Notifikasi</label>
                        <select name="notification_channel" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                            <option value="in_app" {{ $settings['notification_channel'] === 'in_app' ? 'selected' : '' }}>Dalam Aplikasi (In-App)</option>
                            <option value="email" {{ $settings['notification_channel'] === 'email' ? 'selected' : '' }}>Email (SMTP Server)</option>
                            <option value="other" {{ $settings['notification_channel'] === 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Batas Waktu Sesi (Menit) *</label>
                        <input type="number" name="security_session_minutes" value="{{ old('security_session_minutes', $settings['security_session_minutes']) }}" min="15" max="480" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-sm text-slate-900 mb-3 pb-2 border-b border-slate-100">Informasi Sekretariat MM2100</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Email Sekretariat *</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Telepon Hotline *</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Alamat Kantor Kawasan *</label>
                        <textarea name="address" rows="2" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">{{ old('address', $settings['address']) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold lms-transition">
                    Simpan Konfigurasi Sistem →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

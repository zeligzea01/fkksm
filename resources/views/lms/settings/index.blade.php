@extends('layouts.lms')

@section('title', 'Pengaturan Sistem & Keamanan | FKKSM MM2100')
@section('page_title', 'Pengaturan Sistem LMS & Portal')
@section('page_subtitle', 'Konfigurasi parameter operasional, tanda tangan sertifikat, dan keamanan.')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
    <form action="{{ route('lms.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Identitas Aplikasi</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Aplikasi / Portal *</label>
                    <input type="text" name="app_title" value="{{ old('app_title', $settings['app_title']) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Parameter Tanda Tangan Sertifikat Digital</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Pejabat Penandatangan *</label>
                    <input type="text" name="certificate_signature_name" value="{{ old('certificate_signature_name', $settings['certificate_signature_name']) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan Resmi *</label>
                    <input type="text" name="certificate_signature_title" value="{{ old('certificate_signature_title', $settings['certificate_signature_title']) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Keamanan & Sesi</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kanal Notifikasi</label>
                    <select name="notification_channel" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                        <option value="in_app" {{ $settings['notification_channel'] === 'in_app' ? 'selected' : '' }}>Dalam Aplikasi (In-App)</option>
                        <option value="email" {{ $settings['notification_channel'] === 'email' ? 'selected' : '' }}>Email (SMTP Server)</option>
                        <option value="other" {{ $settings['notification_channel'] === 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Waktu Sesi (Menit) *</label>
                    <input type="number" name="security_session_minutes" value="{{ old('security_session_minutes', $settings['security_session_minutes']) }}" min="15" max="480" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Informasi Sekretariat MM2100</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Sekretariat *</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Telepon Hotline *</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Kantor Kawasan *</label>
                    <textarea name="address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">{{ old('address', $settings['address']) }}</textarea>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition">
                Simpan Konfigurasi Sistem →
            </button>
        </div>
    </form>
</div>
@endsection


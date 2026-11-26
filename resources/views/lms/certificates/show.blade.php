@extends('layouts.lms')

@section('title', 'Sertifikat: ' . $certificate->certificate_no)
@section('header_eyebrow', 'Dokumen Kompetensi Resmi')
@section('header_title', 'Sertifikat Kelulusan Digital')

@section('content')
<div class="w-full max-w-5xl 2xl:max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center print:hidden">
        <a href="{{ route('lms.certificates.index') }}" class="text-xs font-semibold text-slate-600 hover:text-[#0E97A8] lms-transition">
            ← Kembali ke Daftar Sertifikat
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('certificates.verify', ['code' => $certificate->certificate_no]) }}" target="_blank" class="px-4 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs">
                Verifikasi Publik ↗
            </a>
            <button type="button" onclick="window.print()" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs inline-flex items-center gap-2 lms-transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Unduh PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable Certificate Frame -->
    <div class="bg-white rounded-xl p-8 sm:p-12 border border-slate-200 shadow-sm relative overflow-hidden print:border-none print:shadow-none print:p-6">
        <div class="border-2 border-[#10A9BC]/30 p-8 sm:p-10 text-center relative rounded-xl">
            <!-- Header Logos -->
            <div class="flex items-center justify-between mb-8">
                <img src="{{ asset('fkksm/logo-transparent.png') }}" alt="Logo FKKSM" class="h-14 w-auto object-contain">
                <div>
                    <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest leading-none">Forum Komunikasi Komite Sekolah Menengah</h2>
                    <h3 class="text-sm font-extrabold text-slate-900 tracking-tight mt-1">KAWASAN INDUSTRI MM2100 CIKARANG BARAT</h3>
                </div>
                <div class="px-3 py-2 rounded-lg border border-slate-200 font-mono font-bold text-xs text-[#0E97A8]">
                    MM2100
                </div>
            </div>

            <!-- Title -->
            <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#0E97A8] block mb-1">Sertifikat Kelulusan &amp; Kompetensi</span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 tracking-wide">CERTIFICATE OF COMPLETION</h1>
                <p class="text-xs font-mono font-semibold text-slate-500 mt-1.5">NO: {{ $certificate->certificate_no }}</p>
            </div>

            <p class="text-xs uppercase tracking-widest text-slate-400 mb-2">Diberikan kepada:</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0E97A8] underline decoration-[#10A9BC] decoration-2 underline-offset-8 mb-6">
                {{ $certificate->user->name }}
            </h2>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl mx-auto mb-10">
                Atas dedikasi dan keberhasilan menyelesaikan seluruh silabus pelatihan serta uji kompetensi pada program pembelajaran:
                <strong class="text-slate-900 text-base mt-2 block font-sans">{{ $certificate->course->title }}</strong>
            </p>

            <!-- Signatures & Verification Stamp -->
            <div class="grid grid-cols-3 gap-6 pt-8 border-t border-slate-200 items-end">
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-800">{{ $certificate->course->instructor->name ?? 'Instruktur FKKSM MM2100' }}</p>
                    <p class="text-[10px] text-slate-400 uppercase mt-0.5">Instruktur Pelatihan</p>
                </div>
                <div class="text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-slate-900 text-white rounded-lg flex items-center justify-center font-mono text-[9px] p-1 text-center leading-tight mb-1">
                        QR AUDIT VERIFIED
                    </div>
                    <span class="text-[9px] text-slate-400 font-mono">Terverifikasi Sistem</span>
                </div>
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-800">{{ \App\Models\Setting::get('certificate_signature_name', 'Dr. Ir. H. Bambang Sudarsono, M.M.') }}</p>
                    <p class="text-[10px] text-slate-400 uppercase mt-0.5">{{ \App\Models\Setting::get('certificate_signature_title', 'Ketua Umum FKKSM MM2100') }}</p>
                </div>
            </div>

            <div class="mt-8 text-[10px] text-slate-400 font-mono">
                Diterbitkan secara sah pada tanggal: {{ $certificate->issued_at->format('d F Y') }} di Cikarang Barat, Kabupaten Bekasi.
            </div>
        </div>
    </div>
</div>

@if (request()->boolean('print'))
@push('scripts')
<script>
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => window.print(), 350);
    });
</script>
@endpush
@endif
@endsection

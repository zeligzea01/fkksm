@extends('layouts.lms')

@section('title', 'Sertifikat: ' . $certificate->certificate_no)
@section('page_title', 'Sertifikat Kompetensi Digital')
@section('page_subtitle', 'Nomor Registrasi: ' . $certificate->certificate_no)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center print:hidden">
        <a href="{{ route('lms.certificates.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
            ← Kembali ke Daftar Sertifikat
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Sertifikat
        </button>
    </div>

    <!-- Printable Certificate Frame -->
    <div class="bg-white rounded-3xl p-10 sm:p-14 border-8 border-slate-900/10 shadow-2xl relative overflow-hidden select-none print:border-none print:shadow-none print:p-6">
        <!-- Certificate Inner Border -->
        <div class="border-2 border-slate-300 p-8 sm:p-12 text-center relative rounded-2xl">
            <!-- Header Logos -->
            <div class="flex items-center justify-between mb-8">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo FKKSM" class="h-16 w-auto object-contain">
                <div>
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-none">Forum Komunikasi Komite Sekolah Menengah</h2>
                    <h3 class="text-base font-black text-slate-900 tracking-tight mt-1">KAWASAN INDUSTRI MM2100 CIKARANG BARAT</h3>
                </div>
                <div class="w-16 h-16 rounded-full border border-slate-200 flex items-center justify-center font-bold text-xs text-blue-600">
                    MM2100
                </div>
            </div>

            <!-- Title -->
            <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 block mb-1">Sertifikat Kelulusan & Kompetensi</span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 tracking-wide">CERTIFICATE OF COMPLETION</h1>
                <p class="text-xs font-mono font-bold text-slate-500 mt-1">NO: {{ $certificate->certificate_no }}</p>
            </div>

            <p class="text-xs uppercase tracking-widest text-slate-400 mb-2">Diberikan dengan bangga kepada:</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-blue-900 underline decoration-blue-200 decoration-4 underline-offset-8 mb-6">
                {{ $certificate->user->name }}
            </h2>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl mx-auto mb-10">
                Atas dedikasi dan keberhasilan menyelesaikan seluruh silabus pelatihan serta uji kompetensi pada program pembelajaran:
                <br>
                <strong class="text-slate-900 text-base mt-2 block font-sans">{{ $certificate->course->title }}</strong>
            </p>

            <!-- Signatures & Verification Stamp -->
            <div class="grid grid-cols-3 gap-6 pt-8 border-t border-slate-200 items-end">
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-800">{{ $certificate->course->instructor->name ?? 'Instruktur K3 & Hubungan Industrial' }}</p>
                    <p class="text-[10px] text-slate-400 uppercase">Instruktur Pelatihan</p>
                </div>
                <div class="text-center flex flex-col items-center">
                    <!-- QR Verification Placeholder -->
                    <div class="w-16 h-16 bg-slate-900 text-white rounded-lg flex items-center justify-center font-mono text-[9px] p-1 text-center leading-tight mb-1">
                        QR AUDIT VERIFIED
                    </div>
                    <span class="text-[9px] text-slate-400 font-mono">Scan untuk Verifikasi</span>
                </div>
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-800">Dr. Ir. H. Bambang Sudarsono, M.M.</p>
                    <p class="text-[10px] text-slate-400 uppercase">Ketua Komite FKKSM MM2100</p>
                </div>
            </div>

            <div class="mt-8 text-[10px] text-slate-400 font-mono">
                Diterbitkan secara sah pada tanggal: {{ $certificate->issued_at->format('d F Y') }} di Cikarang Barat, Kabupaten Bekasi.
            </div>
        </div>
    </div>
</div>
@endsection


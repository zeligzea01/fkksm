@extends('layouts.app')

@section('title', 'Verifikasi Keaslian Sertifikat Digital | FKKSM MM2100')

@section('content')
<!-- Modular Page Header -->
<x-page-header 
    badge="AUDIT &amp; VALIDASI INTEGRITAS"
    title="Verifikasi Keaslian Sertifikat Digital FKKSM"
    description="Layanan resmi audit publik untuk memverifikasi keabsahan nomor registrasi sertifikat kompetensi pelatihan kawasan industri MM2100."
    :breadcrumbs="[
        ['label' => 'Verifikasi Sertifikat', 'url' => route('certificates.verify')]
    ]"
/>

<div class="max-w-3xl 2xl:max-w-4xl min-[2200px]:max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-xl p-8 border border-[#DCE6EE] shadow-sm mb-8">
        <form action="{{ route('certificates.verify') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-2">
                    Masukkan Nomor Registrasi Sertifikat
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="code" value="{{ $searchCode }}" required placeholder="Contoh: CERT-MM2100-2026-FKKSM01" class="flex-1 px-4 py-3 rounded-lg border border-[#DCE6EE] focus:outline-none focus:border-[#10A9BC] text-xs font-mono uppercase bg-[#F5F8FC]">
                    <button type="submit" class="px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-sm uppercase tracking-wider shadow-sm transition">
                        Periksa Status Sertifikat
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if ($searchCode)
        @if ($certificate)
            <div class="bg-white rounded-xl p-8 border {{ $certificate->is_revoked ? 'border-rose-300' : 'border-emerald-300' }} shadow-md">
                <div class="flex items-center gap-4 mb-6">
                    @if (!$certificate->is_revoked)
                        <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center border border-emerald-300">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono font-bold text-emerald-700 uppercase tracking-widest block">SERTIFIKAT VALID &amp; TERDAFTAR</span>
                            <h2 class="text-xl font-heading font-bold text-[#132B45] font-mono tracking-wider">{{ $certificate->certificate_no }}</h2>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center border border-rose-300">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono font-bold text-rose-700 uppercase tracking-widest block">SERTIFIKAT SUDAH DICABUT / NONAKTIF</span>
                            <h2 class="text-xl font-heading font-bold text-[#132B45] font-mono tracking-wider">{{ $certificate->certificate_no }}</h2>
                        </div>
                    @endif
                </div>

                <div class="space-y-3 border-t border-[#DCE6EE] pt-5 text-xs">
                    <div class="flex justify-between py-2 border-b border-[#F5F8FC]">
                        <span class="text-[#61758A] font-medium">Nama Penerima Sertifikat:</span>
                        <span class="font-bold text-[#132B45]">{{ $certificate->user->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-[#F5F8FC]">
                        <span class="text-[#61758A] font-medium">Program Kursus / Kompetensi:</span>
                        <span class="font-bold text-[#132B45] text-right max-w-xs">{{ $certificate->course->title ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-[#F5F8FC]">
                        <span class="text-[#61758A] font-medium">Kategori Rumpun:</span>
                        <span class="font-semibold text-[#10A9BC]">{{ $certificate->course->category ?? 'Pelatihan Industri' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-[#F5F8FC]">
                        <span class="text-[#61758A] font-medium">Tanggal Diterbitkan:</span>
                        <span class="font-mono text-[#132B45]">{{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('d F Y') : '—' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-[#F5F8FC]">
                        <span class="text-[#61758A] font-medium">Lembaga Penerbit:</span>
                        <span class="font-bold text-[#132B45]">FKKSM Kawasan Industri MM2100</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-[#61758A] font-medium">Status Akreditasi:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-[#D8EDF7] text-[#123A5A]">
                            SKKNI / BNSP COMPLIANT
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="p-8 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-center">
                <div class="w-12 h-12 rounded-full bg-amber-100 border border-amber-300 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-xl uppercase mb-1">Nomor Registrasi Tidak Ditemukan</h3>
                <p class="text-xs text-amber-800">
                    Sertifikat dengan kode <strong class="font-mono">{{ $searchCode }}</strong> tidak terdaftar dalam basis data resmi FKKSM MM2100. Pastikan nomor yang dimasukkan sudah sesuai dengan dokumen fisik atau QR Code Anda.
                </p>
            </div>
        @endif
    @endif
</div>
@endsection

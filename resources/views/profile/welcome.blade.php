@extends('layouts.app')

@section('title', 'Selamat Datang di FKKSM MM2100 | Industrial HR Community')
@section('meta_title', 'FKKSM MM2100 — Transformasi & Kolaborasi Industri Indonesia')
@section('meta_description', 'Portal resmi Forum Komunikasi Karyawan & Manajemen HRD Kawasan Industri MM2100 Cikarang Barat.')

@section('content')
<!-- Modular Page Header -->
<x-page-header 
    badge="SELAMAT DATANG DI FKKSM MM2100"
    title="Pusat Kolaborasi, Regulasi, &amp; Pelatihan Vokasi Industri MM2100"
    description="Pilih portal layanan terpadu yang Anda butuhkan: Eksplorasi profil resmi kawasan industri MM2100 atau ikuti program peningkatan keahlian kerja di Upskill LMS."
    :breadcrumbs="[
        ['label' => 'Portal Layanan', 'url' => '']
    ]"
>
    <x-slot:actions>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('profile.fkksm') }}" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition">
                Profile FKKSM →
            </a>
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-lg bg-[#132B45] hover:bg-[#1A3859] text-white border border-[#176D91]/40 font-heading font-bold text-xs uppercase tracking-wider transition">
                Upskill LMS ↗
            </a>
        </div>
    </x-slot:actions>
</x-page-header>

<div class="fkksm-container py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 rounded-xl bg-white border border-slate-200">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h3 class="font-heading font-bold text-xl text-slate-900">Profile FKKSM</h3>
            <p class="text-xs text-slate-600 mt-2 leading-relaxed">Informasi lengkap struktur komite, direktori 250+ perusahaan, galeri kegiatan, dan berita ketenagakerjaan.</p>
            <a href="{{ route('profile.fkksm') }}" class="inline-block mt-4 text-xs font-bold text-[#10A9BC] hover:underline">Jelajahi Profile →</a>
        </div>
        <div class="p-8 rounded-xl bg-white border border-slate-200">
            <div class="w-12 h-12 rounded-lg bg-cyan-50 text-cyan-700 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5"/></svg>
            </div>
            <h3 class="font-heading font-bold text-xl text-slate-900">Upskill LMS Portal</h3>
            <p class="text-xs text-slate-600 mt-2 leading-relaxed">Belajar mandiri dengan kurikulum K3 industri, standar BNSP, penilaian otomatis, dan sertifikasi digital ber-QR code.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 text-xs font-bold text-[#10A9BC] hover:underline">Mulai Kursus →</a>
        </div>
        <div class="p-8 rounded-xl bg-white border border-slate-200">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="font-heading font-bold text-xl text-slate-900">Verifikasi Sertifikat</h3>
            <p class="text-xs text-slate-600 mt-2 leading-relaxed">Cek keabsahan sertifikat kelulusan kompetensi kerja yang diterbitkan oleh FKKSM Kawasan Industri MM2100.</p>
            <a href="{{ route('certificates.verify') }}" class="inline-block mt-4 text-xs font-bold text-[#10A9BC] hover:underline">Cek Sertifikat →</a>
        </div>
    </div>
</div>
@endsection

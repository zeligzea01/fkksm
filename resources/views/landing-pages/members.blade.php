@extends('layouts.app')

@section('title', 'Struktur Pengurus & Keanggotaan Industri MM2100 | FKKSM')

@section('content')
<!-- Modular Page Header -->
<x-page-header 
    badge="ORGANISASI &amp; KEANGGOTAAN"
    title="Struktur Komite &amp; Keanggotaan Industri MM2100"
    description="Forum sinergi representasi antar pimpinan HR, praktisi K3, direksi manufaktur, dan pengelola kawasan industri modern di Cikarang Barat."
    :breadcrumbs="[
        ['label' => 'Direktori Industri', 'url' => route('members')]
    ]"
/>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Bagan Struktur Organisasi -->
    <div class="bg-white rounded-xl p-8 border border-[#DCE6EE] shadow-sm mb-16">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#10A9BC] block mb-1">
                TATA KELOLA ORGANISASI
            </span>
            <h2 class="font-heading font-extrabold text-3xl text-[#132B45] uppercase tracking-wide">
                BAGAN STRUKTUR PENGURUS FKKSM
            </h2>
            <p class="text-xs text-[#61758A] mt-1">Struktur kepengurusan periode aktif koordinasi industri kawasan MM2100.</p>
        </div>
        <x-org-chart />
    </div>

    <!-- MM2100 Industry Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div class="space-y-4">
            <span class="text-[10px] font-bold text-[#10A9BC] uppercase tracking-[0.2em] block">
                PUSAT MANUFAKTUR STRATEGIS NASIONAL
            </span>
            <h2 class="font-heading font-extrabold text-3xl sm:text-4xl text-[#132B45] uppercase tracking-tight">
                KAWASAN INDUSTRI MM2100 CIKARANG BARAT
            </h2>
            <p class="text-xs sm:text-sm text-[#61758A] leading-relaxed">
                MM2100 adalah salah satu kawasan industri terkemuka di koridor timur Jakarta dengan reputasi infrastruktur kelas dunia, pengelolaan limbah terintegrasi, dan sistem logistik modern yang menaungi lebih dari 1.250 korporasi multinasional dan nasional.
            </p>
            <p class="text-xs sm:text-sm text-[#61758A] leading-relaxed">
                FKKSM berperan sebagai motor penggerak stabilitas hubungan ketenagakerjaan, kepatuhan norma K3, keselamatan kebakaran, pembinaan hubungan bipartit yang harmonis, serta penyiapan talenta vokasi siap kerja.
            </p>
            <div class="pt-2">
                <a href="{{ route('hubungi') }}" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold uppercase tracking-wider text-sm transition shadow-sm inline-block">
                    Daftar Sebagai Anggota Perusahaan →
                </a>
            </div>
        </div>
        <div class="rounded-xl overflow-hidden border border-[#DCE6EE] shadow-sm">
            <img src="{{ asset('images/mm2100.png') }}" onerror="this.onerror=null;this.src='{{ asset('fkksm/assets/images/activity-02.jpg') }}'" alt="Kawasan Industri MM2100" class="w-full h-auto object-cover">
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Berita & Informasi Industri MM2100 | FKKSM')

@section('content')
<!-- Modular Page Header -->
<x-page-header 
    badge="PEMBERITAAN &amp; EDARAN RESMI"
    title="Kabar &amp; Analisis Ketenagakerjaan Kawasan MM2100"
    description="Perkembangan regulasi dinas tenaga kerja, buletin keselamatan kerja (K3), notula forum bipartit, dan tren ketenagakerjaan manufaktur terkini."
    :breadcrumbs="[
        ['label' => 'Berita &amp; Forum', 'url' => route('berita')]
    ]"
/>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- News Card 1 -->
        <article class="bg-white rounded-xl border border-[#DCE6EE] overflow-hidden shadow-sm hover:shadow-md hover:border-[#10A9BC] transition flex flex-col justify-between group">
            <div>
                <div class="h-44 bg-[#132B45] p-6 flex flex-col justify-between text-white relative">
                    <span class="self-start px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#10A9BC] text-white uppercase tracking-wider">
                        REGULASI 2026
                    </span>
                    <span class="text-[11px] text-[#A5B4C2] font-mono">14 MEI 2026 · KETENAGAKERJAAN</span>
                </div>
                <div class="p-6 space-y-3">
                    <h2 class="font-heading font-bold text-2xl text-[#132B45] group-hover:text-[#10A9BC] transition-colors leading-tight">
                        Sosialisasi Aturan Lembur &amp; Fleksibilitas Waktu Kerja Pabrik Otomotif
                    </h2>
                    <p class="text-xs text-[#61758A] leading-relaxed">
                        Panduan kepatuhan perhitungan upah lembur dan hak istirahat mingguan operator produksi shift malam di kawasan industri MM2100 pasca penyesuaian regulasi ketenagakerjaan.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-[#F5F8FC] border-t border-[#DCE6EE] flex items-center justify-between text-xs font-bold text-[#10A9BC]">
                <span>Baca Edaran Lengkap</span>
                <span>→</span>
            </div>
        </article>

        <!-- News Card 2 -->
        <article class="bg-white rounded-xl border border-[#DCE6EE] overflow-hidden shadow-sm hover:shadow-md hover:border-[#10A9BC] transition flex flex-col justify-between group">
            <div>
                <div class="h-44 bg-[#0C3553] p-6 flex flex-col justify-between text-white relative">
                    <span class="self-start px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#13AEB9] text-white uppercase tracking-wider">
                        K3 &amp; LINGKUNGAN
                    </span>
                    <span class="text-[11px] text-[#A5B4C2] font-mono">08 MEI 2026 · ZERO ACCIDENT</span>
                </div>
                <div class="p-6 space-y-3">
                    <h2 class="font-heading font-bold text-2xl text-[#132B45] group-hover:text-[#10A9BC] transition-colors leading-tight">
                        Pencapaian 15 Juta Jam Kerja Nihil Kecelakaan di MM2100
                    </h2>
                    <p class="text-xs text-[#61758A] leading-relaxed">
                        Apresiasi komite FKKSM kepada 35 pabrik komponen presisi yang mempertahankan standar keselamatan kerja tanpa fatalitas selama 3 tahun berturut-turut.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-[#F5F8FC] border-t border-[#DCE6EE] flex items-center justify-between text-xs font-bold text-[#10A9BC]">
                <span>Baca Edaran Lengkap</span>
                <span>→</span>
            </div>
        </article>

        <!-- News Card 3 -->
        <article class="bg-white rounded-xl border border-[#DCE6EE] overflow-hidden shadow-sm hover:shadow-md hover:border-[#10A9BC] transition flex flex-col justify-between group">
            <div>
                <div class="h-44 bg-[#103650] p-6 flex flex-col justify-between text-white relative">
                    <span class="self-start px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#54D6D0] text-[#132B45] uppercase tracking-wider">
                        VOKASI INDUSTRI
                    </span>
                    <span class="text-[11px] text-[#A5B4C2] font-mono">02 MEI 2026 · KONSORSIUM</span>
                </div>
                <div class="p-6 space-y-3">
                    <h2 class="font-heading font-bold text-2xl text-[#132B45] group-hover:text-[#10A9BC] transition-colors leading-tight">
                        Kerja Sama Pemagangan Industri MM2100 dengan 20 SMK Unggulan
                    </h2>
                    <p class="text-xs text-[#61758A] leading-relaxed">
                        Fasilitasi kurikulum sinkronisasi kejuruan mekatronika dan teknik otomasi industri untuk pemenuhan kebutuhan tenaga kerja terampil di koridor Cikarang Barat.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-[#F5F8FC] border-t border-[#DCE6EE] flex items-center justify-between text-xs font-bold text-[#10A9BC]">
                <span>Baca Edaran Lengkap</span>
                <span>→</span>
            </div>
        </article>
    </div>
</div>
@endsection

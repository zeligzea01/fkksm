@extends('layouts.app')

@section('title', 'Hubungi Sekretariat FKKSM MM2100')

@section('content')
<!-- Modular Page Header -->
<x-page-header 
    badge="HUBUNGI KAMI · SEKRETARIAT KAWASAN"
    title="Hubungi Pengurus FKKSM MM2100"
    description="Tim sekretariat FKKSM siap melayani pertanyaan seputar keanggotaan korporasi, permohonan in-house training, mediasi perselisihan bipartit, dan validasi sertifikasi kompetensi."
    :breadcrumbs="[
        ['label' => 'Hubungi Kami', 'url' => route('hubungi')]
    ]"
/>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Contact Form -->
        <div class="lg:col-span-7 bg-white rounded-xl p-8 border border-[#DCE6EE] shadow-sm">
            <h2 class="font-heading font-bold text-2xl uppercase tracking-wide text-[#132B45] mb-1">
                Formulir Kontak &amp; Permohonan Program
            </h2>
            <p class="text-xs text-[#61758A] mb-6">Pesan yang Anda kirimkan akan tercatat langsung pada sistem registrasi dan audit internal FKKSM.</p>

            <form action="{{ route('leads.submit') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="source" value="Halaman Hubungi Kami FKKSM">

                <div>
                    <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Nama Lengkap &amp; Perusahaan *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Contoh: Dian Pratama (PT Manufaktur MM2100)" class="w-full px-4 py-2.5 rounded-lg border @error('full_name') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                    @error('full_name')
                        <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Email Resmi Perusahaan *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="pic@perusahaan.co.id" class="w-full px-4 py-2.5 rounded-lg border @error('email') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                        @error('email')
                            <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Telepon / WhatsApp *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0812-3456-7890" class="w-full px-4 py-2.5 rounded-lg border @error('phone') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                        @error('phone')
                            <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Kategori Kepentingan</label>
                    <select name="interest" class="w-full px-4 py-2.5 rounded-lg border border-[#DCE6EE] focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                        <option value="Pendaftaran Pelatihan K3 & Sertifikasi" {{ old('interest') == 'Pendaftaran Pelatihan K3 & Sertifikasi' ? 'selected' : '' }}>Pendaftaran Pelatihan K3 & Sertifikasi</option>
                        <option value="Keanggotaan Baru Perusahaan MM2100" {{ old('interest') == 'Keanggotaan Baru Perusahaan MM2100' ? 'selected' : '' }}>Keanggotaan Baru Perusahaan MM2100</option>
                        <option value="Konsultasi Bipartit & Regulasi Tenaga Kerja" {{ old('interest') == 'Konsultasi Bipartit & Regulasi Tenaga Kerja' ? 'selected' : '' }}>Konsultasi Bipartit & Regulasi Tenaga Kerja</option>
                        <option value="Kerja Sama Pemagangan Siswa Vokasi" {{ old('interest') == 'Kerja Sama Pemagangan Siswa Vokasi' ? 'selected' : '' }}>Kerja Sama Pemagangan Siswa Vokasi</option>
                        <option value="Lainnya" {{ old('interest') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Detail Pesan / Pertanyaan</label>
                    <textarea name="notes" rows="4" placeholder="Tuliskan kebutuhan pelatihan atau konsultasi Anda secara spesifik..." class="w-full px-4 py-2.5 rounded-lg border @error('notes') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-[#118DAB] to-[#13AEB9] hover:brightness-105 text-white font-heading font-bold text-sm uppercase tracking-wider shadow-sm transition">
                    Kirim Pesan ke Pengurus FKKSM →
                </button>
            </form>
        </div>

        <!-- Secretariat Info & Map Preview -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-[#132B45] text-white p-7 rounded-xl border border-[#0C3553] shadow-sm space-y-4">
                <span class="text-[10px] font-mono text-[#54D6D0] uppercase tracking-widest block">
                    ● KANTOR SEKRETARIAT KAWASAN
                </span>
                <h3 class="font-heading font-bold text-2xl uppercase tracking-wide leading-snug">
                    FORUM KOMUNIKASI KOMITE SEKOLAH MENENGAH &amp; HR MM2100
                </h3>
                <p class="text-xs text-[#D8EDF7] leading-relaxed">
                    Gedung Pengelola Kawasan Industri MM2100, Lantai 2, Cikarang Barat, Kabupaten Bekasi, Jawa Barat 17520.
                </p>
                <div class="pt-3 border-t border-[#176D91]/40 space-y-2 text-xs text-[#A5B4C2]">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#54D6D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Jam Operasional: Senin – Jumat, 08.00 – 17.00 WIB</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#54D6D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>Hotline HR &amp; K3: (021) 8980-FKKSM / 8980-0021</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#54D6D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Email: sekretariat@fkksm-mm2100.or.id</span>
                    </div>
                </div>
            </div>

            <!-- Google Maps Embed -->
            <div class="rounded-xl overflow-hidden border border-[#DCE6EE] shadow-sm h-64 bg-slate-200">
                <iframe src="https://maps.google.com/maps?q=Kawasan+Industri+MM2100+Cikarang+Barat&t=&z=14&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

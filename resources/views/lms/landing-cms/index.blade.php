@extends('layouts.lms')

@section('title', 'Kelola Konten Halaman Home (/) | FKKSM Admin CMS')
@section('header_eyebrow', 'CMS & Konfigurasi Halaman Utama')
@section('header_title', 'Kelola Landing Page (Route: /)')

@section('content')
@php
    $current = $sections[$activeKey];
    $c = $current['content'];
@endphp

<div class="space-y-6">
    <!-- Top Banner -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-[#0E97A8]">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>TERINTEGRASI LANGSUNG KE FrontendController::index ('/')</span>
            </div>
            <h2 class="text-lg font-bold text-slate-900 mt-1">Editor Dinamis Halaman Utama FKKSM Upskill</h2>
            <p class="text-xs text-slate-500 mt-0.5">Setiap perubahan yang disimpan otomatis membersihkan cache dan langsung tampil pada halaman depan publik.</p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 rounded-lg border border-slate-200 hover:border-[#10A9BC] text-slate-800 font-semibold text-xs inline-flex items-center gap-1.5 lms-transition">
                <span>Lihat Halaman Home</span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left Section Navigator -->
        <aside class="lg:col-span-3 bg-white rounded-xl border border-slate-200 p-3 space-y-1">
            <p class="px-3 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Section Halaman Home (/)</p>
            @foreach ($sections as $key => $sec)
                <a href="{{ route('lms.landing-cms.index', ['section' => $key]) }}" class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg text-xs lms-transition {{ $activeKey === $key ? 'bg-[#10A9BC] text-white font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="truncate">{{ $sec['title'] }}</span>
                    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ ($sec['is_active'] ?? true) ? ($activeKey === $key ? 'bg-[#54D6D0]' : 'bg-emerald-500') : 'bg-slate-300' }}" title="{{ ($sec['is_active'] ?? true) ? 'Aktif' : 'Disembunyikan' }}"></span>
                </a>
            @endforeach
        </aside>

        <!-- Right Dynamic Form Editor -->
        <div class="lg:col-span-9 bg-white rounded-xl border border-slate-200 p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <span class="font-mono text-[11px] text-[#0E97A8] uppercase font-semibold">{{ $activeKey }}</span>
                    <h3 class="text-lg font-bold text-slate-900">{{ $current['title'] }}</h3>
                </div>
                <form action="{{ route('lms.landing-cms.reset', $activeKey) }}" method="POST" data-confirm="Kembalikan konten section ini ke setelan awal?" data-confirm-title="Reset Konten Section" data-confirm-btn="Ya, Reset">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-medium text-slate-600">
                        Reset Default
                    </button>
                </form>
            </div>

            <form action="{{ route('lms.landing-cms.update', $activeKey) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
                @csrf

                <!-- Global Section Meta Controls -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Label Section (Admin)</label>
                        <input type="text" name="section_title" value="{{ $current['title'] }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-white">
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-3 pt-4 sm:pt-0">
                        <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-800">
                            <input type="checkbox" name="is_active" value="1" {{ ($current['is_active'] ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#10A9BC] rounded">
                            <span>Tampilkan Section di Halaman Home (/)</span>
                        </label>
                    </div>
                </div>

                @if ($activeKey === 'hero_slides')
                    <div id="slidesContainer" class="space-y-4">
                        @foreach (($c['slides'] ?? []) as $i => $slide)
                            <div class="p-4 rounded-xl border border-slate-200 space-y-3 bg-white relative item-row">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-[#0E97A8]">SLIDE #{{ $i + 1 }}</span>
                                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-600 hover:underline font-medium">Hapus Slide</button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Badge Teks</label>
                                        <input type="text" name="slides[{{ $i }}][badge]" value="{{ $slide['badge'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Headline Utama *</label>
                                        <input type="text" name="slides[{{ $i }}][headline]" value="{{ $slide['headline'] ?? '' }}" required class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Subjudul / Deskripsi</label>
                                    <textarea name="slides[{{ $i }}][subtitle]" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200">{{ $slide['subtitle'] ?? '' }}</textarea>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Teks Tombol Utama</label>
                                        <input type="text" name="slides[{{ $i }}][cta_label]" value="{{ $slide['cta_label'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">URL Tombol Utama</label>
                                        <input type="text" name="slides[{{ $i }}][cta_url]" value="{{ $slide['cta_url'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Tombol Sekunder</label>
                                        <input type="text" name="slides[{{ $i }}][secondary_label]" value="{{ $slide['secondary_label'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">URL Sekunder</label>
                                        <input type="text" name="slides[{{ $i }}][secondary_url]" value="{{ $slide['secondary_url'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Path Gambar Banner</label>
                                        <input type="text" name="slides[{{ $i }}][image]" value="{{ $slide['image'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Atau Unggah Gambar Baru</label>
                                        <input type="file" name="slides[{{ $i }}][image_file]" accept="image/*" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addSlideRow()" class="px-4 py-2 rounded-lg border border-dashed border-[#10A9BC] text-[#0E97A8] font-semibold hover:bg-[#E7F6F8]">+ Tambah Slide Banner</button>

                @elseif ($activeKey === 'onboarding_banner' || $activeKey === 'voucher_banner')
                    <div class="space-y-4">
                        @if ($activeKey === 'onboarding_banner')
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Headline Banner *</label>
                                <input type="text" name="headline" value="{{ $c['headline'] ?? '' }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Subjudul</label>
                                <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Teks Tombol (Tamu / Guest)</label>
                                    <input type="text" name="cta_guest" value="{{ $c['cta_guest'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Teks Tombol (Login)</label>
                                    <input type="text" name="cta_auth" value="{{ $c['cta_auth'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Judul Banner Voucher *</label>
                                <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Deskripsi</label>
                                <input type="text" name="description" value="{{ $c['description'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Teks Tombol</label>
                                <input type="text" name="cta_label" value="{{ $c['cta_label'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                            </div>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Path Ikon / Gambar</label>
                                <input type="text" name="image" value="{{ $c['image'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Unggah Gambar Baru</label>
                                <input type="file" name="image_file" accept="image/*" class="w-full px-2 py-2 rounded-lg border border-slate-200 bg-slate-50">
                            </div>
                        </div>
                    </div>

                @elseif ($activeKey === 'what_you_learn')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Section *</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Subjudul Section</label>
                            <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                    </div>
                    <div id="webinarsContainer" class="space-y-4">
                        @foreach (($c['webinars'] ?? []) as $i => $w)
                            <div class="p-4 rounded-xl border border-slate-200 space-y-3 item-row">
                                <div class="flex justify-between items-center">
                                    <span class="font-mono font-bold text-[#0E97A8]">WEBINAR #{{ $i + 1 }}</span>
                                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-600 hover:underline">Hapus</button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <label class="block font-medium text-slate-700 mb-1">Judul Webinar *</label>
                                        <input type="text" name="webinars[{{ $i }}][title]" value="{{ $w['title'] ?? '' }}" required class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Jadwal / Countdown</label>
                                        <input type="text" name="webinars[{{ $i }}][schedule]" value="{{ $w['schedule'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Kategori Label</label>
                                        <input type="text" name="webinars[{{ $i }}][category]" value="{{ $w['category'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Nama Tutor</label>
                                        <input type="text" name="webinars[{{ $i }}][tutor]" value="{{ $w['tutor'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Harga Coret</label>
                                        <input type="text" name="webinars[{{ $i }}][original_price]" value="{{ $w['original_price'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Label Harga / CTA</label>
                                        <input type="text" name="webinars[{{ $i }}][price_label]" value="{{ $w['price_label'] ?? 'GRATIS' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Badge Diskon</label>
                                        <input type="text" name="webinars[{{ $i }}][discount_badge]" value="{{ $w['discount_badge'] ?? 'DISKON 100%' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Path Thumbnail</label>
                                        <input type="text" name="webinars[{{ $i }}][image]" value="{{ $w['image'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Unggah Thumbnail Baru</label>
                                        <input type="file" name="webinars[{{ $i }}][image_file]" accept="image/*" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50">
                                    </div>
                                </div>
                                <input type="hidden" name="webinars[{{ $i }}][cta_label]" value="{{ $w['cta_label'] ?? 'Ikut Gratis' }}">
                                <label class="inline-flex items-center gap-2 font-medium text-slate-700">
                                    <input type="checkbox" name="webinars[{{ $i }}][is_live_soon]" value="1" {{ !empty($w['is_live_soon']) ? 'checked' : '' }} class="accent-[#10A9BC]">
                                    <span>Sorot badge merah (Live Segera)</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                @elseif ($activeKey === 'course_categories')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Section *</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                    </div>
                    <div id="catContainer" class="space-y-3">
                        @foreach (($c['items'] ?? []) as $i => $cat)
                            <div class="p-3.5 rounded-xl border border-slate-200 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end item-row">
                                <div class="sm:col-span-2">
                                    <label class="block font-medium text-slate-700 mb-1">Nama Kategori</label>
                                    <input type="text" name="items[{{ $i }}][name]" value="{{ $cat['name'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Filter Parameter</label>
                                    <input type="text" name="items[{{ $i }}][category_param]" value="{{ $cat['category_param'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Warna / Ikon</label>
                                    <select name="items[{{ $i }}][theme]" class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white">
                                        @foreach (['sky','indigo','emerald','amber','teal','purple','rose'] as $th)
                                            <option value="{{ $th }}" {{ ($cat['theme'] ?? 'sky') === $th ? 'selected' : '' }}>{{ ucfirst($th) }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[{{ $i }}][icon_key]" value="{{ $cat['icon_key'] ?? 'language' }}">
                                </div>
                                <button type="button" onclick="this.closest('.item-row').remove()" class="py-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 font-semibold">Hapus</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addCategoryRow()" class="px-4 py-2 rounded-lg border border-dashed border-[#10A9BC] text-[#0E97A8] font-semibold">+ Tambah Kategori</button>

                @elseif ($activeKey === 'kids_teens_programs')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Section *</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                    </div>
                    <div class="space-y-4">
                        @foreach (($c['programs'] ?? []) as $i => $prog)
                            <div class="p-4 rounded-xl border border-slate-200 space-y-3 item-row">
                                <span class="font-mono font-bold text-[#0E97A8]">PROGRAM #{{ $i + 1 }}</span>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Nama Program</label>
                                        <input type="text" name="programs[{{ $i }}][title]" value="{{ $prog['title'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Badge Mode (Online/Offline)</label>
                                        <input type="text" name="programs[{{ $i }}][mode_badge]" value="{{ $prog['mode_badge'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Meta (Usia / Lokasi)</label>
                                        <input type="text" name="programs[{{ $i }}][meta]" value="{{ $prog['meta'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Deskripsi</label>
                                    <textarea name="programs[{{ $i }}][description]" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200">{{ $prog['description'] ?? '' }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">URL Tujuan</label>
                                        <input type="text" name="programs[{{ $i }}][cta_url]" value="{{ $prog['cta_url'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Path Thumbnail</label>
                                        <input type="text" name="programs[{{ $i }}][image]" value="{{ $prog['image'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Unggah Gambar Baru</label>
                                        <input type="file" name="programs[{{ $i }}][image_file]" accept="image/*" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50">
                                    </div>
                                </div>
                                <input type="hidden" name="programs[{{ $i }}][mode_theme]" value="{{ $prog['mode_theme'] ?? 'blue' }}">
                                <input type="hidden" name="programs[{{ $i }}][cta_label]" value="{{ $prog['cta_label'] ?? 'Lihat Detail' }}">
                            </div>
                        @endforeach
                    </div>

                @elseif ($activeKey === 'bestseller_courses' || $activeKey === 'prakerja_section')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Section *</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        @if ($activeKey === 'prakerja_section')
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Judul Banner Prakerja</label>
                                <input type="text" name="banner_heading" value="{{ $c['banner_heading'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Deskripsi Banner Prakerja</label>
                                <input type="text" name="banner_description" value="{{ $c['banner_description'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                            </div>
                        @endif
                    </div>

                    <div id="courseShowcaseContainer" class="space-y-4">
                        @foreach (($c['items'] ?? []) as $i => $it)
                            <div class="p-4 rounded-xl border border-slate-200 space-y-3 item-row">
                                <div class="flex justify-between items-center">
                                    <span class="font-mono font-bold text-[#0E97A8]">KARTU KURSUS #{{ $i + 1 }}</span>
                                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-600 hover:underline">Hapus Kartu</button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <label class="block font-medium text-slate-700 mb-1">Judul Kursus *</label>
                                        <input type="text" name="items[{{ $i }}][title]" value="{{ $it['title'] ?? '' }}" required class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Kategori</label>
                                        <input type="text" name="items[{{ $i }}][category]" value="{{ $it['category'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Harga Tampil</label>
                                        <input type="text" name="items[{{ $i }}][price]" value="{{ $it['price'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Rating</label>
                                        <input type="text" name="items[{{ $i }}][rating]" value="{{ $it['rating'] ?? '4.9' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Ulasan</label>
                                        <input type="text" name="items[{{ $i }}][reviews]" value="{{ $it['reviews'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Thumbnail Path</label>
                                        <input type="text" name="items[{{ $i }}][thumbnail]" value="{{ $it['thumbnail'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                                    </div>
                                </div>

                                <!-- Skill Badges Editor for Card -->
                                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/80 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-slate-700">Skill Badges Kartu Ini:</span>
                                        <button type="button" onclick="addBadgeToCard(this, {{ $i }})" class="text-[#0E97A8] font-semibold hover:underline">+ Tambah Skill Badge</button>
                                    </div>
                                    <div class="badges-list space-y-2">
                                        @foreach (($it['badges'] ?? []) as $bi => $b)
                                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 badge-row">
                                                <img src="{{ asset('assets/fkksm/' . ($b['icon'] ?? '1765179223375_166389665.png')) }}" alt="" class="w-7 h-7 rounded-full border border-slate-200 bg-white shrink-0">
                                                <select name="items[{{ $i }}][badges][{{ $bi }}][icon]" class="px-2 py-1.5 rounded-lg border border-slate-200 bg-white w-48" onchange="this.previousElementSibling.src='{{ asset('assets/fkksm') }}/' + this.value">
                                                    @foreach ($skillBadgeCatalog as $sbOpt)
                                                        <option value="{{ $sbOpt['icon'] }}" {{ ($b['icon'] ?? '') === $sbOpt['icon'] ? 'selected' : '' }}>{{ $sbOpt['default_name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="items[{{ $i }}][badges][{{ $bi }}][name]" value="{{ $b['name'] ?? '' }}" placeholder="Nama Skill Badge..." class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white">
                                                <button type="button" onclick="this.closest('.badge-row').remove()" class="text-rose-600 font-semibold px-2">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @elseif ($activeKey === 'job_vacancies')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Section *</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                    </div>
                    <div id="jobsContainer" class="space-y-3">
                        @foreach (($c['jobs'] ?? []) as $i => $job)
                            <div class="p-4 rounded-xl border border-slate-200 grid grid-cols-1 sm:grid-cols-6 gap-3 items-end item-row">
                                <div class="sm:col-span-2">
                                    <label class="block font-medium text-slate-700 mb-1">Posisi Lowongan</label>
                                    <input type="text" name="jobs[{{ $i }}][title]" value="{{ $job['title'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Perusahaan</label>
                                    <input type="text" name="jobs[{{ $i }}][company]" value="{{ $job['company'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Tipe</label>
                                    <input type="text" name="jobs[{{ $i }}][type]" value="{{ $job['type'] ?? 'Full-Time' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Lokasi</label>
                                    <input type="text" name="jobs[{{ $i }}][location]" value="{{ $job['location'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" name="jobs[{{ $i }}][time]" value="{{ $job['time'] ?? 'Baru saja' }}" class="w-full px-2.5 py-2 rounded-lg border border-slate-200 font-mono">
                                    <button type="button" onclick="this.closest('.item-row').remove()" class="px-2.5 py-2 rounded-lg border border-rose-200 text-rose-600 font-bold">×</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addJobRow()" class="px-4 py-2 rounded-lg border border-dashed border-[#10A9BC] text-[#0E97A8] font-semibold">+ Tambah Lowongan Kerja</button>

                @elseif ($activeKey === 'b2b_corporate')
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Badge Section</label>
                            <input type="text" name="badge" value="{{ $c['badge'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Headline *</label>
                            <input type="text" name="headline" value="{{ $c['headline'] ?? '' }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">{{ $c['description'] ?? '' }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Label Tombol CTA</label>
                                <input type="text" name="cta_label" value="{{ $c['cta_label'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Telepon Sekretariat</label>
                                <input type="text" name="phone" value="{{ $c['phone'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Email Resmi</label>
                                <input type="email" name="email" value="{{ $c['email'] ?? '' }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
                            </div>
                        </div>
                    </div>

                @elseif ($activeKey === 'testimonials')
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Badge</label>
                            <input type="text" name="badge" value="{{ $c['badge'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Section</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                        </div>
                    </div>
                    <div id="testiContainer" class="space-y-3">
                        @foreach (($c['items'] ?? []) as $i => $t)
                            <div class="p-4 rounded-xl border border-slate-200 space-y-3 item-row">
                                <div class="flex justify-between items-center">
                                    <span class="font-mono font-bold text-[#0E97A8]">TESTIMONI #{{ $i + 1 }}</span>
                                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-600 hover:underline">Hapus</button>
                                </div>
                                <textarea name="items[{{ $i }}][quote]" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="Kutipan testimoni...">{{ $t['quote'] ?? '' }}</textarea>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <input type="text" name="items[{{ $i }}][name]" value="{{ $t['name'] ?? '' }}" placeholder="Nama Narasumber" class="px-3 py-2 rounded-lg border border-slate-200">
                                    <input type="text" name="items[{{ $i }}][role]" value="{{ $t['role'] ?? '' }}" placeholder="Jabatan & Perusahaan" class="sm:col-span-2 px-3 py-2 rounded-lg border border-slate-200">
                                </div>
                            </div>
                        @endforeach
                    </div>

                @elseif ($activeKey === 'faq_items')
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Badge</label>
                            <input type="text" name="badge" value="{{ $c['badge'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Section</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" value="{{ $c['subtitle'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                        </div>
                    </div>
                    <div id="faqContainer" class="space-y-3">
                        @foreach (($c['items'] ?? []) as $i => $f)
                            <div class="p-4 rounded-xl border border-slate-200 space-y-2.5 item-row">
                                <div class="flex justify-between items-center">
                                    <span class="font-mono font-bold text-[#0E97A8]">FAQ #{{ $i + 1 }}</span>
                                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-600 hover:underline">Hapus</button>
                                </div>
                                <input type="text" name="items[{{ $i }}][question]" value="{{ $f['question'] ?? '' }}" placeholder="Pertanyaan..." class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold">
                                <textarea name="items[{{ $i }}][answer]" rows="2" placeholder="Jawaban lengkap..." class="w-full px-3 py-2 rounded-lg border border-slate-200">{{ $f['answer'] ?? '' }}</textarea>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addFaqRow()" class="px-4 py-2 rounded-lg border border-dashed border-[#10A9BC] text-[#0E97A8] font-semibold">+ Tambah Pertanyaan FAQ</button>

                @elseif ($activeKey === 'seo_content')
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Judul Artikel SEO *</label>
                            <input type="text" name="heading" value="{{ $c['heading'] ?? '' }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Paragraf Pembuka</label>
                            <textarea name="paragraph_1" rows="3" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">{{ $c['paragraph_1'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Paragraf Penutup</label>
                            <textarea name="paragraph_2" rows="3" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">{{ $c['paragraph_2'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endif

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                        Simpan &amp; Terbitkan ke Halaman Home (/)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const skillCatalog = @json($skillBadgeCatalog);
    const assetBase = "{{ asset('assets/fkksm') }}";

    function addSlideRow() {
        const c = document.getElementById('slidesContainer');
        const idx = c.querySelectorAll('.item-row').length + Math.floor(Math.random() * 1000);
        c.insertAdjacentHTML('beforeend', `
            <div class="p-4 rounded-xl border border-slate-200 space-y-3 bg-white item-row">
                <div class="flex items-center justify-between">
                    <span class="font-mono font-bold text-[#0E97A8]">SLIDE BARU</span>
                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-600 hover:underline font-medium">Hapus Slide</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" name="slides[${idx}][badge]" placeholder="Badge (misal: PROGRAM BARU)" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                    <input type="text" name="slides[${idx}][headline]" required placeholder="Headline Utama" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                </div>
                <textarea name="slides[${idx}][subtitle]" rows="2" placeholder="Deskripsi banner..." class="w-full px-3 py-2 rounded-lg border border-slate-200"></textarea>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="slides[${idx}][cta_label]" value="Jelajahi Kelas" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                    <input type="text" name="slides[${idx}][cta_url]" value="#katalog-kursus" class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                    <input type="hidden" name="slides[${idx}][image]" value="fkksm/assets/images/activity-01.jpg">
                </div>
            </div>
        `);
    }

    function addCategoryRow() {
        const c = document.getElementById('catContainer');
        const idx = c.querySelectorAll('.item-row').length + Math.floor(Math.random() * 1000);
        c.insertAdjacentHTML('beforeend', `
            <div class="p-3.5 rounded-xl border border-slate-200 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end item-row">
                <div class="sm:col-span-2">
                    <label class="block font-medium text-slate-700 mb-1">Nama Kategori</label>
                    <input type="text" name="items[${idx}][name]" required class="w-full px-3 py-2 rounded-lg border border-slate-200">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Filter Parameter</label>
                    <input type="text" name="items[${idx}][category_param]" required class="w-full px-3 py-2 rounded-lg border border-slate-200">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tema Warna</label>
                    <select name="items[${idx}][theme]" class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white">
                        <option value="sky">Sky</option><option value="indigo">Indigo</option><option value="emerald">Emerald</option><option value="amber">Amber</option><option value="teal">Teal</option><option value="purple">Purple</option><option value="rose">Rose</option>
                    </select>
                    <input type="hidden" name="items[${idx}][icon_key]" value="tech">
                </div>
                <button type="button" onclick="this.closest('.item-row').remove()" class="py-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 font-semibold">Hapus</button>
            </div>
        `);
    }

    function addBadgeToCard(btn, cardIdx) {
        const list = btn.closest('.p-3').querySelector('.badges-list');
        const bIdx = list.querySelectorAll('.badge-row').length + Math.floor(Math.random() * 1000);
        const options = skillCatalog.map(s => `<option value="${s.icon}">${s.default_name}</option>`).join('');
        const first = skillCatalog[0] || { icon: '1765179223375_166389665.png', default_name: 'Skill Baru' };
        list.insertAdjacentHTML('beforeend', `
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 badge-row">
                <img src="${assetBase}/${first.icon}" alt="" class="w-7 h-7 rounded-full border border-slate-200 bg-white shrink-0">
                <select name="items[${cardIdx}][badges][${bIdx}][icon]" class="px-2 py-1.5 rounded-lg border border-slate-200 bg-white w-48" onchange="this.previousElementSibling.src='${assetBase}/' + this.value">
                    ${options}
                </select>
                <input type="text" name="items[${cardIdx}][badges][${bIdx}][name]" value="${first.default_name}" placeholder="Nama Skill Badge..." class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white">
                <button type="button" onclick="this.closest('.badge-row').remove()" class="text-rose-600 font-semibold px-2">×</button>
            </div>
        `);
    }

    function addJobRow() {
        const c = document.getElementById('jobsContainer');
        const idx = c.querySelectorAll('.item-row').length + Math.floor(Math.random() * 1000);
        c.insertAdjacentHTML('beforeend', `
            <div class="p-4 rounded-xl border border-slate-200 grid grid-cols-1 sm:grid-cols-6 gap-3 items-end item-row">
                <div class="sm:col-span-2"><label class="block font-medium text-slate-700 mb-1">Posisi</label><input type="text" name="jobs[${idx}][title]" required class="w-full px-3 py-2 rounded-lg border border-slate-200"></div>
                <div><label class="block font-medium text-slate-700 mb-1">Perusahaan</label><input type="text" name="jobs[${idx}][company]" required class="w-full px-3 py-2 rounded-lg border border-slate-200"></div>
                <div><label class="block font-medium text-slate-700 mb-1">Tipe</label><input type="text" name="jobs[${idx}][type]" value="Full-Time" class="w-full px-3 py-2 rounded-lg border border-slate-200"></div>
                <div><label class="block font-medium text-slate-700 mb-1">Lokasi</label><input type="text" name="jobs[${idx}][location]" value="Bekasi, Indonesia" class="w-full px-3 py-2 rounded-lg border border-slate-200"></div>
                <div class="flex gap-2"><input type="text" name="jobs[${idx}][time]" value="Baru saja" class="w-full px-2.5 py-2 rounded-lg border border-slate-200 font-mono"><button type="button" onclick="this.closest('.item-row').remove()" class="px-2.5 py-2 rounded-lg border border-rose-200 text-rose-600 font-bold">×</button></div>
            </div>
        `);
    }

    function addFaqRow() {
        const c = document.getElementById('faqContainer');
        const idx = c.querySelectorAll('.item-row').length + Math.floor(Math.random() * 1000);
        c.insertAdjacentHTML('beforeend', `
            <div class="p-4 rounded-xl border border-slate-200 space-y-2.5 item-row">
                <div class="flex justify-between items-center">
                    <span class="font-mono font-bold text-[#0E97A8]">FAQ BARU</span>
                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-600 hover:underline">Hapus</button>
                </div>
                <input type="text" name="items[${idx}][question]" required placeholder="Pertanyaan..." class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold">
                <textarea name="items[${idx}][answer]" rows="2" placeholder="Jawaban lengkap..." class="w-full px-3 py-2 rounded-lg border border-slate-200"></textarea>
            </div>
        `);
    }
</script>
@endpush
@endsection


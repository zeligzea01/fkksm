@extends('layouts.lms')

@section('title', 'Modul & Katalog Pelatihan | FKKSM LMS')
@section('header_eyebrow', '2. Course & Learning Management')
@section('header_title', 'Modul, Kursus & Program Pelatihan')

@section('content')
<div class="space-y-6">
    <!-- Top Quick Voucher Redeem Strip matching Figma Layar 2D (Html -> Body.svg) -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-[#E7F6F8] border border-[#C7E8EE] text-[#0E97A8] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sm text-slate-900">Tukarkan kode belajarmu di sini.</p>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan kode voucher atau token perusahaan untuk membuka akses modul pelatihan resmi.</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 w-full md:w-auto shrink-0">
            <button type="button" onclick="document.getElementById('quickRedeemModal').classList.remove('hidden')" class="px-4 py-2 rounded-lg border border-slate-200 hover:border-[#10A9BC] text-slate-800 font-semibold text-xs inline-flex items-center gap-2 lms-transition">
                <svg class="w-4 h-4 text-[#0E97A8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Tukar</span>
            </button>
            <a href="{{ route('lms.vouchers.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs inline-flex items-center gap-2 lms-transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                <span>Cara tukar kode</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col lg:flex-row gap-3 items-center justify-between">
        <form action="{{ route('lms.courses.index') }}" method="GET" class="flex-1 flex flex-col sm:flex-row gap-3 w-full">
            <div class="flex-1 relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama modul, topik K3, atau kategori..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC] text-xs bg-slate-50 focus:bg-white lms-transition">
            </div>

            <select name="category" class="px-3.5 py-2 rounded-lg border border-slate-200 text-xs bg-white text-slate-700 focus:outline-none focus:border-[#10A9BC]">
                <option value="all">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="access_type" class="px-3.5 py-2 rounded-lg border border-slate-200 text-xs bg-white text-slate-700 focus:outline-none focus:border-[#10A9BC]">
                <option value="all">Semua Akses</option>
                <option value="free" {{ $accessType === 'free' ? 'selected' : '' }}>Gratis / Subsidi</option>
                <option value="paid" {{ $accessType === 'paid' ? 'selected' : '' }}>Berbayar</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition whitespace-nowrap">
                Filter Kelas
            </button>
        </form>

        @if (auth()->user()->isInstructor())
            <a href="{{ route('lms.courses.create') }}" class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shrink-0 lms-transition whitespace-nowrap">
                + Tambah Kursus
            </a>
        @endif
    </div>

    <!-- Courses Horizontal Cards List matching Figma Layar 2D (Html -> Body.svg) -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 space-y-4">
        @forelse ($courses as $course)
            @php
                $isEnrolled = isset($enrollmentsByCourse[$course->id]);
                $totalMat = $course->materials_count ?? $course->modules->flatMap->materials->count();
                $completedMat = 0;
                foreach ($course->modules as $mod) {
                    foreach ($mod->materials as $m) {
                        if (isset($completedMaterialIds[$m->id])) {
                            $completedMat++;
                        }
                    }
                }
                $pct = $isEnrolled && $totalMat > 0 ? (int) round(($completedMat / $totalMat) * 100) : 0;
                $minutes = (int) round(((float) ($course->duration_hours ?? 2)) * 60);
                $statusLabel = $pct >= 100 ? 'SELESAI' : ($isEnrolled ? 'ONGOING' : 'BELUM DIMULAI');
            @endphp

            <div class="p-4 rounded-xl border border-slate-200/90 hover:border-[#10A9BC]/60 lms-transition flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1 min-w-0">
                    <!-- Thumbnail with Duration Badge matching Figma -->
                    <div class="relative w-full sm:w-44 aspect-[16/10] rounded-lg overflow-hidden bg-slate-900 shrink-0">
                        <img src="{{ $course->thumbnail_url }}" onerror="this.onerror=null;this.src='{{ asset('assets/fkksm/_1795405504399_1756706515.png') }}'" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-0.5 rounded bg-slate-900/80 text-white text-[10px] font-mono font-semibold uppercase tabular-nums">
                                {{ $minutes }} MENIT
                            </span>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1 space-y-2">
                        <div class="flex flex-wrap items-center gap-2 text-[11px] font-mono">
                            <span class="font-bold uppercase tracking-wider {{ $statusLabel === 'SELESAI' ? 'text-emerald-600' : ($statusLabel === 'ONGOING' ? 'text-[#0E97A8]' : 'text-slate-500') }}">
                                {{ $statusLabel }}
                            </span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-semibold text-slate-700">{{ $course->category ?? 'Umum' }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-slate-500">{{ $course->formatted_price }}</span>
                        </div>

                        <h3 class="font-semibold text-base text-slate-900 leading-snug">
                            {{ $course->title }}
                        </h3>

                        <!-- Skill Badges inline -->
                        @if (is_array($course->skill_badges) && count($course->skill_badges) > 0)
                            <div class="flex flex-wrap items-center gap-1.5">
                                @foreach ($course->skill_badges as $b)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#E7F6F8] border border-[#C7E8EE] text-[10px] font-medium text-[#0E97A8]">
                                        <img src="{{ asset('assets/fkksm/' . ($b['icon'] ?? '1765179223375_166389665.png')) }}" alt="{{ $b['name'] ?? '' }}" class="w-3.5 h-3.5 rounded-full object-contain">
                                        <span>{{ $b['name'] ?? '' }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-mono tabular-nums pt-0.5">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                <span>Modul {{ $course->modules_count }} &bull; {{ number_format((float)$course->duration_hours, 1) }} jam</span>
                            </span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>{{ $totalMat }} Materi &bull; {{ $course->assessments_count }} Kuis</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Progress & Action Button -->
                <div class="flex items-center justify-between md:justify-end gap-5 w-full md:w-auto pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 shrink-0">
                    <div class="text-left md:text-right">
                        <span class="font-mono tabular-nums font-bold text-xs text-slate-800 block">{{ $pct }}% selesai</span>
                        <div class="w-24 bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden">
                            <div class="bg-[#10A9BC] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if (auth()->user()->isInstructor())
                            <a href="{{ route('lms.courses.edit', $course) }}" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-medium text-slate-700">
                                Edit
                            </a>
                        @endif
                        <a href="{{ route('lms.courses.show', $course) }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:border-[#10A9BC] hover:bg-[#E7F6F8]/40 text-slate-900 font-semibold text-xs inline-flex items-center gap-1.5 lms-transition">
                            <span>{{ $isEnrolled ? 'Lanjutkan' : 'Mulai' }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-xs text-slate-400">
                Tidak ditemukan modul atau kursus yang cocok dengan kriteria pencarian.
            </div>
        @endforelse
    </div>

    @if ($courses->hasPages())
        <div class="bg-white rounded-xl border border-slate-200 p-3">
            {{ $courses->links() }}
        </div>
    @endif
</div>

<!-- Quick Voucher Modal -->
<div id="quickRedeemModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-xl border border-slate-200 max-w-md w-full p-6 shadow-xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-base text-slate-900">Tukar Kode Belajar</h3>
            <button type="button" onclick="document.getElementById('quickRedeemModal').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('lms.vouchers.redeem') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1.5">Kode Voucher</label>
                <input type="text" name="code" required placeholder="Contoh: FKKSM2026" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono text-sm uppercase focus:outline-none focus:border-[#10A9BC]">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('quickRedeemModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-semibold">Tukarkan Kode</button>
            </div>
        </form>
    </div>
</div>
@endsection

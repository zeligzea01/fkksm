@extends('layouts.lms')

@section('title', 'Sertifikat Anda | FKKSM LMS')
@section('header_eyebrow', 'Sertifikasi Kompetensi')
@section('header_title', 'Sertifikat Anda')

@section('content')
<div class="space-y-6">
    <!-- Hero Banner matching Figma Sertifikat Page (page_1.png) -->
    <div class="bg-lms-hero rounded-xl p-6 sm:p-8 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative overflow-hidden">
        <div class="space-y-2.5 max-w-xl relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-[11px] font-medium">
                <svg class="w-3.5 h-3.5 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Sertifikat Saya</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">Sertifikat Anda</h2>
            <p class="text-xs sm:text-sm text-white/85 leading-relaxed">
                Berikut adalah sertifikat yang telah Anda peroleh setelah menyelesaikan modul, kuis, dan memenuhi syarat kelulusan resmi FKKSM MM2100.
            </p>
        </div>

        <!-- Vector Certificate Illustration matching Figma -->
        <div class="hidden sm:flex items-center justify-center shrink-0">
            <svg class="w-36 h-24 opacity-95" viewBox="0 0 160 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10" y="10" width="136" height="90" rx="10" fill="white" fill-opacity="0.92"/>
                <rect x="26" y="28" width="44" height="7" rx="3.5" fill="#54D6D0"/>
                <rect x="26" y="44" width="76" height="5" rx="2.5" fill="#CBD5E1"/>
                <rect x="26" y="55" width="60" height="5" rx="2.5" fill="#E2E8F0"/>
                <circle cx="118" cy="64" r="14" fill="#0C3553"/>
                <path d="M113 76V92L118 88L123 92V76" fill="#0C3553"/>
                <path d="M118 57L120 61.5L125 62L121 65L122.2 70L118 67.5L113.8 70L115 65L111 62L116 61.5L118 57Z" fill="white"/>
            </svg>
        </div>
    </div>

    <!-- Header & Search Filter Bar matching Figma -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-base text-slate-900">Daftar Sertifikat</h3>
            <p class="text-xs text-slate-500 font-mono tabular-nums mt-0.5">
                Total sertifikat yang diperoleh: {{ $certificates->total() }}
            </p>
        </div>

        <form action="{{ route('lms.certificates.index') }}" method="GET" class="flex items-center gap-2.5 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari sertifikat..." class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:border-[#10A9BC]">
            </div>
            <select name="sort" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:outline-none focus:border-[#10A9BC]">
                <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ ($sort ?? '') === 'oldest' ? 'selected' : '' }}>Terlama</option>
            </select>
        </form>
    </div>

    <!-- Responsive Certificate Cards Grid (3-col Desktop, 4-col 2K+) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
        @forelse ($certificates as $cert)
            <article class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col justify-between gap-4 hover:border-[#10A9BC]/60 lms-transition">
                <div class="space-y-3.5">
                    <!-- Top Row: Mini Certificate Thumbnail + Course Title & Badge -->
                    <div class="flex items-start gap-3.5">
                        <!-- Mini Certificate Preview Thumb -->
                        <div class="w-24 h-16 rounded-lg border border-slate-200 bg-slate-50 p-1.5 flex flex-col justify-between shrink-0 relative overflow-hidden">
                            <div class="flex items-center justify-between">
                                <img src="{{ asset('fkksm/logo-transparent.png') }}" alt="FKKSM" class="h-3.5 w-auto object-contain">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#54D6D0]"></span>
                            </div>
                            <div class="text-center my-auto">
                                <span class="block text-[6px] font-bold text-slate-800 tracking-tight leading-none">SERTIFIKAT KELULUSAN</span>
                                <span class="block text-[5px] text-slate-400 mt-0.5 truncate">{{ $cert->user->name }}</span>
                            </div>
                            <div class="w-5 h-1 bg-[#10A9BC] rounded-full mx-auto"></div>
                        </div>

                        <div class="min-w-0 flex-1 space-y-1.5">
                            <h4 class="font-semibold text-sm text-slate-900 leading-snug line-clamp-2">
                                {{ $cert->course->title }}
                            </h4>
                            @if ($cert->is_revoked)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[11px] font-medium">
                                    Dicabut
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Selesai</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata Rows -->
                    <div class="space-y-1.5 text-xs text-slate-600 pt-1">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Diterbitkan : <strong class="font-medium text-slate-800">{{ $cert->issued_at->format('d F Y') }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                            <span class="truncate">No. Sertifikat : <strong class="font-mono text-slate-900">{{ $cert->certificate_no }}</strong></span>
                        </div>
                        @if (auth()->user()->isAdmin())
                            <div class="text-[11px] text-slate-500 pl-5">
                                Penerima: <strong class="text-slate-700">{{ $cert->user->name }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Row: Unduh PDF | Lihat | Bagikan -->
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <a href="{{ route('lms.certificates.show', ['certificate' => $cert->id, 'print' => 1]) }}" class="px-2.5 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold flex items-center justify-center gap-1.5 lms-transition">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh PDF</span>
                        </a>
                        <a href="{{ route('lms.certificates.show', $cert) }}" class="px-2.5 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium flex items-center justify-center gap-1.5 lms-transition">
                            <svg class="w-3.5 h-3.5 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Lihat</span>
                        </a>
                        <button type="button" onclick="copyCertLink('{{ route('certificates.verify', ['code' => $cert->certificate_no]) }}', this)" class="px-2.5 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium flex items-center justify-center gap-1.5 lms-transition">
                            <svg class="w-3.5 h-3.5 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            <span>Bagikan</span>
                        </button>
                    </div>

                    @if (auth()->user()->isAdmin())
                        <form action="{{ route('lms.certificates.toggle-revoke', $cert) }}" method="POST" data-confirm="Ubah status keabsahan sertifikat ini?" data-confirm-title="Konfirmasi Status Sertifikat" data-confirm-btn="Ya, Ubah">
                            @csrf
                            <button type="submit" class="w-full py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-[11px] font-medium {{ $cert->is_revoked ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $cert->is_revoked ? 'Pulihkan Sertifikat (Admin)' : 'Cabut Keabsahan (Admin)' }}
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="col-span-full bg-white rounded-xl border border-slate-200 p-12 text-center space-y-2">
                <p class="text-sm font-semibold text-slate-800">Belum ada sertifikat diterbitkan</p>
                <p class="text-xs text-slate-500">Selesaikan seluruh materi di kursus yang Anda ikuti untuk menerbitkan sertifikat resmi secara otomatis.</p>
                <div class="pt-2">
                    <a href="{{ route('lms.courses.index') }}" class="inline-flex px-4 py-2 rounded-lg bg-[#10A9BC] text-white text-xs font-semibold">Jelajahi Modul</a>
                </div>
            </div>
        @endforelse
    </div>

    @if ($certificates->hasPages())
        <div class="bg-white rounded-xl border border-slate-200 p-3">
            {{ $certificates->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function copyCertLink(url, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url);
        }
        window.fkksmModal({
            icon: 'success',
            title: 'Tautan Sertifikat Disalin',
            text: 'Tautan verifikasi publik berhasil disalin ke clipboard Anda.',
            confirmButtonText: 'Tutup'
        });
    }
</script>
@endpush
@endsection

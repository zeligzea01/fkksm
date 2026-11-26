@extends('layouts.lms', ['title' => $course->title . ' — Detail Modul FKKSM'])

@section('content')
@php
    $orderedMats = $course->modules->flatMap->materials->values();
    $totalMaterials = $orderedMats->count();
    $completedCount = $orderedMats->whereIn('id', $completedMaterialIds)->count();
    $finalAssessment = $course->assessments->first();
    $totalSteps = $totalMaterials + ($finalAssessment ? 1 : 0);
    $unlockedIds = $unlockedMaterialIds ?? ($orderedMats->isNotEmpty() ? [$orderedMats->first()->id] : []);
    $isFinalUnlocked = $finalAssessmentUnlocked ?? ($totalMaterials === 0 || $completedCount >= $totalMaterials);
    $progressPercent = $totalMaterials > 0 ? (int) round(($completedCount / $totalMaterials) * 100) : 0;
@endphp

<div class="mb-5 flex items-center justify-between gap-4">
    <a href="{{ route('lms.courses.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#757575] hover:text-[#10A9BC] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Katalog Kursus
    </a>
    @if(auth()->user()->isAdmin() || (auth()->user()->isInstructor() && $course->instructor_id === auth()->id()))
        <div class="flex items-center gap-2">
            <a href="{{ route('lms.materials.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-[#333333] transition-colors">
                Kelola Modul & Materi
            </a>
            <a href="{{ route('lms.courses.edit', $course) }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-[#333333] transition-colors">
                Edit Informasi Kursus
            </a>
        </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 2xl:gap-8 items-start">
    {{-- Left 8/9 Cols: Course Banner, Alur 4 Tahap & Syllabus Accordion --}}
    <div class="lg:col-span-8 2xl:col-span-9 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="relative h-60 sm:h-72 xl:h-80 2xl:h-96 bg-slate-900 overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover opacity-80">
                @else
                    <img src="{{ asset('fkksm/assets/images/activity-02.jpg') }}" alt="{{ $course->title }}" class="w-full h-full object-cover opacity-70">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent"></div>

                <div class="absolute top-4 left-4 flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-[#10A9BC] text-white text-xs font-bold shadow-sm">
                        {{ $course->category ?? 'Industri MM2100' }}
                    </span>
                    <span class="px-3 py-1 rounded-full bg-white/95 text-[#333333] text-xs font-extrabold shadow-sm">
                        4 Tahap Berurutan: Video → Materi → Latihan Soal → Latihan Akhir
                    </span>
                </div>

                <div class="absolute bottom-5 left-6 right-6 text-white">
                    <h1 class="text-xl sm:text-2xl font-extrabold leading-snug mb-2">{{ $course->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-200 font-semibold">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#FFC107]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <strong class="text-white">{{ number_format((float)($course->rating ?: 4.9), 1) }}</strong>
                            ({{ number_format((int)($course->reviews_count ?: 120), 0, ',', '.') }} Ulasan)
                        </span>
                        <span>•</span>
                        <span>Durasi: {{ $course->duration_hours ?? '2.0' }} Jam</span>
                        <span>•</span>
                        <span>Instruktur: {{ $course->instructor?->name ?? 'Praktisi Industri MM2100' }}</span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                {{-- 4-Stage Sequential Pipeline Banner --}}
                <div class="mb-6 p-4 rounded-xl bg-[#F7F9FB] border border-[#E5E9F0]">
                    <div class="text-xs font-extrabold uppercase tracking-wider text-[#0C3553] mb-3">
                        Metode Belajar Berurutan (Wajib Selesai Satu per Satu)
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        <div class="p-3 rounded-lg bg-white border border-slate-200/80 flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-[#E7F6F8] text-[#0C3553] text-xs font-extrabold flex items-center justify-center shrink-0">1</span>
                            <div>
                                <div class="text-xs font-extrabold text-[#2A2A2E]">Video</div>
                                <div class="text-[10px] text-[#7E7E81]">Storage Stream</div>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-slate-200/80 flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-[#E7F6F8] text-[#0C3553] text-xs font-extrabold flex items-center justify-center shrink-0">2</span>
                            <div>
                                <div class="text-xs font-extrabold text-[#2A2A2E]">Materi</div>
                                <div class="text-[10px] text-[#7E7E81]">E-Book & SOP</div>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-slate-200/80 flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-[#E7F6F8] text-[#0C3553] text-xs font-extrabold flex items-center justify-center shrink-0">3</span>
                            <div>
                                <div class="text-xs font-extrabold text-[#2A2A2E]">Latihan Soal</div>
                                <div class="text-[10px] text-[#7E7E81]">Kuis Modul (≥70%)</div>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-slate-200/80 flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-[#E7F6F8] text-[#0C3553] text-xs font-extrabold flex items-center justify-center shrink-0">4</span>
                            <div>
                                <div class="text-xs font-extrabold text-[#2A2A2E]">Latihan Akhir</div>
                                <div class="text-[10px] text-[#7E7E81]">Ujian & Sertifikat</div>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-base font-extrabold text-[#333333] mb-2">Deskripsi Program Pelatihan</h2>
                <div class="text-sm text-[#555555] leading-relaxed whitespace-pre-line">
                    {{ $course->description ?: $course->summary }}
                </div>

                @if(!empty($course->skill_badges))
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <div class="text-xs font-extrabold text-[#757575] uppercase tracking-wider mb-3">Kompetensi yang Dipelajari</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($course->skill_badges as $badge)
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#E7F6F8] border border-[#10A9BC]/20">
                                    @if(!empty($badge['icon']))
                                        <img src="{{ asset('assets/fkksm/' . $badge['icon']) }}" alt="" class="w-4 h-4 object-contain">
                                    @endif
                                    <span class="text-xs font-bold text-[#0c8a9a]">{{ $badge['name'] ?? 'Skill Industri' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Syllabus Modules Accordion with Live Sequential Lock & Completion Status --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-extrabold text-[#333333]">Silabus & Tahapan Modul Pembelajaran</h2>
                    <p class="text-xs text-[#757575] mt-0.5">Selesaikan setiap tahap secara berurutan (Video → Materi → Latihan Soal → Latihan Akhir)</p>
                </div>
                <span class="text-xs font-bold text-[#757575]">{{ $course->modules->count() }} Modul • {{ $totalSteps }} Tahap</span>
            </div>

            @if($course->modules->isEmpty())
                <div class="p-8 text-center text-xs text-[#757575] bg-slate-50 rounded-xl">
                    Silabus modul sedang disusun oleh instruktur.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($course->modules as $index => $module)
                        <div class="rounded-xl border border-slate-200/90 overflow-hidden">
                            <div class="px-4 py-3 bg-[#F7F9FB] border-b border-slate-200/70 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-lg bg-[#0C3553] text-white text-xs font-extrabold flex items-center justify-center">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h3 class="text-xs font-extrabold uppercase tracking-wide text-[#2A2A2E]">
                                            PEMBELAJARAN {{ $index + 1 }} - {{ $module->title }}
                                        </h3>
                                        @if($module->summary)
                                            <p class="text-[11px] text-[#757575]">{{ $module->summary }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-[#0C3553] bg-[#E7F6F8] px-2.5 py-1 rounded-md">
                                    {{ $module->materials->count() }} Tahap
                                </span>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @foreach($module->materials as $mat)
                                    @php
                                        $done = in_array($mat->id, $completedMaterialIds, true);
                                        $unlocked = in_array($mat->id, $unlockedIds, true);
                                    @endphp
                                    <div class="px-4 py-3 flex items-center justify-between gap-3 text-xs {{ !$unlocked ? 'bg-slate-50/70 opacity-80' : 'bg-white' }}">
                                        <div class="flex items-center gap-3 min-w-0">
                                            @if($done)
                                                <span class="w-6 h-6 rounded-full bg-[#2ECC71] text-white flex items-center justify-center shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                            @elseif($unlocked)
                                                <span class="w-6 h-6 rounded-full bg-[#E7F6F8] text-[#10A9BC] flex items-center justify-center shrink-0">
                                                    @if($mat->content_type === 'video')
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                                    @elseif(in_array($mat->content_type, ['practice', 'quiz'], true))
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="w-6 h-6 rounded-full bg-slate-100 text-[#BCBCBD] flex items-center justify-center shrink-0">
                                                    <svg viewBox="0 0 32 40" fill="none" width="13" height="13">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82456 13.3929V9.55357C9.82456 7.94643 10.4172 6.57738 11.6023 5.44643C12.8499 4.25595 14.3158 3.66071 16 3.66071C17.6842 3.66071 19.1189 4.25595 20.3041 5.44643C21.5517 6.57738 22.1754 7.94643 22.1754 9.55357V13.3929H9.82456ZM30.7836 14.5536C29.9727 13.7798 29.037 13.3929 27.9766 13.3929H26.0117V9.55357C26.0117 6.93452 25.0136 4.70238 23.0175 2.85714C21.0838 0.952381 18.7446 0 16 0C13.2554 0 10.885 0.952381 8.88889 2.85714C6.95517 4.70238 5.9883 6.93452 5.9883 9.55357V13.3929H4.02339C2.96296 13.3929 2.02729 13.7798 1.21637 14.5536C0.405458 15.2679 0 16.131 0 17.1429V36.25C0 37.2619 0.405458 38.1548 1.21637 38.9286C2.02729 39.6429 2.96296 40 4.02339 40H27.9766C29.037 40 29.9727 39.6429 30.7836 38.9286C31.5945 38.1548 32 37.2619 32 36.25V17.1429C32 16.131 31.5945 15.2679 30.7836 14.5536Z" fill="#BCBCBD"></path>
                                                    </svg>
                                                </span>
                                            @endif

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded bg-[#E7F6F8] text-[#0C3553] text-[10px] font-extrabold uppercase">
                                                        {{ $mat->sidebar_type_label }}
                                                    </span>
                                                    <span class="font-bold text-[#2A2A2E] truncate">{{ $mat->title }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="shrink-0">
                                            @if($isEnrolled && $unlocked)
                                                <a href="{{ route('lms.classroom.show', ['course' => $course->id, 'material' => $mat->id]) }}"
                                                   class="inline-flex items-center gap-1 px-3 py-1 rounded-lg {{ $done ? 'bg-emerald-50 text-emerald-700' : 'bg-[#10A9BC] text-white' }} text-[11px] font-extrabold">
                                                    {{ $done ? 'Selesai' : 'Buka Tahap' }}
                                                </a>
                                            @elseif(!$unlocked)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-100 text-[#7E7E81] text-[10px] font-bold">
                                                    Terkunci
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Final Stage Card: Latihan Akhir --}}
                    @if($finalAssessment)
                        <div class="rounded-xl border {{ $isFinalUnlocked ? 'border-[#10A9BC]/40 bg-[#E7F6F8]/40' : 'border-slate-200 bg-slate-50/80' }} px-4 py-3.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                @if($isFinalUnlocked)
                                    <span class="w-7 h-7 rounded-lg bg-[#10A9BC] text-white flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                @else
                                    <span class="w-7 h-7 rounded-lg bg-slate-200 text-[#BCBCBD] flex items-center justify-center shrink-0">
                                        <svg viewBox="0 0 32 40" fill="none" width="14" height="14">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82456 13.3929V9.55357C9.82456 7.94643 10.4172 6.57738 11.6023 5.44643C12.8499 4.25595 14.3158 3.66071 16 3.66071C17.6842 3.66071 19.1189 4.25595 20.3041 5.44643C21.5517 6.57738 22.1754 7.94643 22.1754 9.55357V13.3929H9.82456ZM30.7836 14.5536C29.9727 13.7798 29.037 13.3929 27.9766 13.3929H26.0117V9.55357C26.0117 6.93452 25.0136 4.70238 23.0175 2.85714C21.0838 0.952381 18.7446 0 16 0C13.2554 0 10.885 0.952381 8.88889 2.85714C6.95517 4.70238 5.9883 6.93452 5.9883 9.55357V13.3929H4.02339C2.96296 13.3929 2.02729 13.7798 1.21637 14.5536C0.405458 15.2679 0 16.131 0 17.1429V36.25C0 37.2619 0.405458 38.1548 1.21637 38.9286C2.02729 39.6429 2.96296 40 4.02339 40H27.9766C29.037 40 29.9727 39.6429 30.7836 38.9286C31.5945 38.1548 32 37.2619 32 36.25V17.1429C32 16.131 31.5945 15.2679 30.7836 14.5536Z" fill="#BCBCBD"></path>
                                        </svg>
                                    </span>
                                @endif
                                <div>
                                    <div class="text-[10px] font-extrabold uppercase tracking-wider text-[#0C3553]">TAHAP AKHIR • PENILAIAN AKHIR</div>
                                    <div class="text-xs font-extrabold text-[#2A2A2E]">{{ $finalAssessment->title }}</div>
                                </div>
                            </div>

                            @if($isEnrolled && $isFinalUnlocked)
                                <a href="{{ route('lms.classroom.show', ['course' => $course->id, 'step' => 'final']) }}"
                                   class="px-3 py-1.5 rounded-lg bg-[#10A9BC] text-white text-[11px] font-extrabold">
                                    Kerjakan Latihan Akhir →
                                </a>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-slate-200/70 text-[#7E7E81] text-[10px] font-bold">
                                    Terkunci (Selesaikan Modul)
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Right 4/3 Cols: Sticky Enrollment & Progress Action Card --}}
    <div class="lg:col-span-4 2xl:col-span-3 lg:sticky lg:top-6 space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="mb-4 pb-4 border-b border-slate-100">
                <div class="text-xs font-bold text-[#757575] mb-1">Biaya Investasi Pelatihan</div>
                @if($course->access_type === 'free' || (float)$course->price <= 0)
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-[#00A859]">GRATIS</span>
                        <span class="text-xs text-slate-400 line-through">Rp {{ number_format((float)($course->original_price ?: 500000), 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-[#333333]">Rp {{ number_format((float)$course->price, 0, ',', '.') }}</span>
                        @if($course->original_price)
                            <span class="text-xs text-slate-400 line-through">Rp {{ number_format((float)$course->original_price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                @endif
            </div>

            @if($isEnrolled)
                <div class="mb-4">
                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                        <span class="text-[#757575]">Progres Modul Anda</span>
                        <span class="text-[#10A9BC]">{{ $progressPercent }}% ({{ $completedCount }}/{{ $totalMaterials }})</span>
                    </div>
                    <div class="w-full h-2 bg-[#DCE6EE] rounded-full overflow-hidden">
                        <div class="h-full bg-[#0C3553] rounded-full" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>

                <a href="{{ route('lms.classroom.show', $course) }}" class="w-full py-3 px-4 rounded-xl bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-extrabold flex items-center justify-center gap-2 transition-colors shadow-sm">
                    {{ $progressPercent >= 100 ? 'Buka Ruang Belajar & Latihan Akhir' : 'Lanjutkan Belajar Berurutan' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            @else
                <form method="POST" action="{{ route('lms.courses.enroll', $course) }}">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#F46321] hover:bg-[#d95416] text-white text-xs font-extrabold flex items-center justify-center gap-2 transition-colors shadow-sm cursor-pointer">
                        Daftar & Mulai Tahap 1 (Video)
                    </button>
                </form>
            @endif

            <ul class="mt-5 pt-5 border-t border-slate-100 space-y-2.5 text-xs text-[#555555]">
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-[#10A9BC] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Video ringan tersimpan di Storage FKKSM
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-[#10A9BC] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Alur terstruktur: Video → Materi → Latihan Soal → Latihan Akhir
                </li>
                <li class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-[#10A9BC] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Sertifikat Kompetensi Resmi FKKSM MM2100
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

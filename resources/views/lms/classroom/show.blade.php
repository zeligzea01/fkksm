@extends('layouts.lms')

@section('title', 'Ruang Kelas: ' . $course->title)
@section('page_title', $course->title)
@section('page_subtitle', 'Progres: ' . $progressPercent . '% (' . $completedCount . ' dari ' . $totalMaterials . ' materi selesai)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- Main Lesson Viewer -->
    <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        @if ($progressPercent >= 100 && $certificate)
            <!-- Completion Certificate Banner -->
            <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
                <div class="flex items-center gap-4">
                    <span class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </span>
                    <div>
                        <h3 class="font-extrabold text-base">Selamat! Kursus Telah Selesai</h3>
                        <p class="text-xs text-emerald-100">Sertifikat Anda telah resmi diterbitkan dengan nomor: <strong class="font-mono text-white">{{ $certificate->certificate_no }}</strong></p>
                    </div>
                </div>
                <a href="{{ route('lms.certificates.show', $certificate) }}" class="px-5 py-2.5 rounded-xl bg-white text-emerald-800 font-bold text-xs hover:bg-emerald-50 transition shrink-0">
                    Buka Sertifikat Digital →
                </a>
            </div>
        @endif

        @if ($currentMaterial)
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 block mb-1">
                            {{ $currentMaterial->module->title ?? 'Materi Kursus' }}
                        </span>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                            {{ $currentMaterial->title }}
                        </h2>
                    </div>
                    <span class="px-2.5 py-1 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                        {{ $currentMaterial->content_type_label }}
                    </span>
                </div>

                <!-- Content Area based on content_type -->
                @if ($currentMaterial->content_type === 'link' && $currentMaterial->content_url)
                    <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-950 mb-6 shadow-sm">
                        @if (str_contains($currentMaterial->content_url, 'youtube.com') || str_contains($currentMaterial->content_url, 'youtu.be'))
                            <iframe src="{{ $currentMaterial->content_url }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <div class="h-full flex flex-col items-center justify-center p-6 text-white text-center">
                                <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-[#54D6D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <p class="text-xs text-slate-300 mb-4">Tautan Materi / Video Pembelajaran Eksternal</p>
                                <a href="{{ $currentMaterial->content_url }}" target="_blank" class="px-5 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs">
                                    Buka Tautan Materi ↗
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($currentMaterial->content_text)
                    <div class="prose prose-slate max-w-none text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/60 p-6 rounded-2xl border border-slate-100 mb-6">
                        {{ $currentMaterial->content_text }}
                    </div>
                @endif

                <!-- Mark as completed button -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                    @if (in_array($currentMaterial->id, $completedMaterialIds))
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Materi Ini Sudah Selesai
                        </span>
                    @else
                        <form action="{{ route('lms.classroom.material.complete', [$course, $currentMaterial]) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Tandai Selesai & Lanjutkan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-sm text-slate-500">Materi belum tersedia.</p>
            </div>
        @endif
    </div>

    <!-- Syllabus Navigation Sidebar -->
    <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
        <div>
            <div class="flex justify-between items-center text-xs font-bold text-slate-800 mb-2">
                <span>Kemajuan Belajar</span>
                <span class="{{ $progressPercent >= 100 ? 'text-emerald-600' : 'text-blue-600' }}">{{ $progressPercent }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        <div class="space-y-4">
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400">Daftar Modul & Materi</h4>
            @foreach ($course->modules as $mod)
                <div class="rounded-xl border border-slate-200 overflow-hidden text-xs">
                    <div class="px-3.5 py-2.5 bg-slate-100 font-bold text-slate-800">
                        {{ $mod->title }}
                    </div>
                    <ul class="divide-y divide-slate-100">
                        @foreach ($mod->materials as $m)
                            @php
                                $isDone = in_array($m->id, $completedMaterialIds);
                                $isCurrent = $currentMaterial && $currentMaterial->id === $m->id;
                            @endphp
                            <li>
                                <a href="{{ route('lms.classroom.show', ['course' => $course->id, 'material' => $m->id]) }}" class="px-3.5 py-2.5 flex items-center justify-between transition {{ $isCurrent ? 'bg-blue-50 text-blue-700 font-bold' : 'hover:bg-slate-50 text-slate-600' }}">
                                    <span class="flex items-center gap-2 truncate">
                                        @if ($isDone)
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <span class="w-3.5 h-3.5 rounded-full border-2 border-slate-300 shrink-0 inline-block"></span>
                                        @endif
                                        <span class="truncate">{{ $m->title }}</span>
                                    </span>
                                    <span class="text-[9px] uppercase font-semibold text-slate-400 shrink-0">{{ $m->content_type }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        @if ($course->assessments->isNotEmpty())
            <div class="pt-4 border-t border-slate-100">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-2">Evaluasi & Ujian</h4>
                @foreach ($course->assessments as $assessment)
                    <a href="{{ route('lms.assessments.show', $assessment) }}" class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 flex items-center justify-between text-xs transition">
                        <div>
                            <span class="font-bold text-slate-800 block">{{ $assessment->title }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $assessment->type_label }}</span>
                        </div>
                        <span class="text-blue-600 font-bold">Buka →</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection


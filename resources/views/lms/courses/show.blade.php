@extends('layouts.lms')

@section('title', $course->title . ' | FKKSM MM2100')
@section('page_title', $course->title)
@section('page_subtitle', 'Kategori: ' . $course->category . ' • Durasi: ' . $course->duration_hours . ' Jam')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-100 pb-8">
            <div class="space-y-2">
                <span class="px-2.5 py-1 rounded text-xs font-bold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    {{ $course->category }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">{{ $course->title }}</h1>
                <p class="text-xs text-slate-500">Diampu oleh: <strong class="text-slate-800">{{ $course->instructor->name ?? 'Instruktur FKKSM' }}</strong></p>
            </div>

            <div class="text-right shrink-0">
                <div class="text-2xl font-black text-slate-900 mb-2">{{ $course->formatted_price }}</div>
                @if ($isEnrolled)
                    <a href="{{ route('lms.classroom.show', $course) }}" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs inline-flex items-center gap-2 shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Lanjutkan ke Ruang Kelas →</span>
                    </a>
                @else
                    <form action="{{ route('lms.courses.enroll', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs inline-flex items-center gap-2 shadow-sm transition">
                            Daftar Kursus Ini Sekarang →
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="py-6 border-b border-slate-100">
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-2">Deskripsi Kursus</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $course->description ?? $course->summary }}</p>
        </div>

        <!-- Modules & Syllabus breakdown -->
        <div class="pt-6">
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-4">Silabus & Materi Pembelajaran</h3>
            <div class="space-y-4">
                @forelse ($course->modules as $index => $module)
                    <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50/50">
                        <div class="px-4 py-3 bg-slate-100 font-bold text-xs text-slate-800 flex justify-between items-center">
                            <span>{{ $module->title }}</span>
                            <span class="text-[11px] font-normal text-slate-500">{{ $module->materials->count() }} Pelajaran</span>
                        </div>
                        <ul class="divide-y divide-slate-100">
                            @foreach ($module->materials as $mat)
                                <li class="px-4 py-2.5 text-xs text-slate-600 flex items-center justify-between">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>{{ $mat->title }}</span>
                                    </span>
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase">{{ $mat->content_type_label }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Silabus materi sedang disusun oleh instruktur.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


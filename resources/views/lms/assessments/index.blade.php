@extends('layouts.lms')

@section('title', 'Kuis & Evaluasi | FKKSM LMS')
@section('header_eyebrow', 'Uji Pemahaman & Evaluasi')
@section('header_title', 'Kuis & Evaluasi Kompetensi')

@section('content')
<div class="space-y-6">
    @if (auth()->user()->isInstructor())
        <!-- Instructor: Create Evaluation Form -->
        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <h3 class="font-semibold text-base text-slate-900 mb-4 pb-3 border-b border-slate-100">+ Buat Evaluasi / Kuis Baru</h3>
            <form action="{{ route('lms.assessments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end text-xs">
                @csrf
                <div class="md:col-span-2">
                    <label class="block font-medium text-slate-700 mb-1">Kursus *</label>
                    <select name="course_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Judul Ujian / Kuis *</label>
                    <input type="text" name="title" required placeholder="Contoh: Kuis K3 Evakuasi" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tipe</label>
                    <select name="assessment_type" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                        <option value="quiz">Kuis Pilihan / Esai</option>
                        <option value="assignment">Tugas Praktik</option>
                        <option value="evaluation">Evaluasi Akhir</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block font-medium text-slate-700 mb-1">Instruksi Pengerjaan</label>
                    <textarea name="instructions" rows="2" placeholder="Tuliskan panduan pengerjaan kuis atau tugas..." class="w-full px-3.5 py-2 rounded-lg border border-slate-200"></textarea>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                        Simpan Evaluasi →
                    </button>
                </div>
            </form>
        </div>

        @if ($pendingSubmissions->isNotEmpty())
            <div class="bg-white rounded-xl p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-semibold text-base text-slate-900">Jawaban Masuk (Menunggu Penilaian)</h3>
                        <p class="text-xs text-slate-500">Berikan skor dan umpan balik untuk peserta kursus.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-mono font-semibold bg-rose-50 text-rose-700">
                        {{ $pendingSubmissions->count() }} Menunggu
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach ($pendingSubmissions as $sub)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-xs">
                            <div>
                                <span class="font-semibold text-slate-900 text-sm">{{ $sub->user->name }}</span>
                                <span class="text-slate-500 block mt-0.5">{{ $sub->assessment->course->title }} &bull; {{ $sub->assessment->title }}</span>
                                <span class="text-[11px] font-mono text-slate-400">Dikirim: {{ $sub->submitted_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('lms.assessments.show', $sub->assessment) }}" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                                Periksa &amp; Beri Nilai →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- Assessments List -->
    <div class="bg-white rounded-xl p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-semibold text-base text-slate-900">Daftar Kuis &amp; Evaluasi</h3>
                <p class="text-xs text-slate-500">Selesaikan kuis pada setiap modul untuk mengukur pemahaman Anda.</p>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($assessments as $item)
                @php
                    $mySub = $item->relationLoaded('submissions') ? $item->submissions->where('user_id', auth()->id())->first() : null;
                @endphp
                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-[#E7F6F8] text-[#0E97A8] font-mono text-[10px] font-semibold uppercase">
                                {{ $item->type_label }}
                            </span>
                            <span class="font-semibold text-slate-900 text-sm">{{ $item->title }}</span>
                        </div>
                        <p class="text-slate-500">Kursus: <strong class="font-medium text-slate-700">{{ $item->course->title }}</strong></p>
                        @if ($mySub)
                            <p class="font-mono text-[11px] {{ $mySub->status === 'reviewed' ? 'text-emerald-700 font-semibold' : 'text-amber-700' }}">
                                Status: {{ $mySub->status_label }}
                                @if ($mySub->score !== null)
                                    &bull; Nilai {{ number_format((float)$mySub->score, 0) }}/100
                                @endif
                            </p>
                        @endif
                    </div>

                    <a href="{{ route('lms.assessments.show', $item) }}" class="px-4 py-2 rounded-lg {{ $mySub ? 'border border-slate-200 text-slate-700 hover:bg-slate-50' : 'bg-[#10A9BC] hover:bg-[#0E97A8] text-white' }} font-semibold text-xs shrink-0 lms-transition">
                        {{ $mySub ? 'Lihat Hasil →' : 'Mulai Kuis →' }}
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-8 text-center">Belum ada kuis atau evaluasi tersedia pada kursus yang Anda ikuti.</p>
            @endforelse
        </div>

        @if ($assessments->hasPages())
            <div class="pt-4">{{ $assessments->links() }}</div>
        @endif
    </div>
</div>
@endsection

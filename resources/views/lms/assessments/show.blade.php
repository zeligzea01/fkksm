@extends('layouts.lms')

@section('title', $assessment->title . ' | FKKSM LMS')
@section('header_eyebrow', 'Evaluasi & Kuis Kompetensi')
@section('header_title', $assessment->title)

@section('content')
<div class="w-full max-w-5xl 2xl:max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="px-2.5 py-0.5 rounded bg-[#E7F6F8] text-[#0E97A8] font-mono text-[11px] font-semibold uppercase">
                    {{ $assessment->type_label }}
                </span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-2">{{ $assessment->title }}</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Program: <strong class="text-slate-700">{{ $assessment->course->title }}</strong> &bull;
                    Tenggat: <span class="font-mono">{{ $assessment->due_at ? $assessment->due_at->format('d M Y, H:i') : 'Fleksibel' }}</span>
                </p>
            </div>
            <a href="{{ route('lms.assessments.index') }}" class="text-xs font-medium text-slate-500 hover:text-[#0E97A8]">← Daftar Kuis</a>
        </div>

        @if ($assessment->instructions)
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                <strong class="block text-slate-900 text-xs uppercase tracking-wider mb-1.5">Petunjuk Pengerjaan:</strong>
                {{ $assessment->instructions }}
            </div>
        @endif

        @if (!auth()->user()->isInstructor())
            <div class="space-y-4">
                <h3 class="font-semibold text-sm text-slate-900">Jawaban / Hasil Evaluasi Anda</h3>

                @if ($mySubmission)
                    <div class="p-4 rounded-xl border {{ $mySubmission->status === 'reviewed' ? 'border-emerald-200 bg-emerald-50/60' : 'border-amber-200 bg-amber-50/60' }} text-xs space-y-1.5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold {{ $mySubmission->status === 'reviewed' ? 'text-emerald-800' : 'text-amber-800' }}">
                                Status: {{ $mySubmission->status_label }}
                            </span>
                            @if ($mySubmission->score !== null)
                                <span class="text-base font-mono font-bold text-emerald-700 tabular-nums">Nilai: {{ number_format((float)$mySubmission->score, 0) }}/100</span>
                            @endif
                        </div>
                        @if ($mySubmission->feedback)
                            <p class="text-slate-700"><strong class="text-slate-900">Catatan Instruktur:</strong> {{ $mySubmission->feedback }}</p>
                        @endif
                    </div>
                @endif

                <form action="{{ route('lms.assessments.submit', $assessment) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea name="response_text" rows="8" required placeholder="Tuliskan jawaban evaluasi atau analisis kasus Anda di sini..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-[#10A9BC] text-xs sm:text-sm">{{ old('response_text', $mySubmission->response_text ?? '') }}</textarea>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                        {{ $mySubmission ? 'Perbarui Jawaban →' : 'Kirim Jawaban →' }}
                    </button>
                </form>
            </div>
        @else
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <h3 class="font-semibold text-sm text-slate-900">Jawaban Peserta Masuk</h3>
                @forelse ($assessment->submissions as $sub)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3 text-xs">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-semibold text-slate-900 text-sm">{{ $sub->user->name }}</span>
                                <span class="text-slate-500 block font-mono text-[11px]">{{ $sub->user->email }} &bull; {{ $sub->submitted_at->format('d M Y, H:i') }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded font-mono text-[10px] font-semibold {{ $sub->status === 'reviewed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sub->status_label }}
                            </span>
                        </div>

                        <div class="p-3.5 rounded-lg bg-white border border-slate-200 whitespace-pre-line text-slate-700">
                            {{ $sub->response_text }}
                        </div>

                        <form action="{{ route('lms.submissions.grade', $sub) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-medium text-slate-700 mb-1">Nilai (0-100)</label>
                                <input type="number" name="score" min="0" max="100" value="{{ old('score', (int)$sub->score) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-200 font-mono">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-medium text-slate-700 mb-1">Umpan Balik</label>
                                <input type="text" name="feedback" value="{{ old('feedback', $sub->feedback) }}" placeholder="Komentar penilai..." class="w-full px-3 py-2 rounded-lg border border-slate-200">
                            </div>
                            <div>
                                <input type="hidden" name="status" value="reviewed">
                                <button type="submit" class="w-full py-2 rounded-lg bg-[#0F766E] hover:bg-[#0d625b] text-white font-semibold text-xs">
                                    Simpan Nilai
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">Belum ada peserta yang mengumpulkan jawaban.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.lms')

@section('title', 'Detail Evaluasi: ' . $assessment->title)
@section('page_title', $assessment->title)
@section('page_subtitle', 'Kursus: ' . $assessment->course->title . ' • Tipe: ' . $assessment->type_label)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
        <div>
            <span class="px-2.5 py-1 rounded text-xs font-bold bg-blue-100 text-blue-800 uppercase">
                {{ $assessment->type_label }}
            </span>
            <h1 class="text-2xl font-black text-slate-900 mt-2">{{ $assessment->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">Tenggat Waktu: {{ $assessment->due_at ? $assessment->due_at->format('d M Y, H:i') : 'Tidak ditentukan' }}</p>
        </div>

        @if ($assessment->instructions)
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                <strong class="block text-slate-900 text-xs uppercase mb-2">Petunjuk Pengerjaan:</strong>
                {{ $assessment->instructions }}
            </div>
        @endif

        @if (!auth()->user()->isInstructor())
            <!-- Student Form -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-3">Jawaban / Hasil Praktik Anda</h3>

                @if ($mySubmission)
                    <div class="p-4 rounded-2xl border {{ $mySubmission->status === 'reviewed' ? 'border-emerald-200 bg-emerald-50/50' : 'border-amber-200 bg-amber-50/50' }} mb-6 text-xs space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="font-bold {{ $mySubmission->status === 'reviewed' ? 'text-emerald-800' : 'text-amber-800' }}">
                                Status: {{ $mySubmission->status_label }}
                            </span>
                            @if ($mySubmission->score !== null)
                                <span class="text-lg font-black text-emerald-700">Nilai: {{ number_format((float)$mySubmission->score, 0) }}/100</span>
                            @endif
                        </div>
                        @if ($mySubmission->feedback)
                            <p class="text-slate-600 mt-2"><strong class="text-slate-800">Catatan Instruktur:</strong> {{ $mySubmission->feedback }}</p>
                        @endif
                    </div>
                @endif

                <form action="{{ route('lms.assessments.submit', $assessment) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <textarea name="response_text" rows="8" required placeholder="Tuliskan jawaban evaluasi, analisis kasus K3, atau tautan berkas tugas praktik Anda di sini..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs sm:text-sm">{{ old('response_text', $mySubmission->response_text ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition">
                        {{ $mySubmission ? 'Perbarui Jawaban Saya →' : 'Kirim Jawaban Evaluasi →' }}
                    </button>
                </form>
            </div>
        @else
            <!-- Instructor Submissions Grading Table -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-4">Jawaban Peserta yang Masuk</h3>
                <div class="space-y-4">
                    @forelse ($assessment->submissions as $sub)
                        <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-4 text-xs">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm">{{ $sub->user->name }}</span>
                                    <span class="text-slate-500 block text-[11px]">{{ $sub->user->email }} • Dikirim: {{ $sub->submitted_at->format('d M Y, H:i') }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $sub->status === 'reviewed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $sub->status_label }}
                                </span>
                            </div>

                            <div class="p-4 rounded-xl bg-white border border-slate-200 whitespace-pre-line text-slate-700 text-xs">
                                {{ $sub->response_text }}
                            </div>

                            <!-- Grading Form -->
                            <form action="{{ route('lms.submissions.grade', $sub) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end pt-2">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Nilai (0-100)</label>
                                    <input type="number" name="score" min="0" max="100" value="{{ old('score', (int)$sub->score) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Catatan Feedback</label>
                                    <input type="text" name="feedback" value="{{ old('feedback', $sub->feedback) }}" placeholder="Komentar atau saran perbaikan..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                                </div>
                                <div>
                                    <input type="hidden" name="status" value="reviewed">
                                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition inline-flex items-center justify-center gap-1.5">
                                        <span>Simpan Nilai</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada peserta yang mengumpulkan jawaban.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


@extends('layouts.lms')

@section('title', 'Evaluasi & Ujian Kompetensi | FKKSM MM2100')
@section('page_title', 'Evaluasi & Ujian Praktik')
@section('page_subtitle', 'Penilaian kompetensi peserta untuk persyaratan kelulusan sertifikasi.')

@section('content')
<div class="space-y-8">
    @if (auth()->user()->isInstructor())
        <!-- Instructor: Form Tambah Evaluasi -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <h3 class="font-bold text-base text-slate-900 mb-4 pb-2 border-b border-slate-100">+ Buat Evaluasi / Kuis Baru</h3>
            <form action="{{ route('lms.assessments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kursus</label>
                    <select name="course_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Ujian / Kuis</label>
                    <input type="text" name="title" required placeholder="Contoh: Kuis K3 Evakuasi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe</label>
                    <select name="assessment_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                        <option value="quiz">Kuis Pilihan / Esai</option>
                        <option value="assignment">Tugas Praktik</option>
                        <option value="evaluation">Evaluasi Akhir</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                        Simpan Evaluasi →
                    </button>
                </div>
            </form>
        </div>

        @if ($pendingSubmissions->isNotEmpty())
            <!-- Pending submissions to grade -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Jawaban Masuk (Menunggu Penilaian)</h3>
                        <p class="text-xs text-slate-500">Berikan skor dan umpan balik untuk peserta kursus.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                        {{ $pendingSubmissions->count() }} Menunggu
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach ($pendingSubmissions as $sub)
                        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-xs">
                            <div>
                                <span class="font-bold text-slate-900 text-sm">{{ $sub->user->name }}</span>
                                <span class="text-slate-500 block mt-0.5">Kursus: {{ $sub->assessment->course->title }} • {{ $sub->assessment->title }}</span>
                                <span class="text-[10px] text-slate-400">Dikirim: {{ $sub->submitted_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('lms.assessments.show', $sub->assessment) }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 transition">
                                Periksa & Beri Nilai →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- Assessments List -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <h3 class="font-bold text-base text-slate-900 mb-4 pb-3 border-b border-slate-100">Daftar Evaluasi Pembelajaran</h3>
        <div class="divide-y divide-slate-100">
            @forelse ($assessments as $item)
                <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 uppercase mr-2">
                            {{ $item->type_label }}
                        </span>
                        <span class="font-bold text-slate-900 text-sm">{{ $item->title }}</span>
                        <p class="text-slate-500 text-xs mt-1">Kursus: {{ $item->course->title }}</p>
                    </div>
                    <a href="{{ route('lms.assessments.show', $item) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition">
                        Buka Evaluasi →
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada evaluasi yang tersedia.</p>
            @endforelse
        </div>
        <div class="pt-4">
            {{ $assessments->links() }}
        </div>
    </div>
</div>
@endsection


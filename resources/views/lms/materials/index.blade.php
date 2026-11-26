@extends('layouts.lms', ['title' => 'Manajemen Modul & Materi 4 Tahap — FKKSM LMS'])

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-[#333333]">Silabus Modul & Materi 4 Tahap</h1>
        <p class="text-xs text-[#757575] mt-0.5">Kelola urutan pembelajaran wajib: <strong>1. Video (Storage) → 2. Materi/E-Book → 3. Latihan Soal → 4. Latihan Akhir</strong>.</p>
    </div>
    <a href="{{ route('lms.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#10A9BC] hover:bg-[#0d8e9e] text-white text-xs font-extrabold transition-colors">
        + Buat Kursus Baru
    </a>
</div>

<div class="space-y-6">
    @forelse($courses as $course)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 bg-slate-50/70 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-[#E7F6F8] text-[#10A9BC] text-[10px] font-extrabold uppercase">
                            {{ $course->category }}
                        </span>
                        <span class="text-xs text-[#757575]">Instruktur: {{ $course->instructor?->name ?? 'FKKSM' }}</span>
                    </div>
                    <h2 class="text-base font-extrabold text-[#333333] mt-1">{{ $course->title }}</h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('lms.classroom.show', $course) }}" class="px-3 py-1.5 rounded-lg border border-[#10A9BC] text-[#10A9BC] hover:bg-[#E7F6F8] text-xs font-bold transition-colors">
                        Pratinjau Player
                    </a>
                    <button type="button" onclick="document.getElementById('modal-mod-{{ $course->id }}').classList.remove('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-xs font-bold text-[#333333] cursor-pointer">
                        + Tambah Modul
                    </button>
                    <button type="button" onclick="document.getElementById('modal-mat-{{ $course->id }}').classList.remove('hidden')" class="px-3 py-1.5 rounded-lg bg-[#10A9BC] hover:bg-[#0d8e9e] text-white text-xs font-bold cursor-pointer">
                        + Tambah Tahap Materi
                    </button>
                </div>
            </div>

            <div class="p-5 space-y-4">
                @forelse($course->modules as $module)
                    <div class="rounded-xl border border-slate-200/80 overflow-hidden">
                        <div class="px-4 py-2.5 bg-slate-50 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-extrabold text-[#333333]">{{ $module->title }}</span>
                                @if($module->summary)
                                    <span class="text-[11px] text-[#757575] ml-2">— {{ $module->summary }}</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('lms.modules.destroy', $module) }}" data-confirm="Hapus modul ini beserta seluruh tahapan materinya?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-bold text-red-600 hover:underline cursor-pointer">Hapus Modul</button>
                            </form>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($module->materials as $mat)
                                <div class="px-4 py-2.5 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <span class="px-2 py-0.5 rounded bg-[#E7F6F8] text-[#0C3553] text-[10px] font-extrabold uppercase">
                                            {{ $mat->sidebar_type_label }}
                                        </span>
                                        <span class="font-semibold text-[#333333]">{{ $mat->title }}</span>
                                        @if($mat->video_path)
                                            <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-mono">
                                                storage/{{ $mat->video_path }}
                                            </span>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('lms.materials.destroy', $mat) }}" data-confirm="Hapus tahap materi ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] font-bold text-red-500 hover:underline cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            @empty
                                <div class="px-4 py-3 text-xs text-slate-400">Belum ada tahapan materi di modul ini.</div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-[#757575]">Belum ada modul silabus untuk kursus ini.</div>
                @endforelse
            </div>
        </div>

        {{-- Modal Tambah Modul --}}
        <div id="modal-mod-{{ $course->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
                <h3 class="text-base font-extrabold text-[#333333] mb-4">Tambah Modul ke {{ $course->title }}</h3>
                <form method="POST" action="{{ route('lms.modules.store', $course) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1">Judul Modul</label>
                        <input type="text" name="title" required placeholder="Contoh: Modul 1: Dasar K3 Pabrik" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1">Ringkasan Modul</label>
                        <input type="text" name="summary" placeholder="Deskripsi singkat modul" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="document.getElementById('modal-mod-{{ $course->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#10A9BC] text-white text-xs font-extrabold cursor-pointer">Simpan Modul</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Tambah Materi (Supports Video Storage Upload, Materi E-Book, Latihan Soal) --}}
        <div id="modal-mat-{{ $course->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl my-8">
                <h3 class="text-base font-extrabold text-[#333333] mb-4">Tambah Tahap Pembelajaran ke {{ $course->title }}</h3>
                <form method="POST" action="{{ route('lms.materials.store', $course) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#333333] mb-1">Pilih Modul</label>
                            <select name="module_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                                @foreach($course->modules as $mod)
                                    <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#333333] mb-1">Tahap Pembelajaran</label>
                            <select name="content_type" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                                <option value="video">1. Video Pembelajaran (Storage)</option>
                                <option value="ebook">2. Materi / E-Book Bacaan</option>
                                <option value="text">2. Materi Teks / SOP</option>
                                <option value="practice">3. Latihan Soal (Kuis Modul)</option>
                                <option value="link">Tautan Materi Eksternal</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-[#333333] mb-1">Judul Tahap Materi</label>
                            <input type="text" name="title" required placeholder="Judul Video / Materi / Latihan Soal" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#333333] mb-1">Durasi (Menit)</label>
                            <input type="number" name="duration_minutes" value="5" min="1" max="300" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#F7F9FB] border border-[#E5E9F0] space-y-2">
                        <label class="block text-xs font-extrabold text-[#0C3553]">Unggah Video ke Local Storage (Khusus Tahap Video — Maks 20MB MP4/WEBM)</label>
                        <input type="file" name="video_file" accept="video/mp4,video/webm" class="w-full text-xs text-slate-600">
                        <p class="text-[11px] text-[#757575]">Jika dikosongkan pada tipe Video, sistem otomatis menggunakan video sampel ringan di <code>storage/app/public/videos/fkksm-sample.mp4</code>.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1">URL Eksternal (Opsional untuk Tautan Materi)</label>
                        <input type="url" name="content_url" placeholder="https://..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#333333] mb-1">Isi Materi / Ringkasan E-Book / Pengantar</label>
                        <textarea name="content_text" rows="3" placeholder="Tuliskan uraian materi lengkap..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs"></textarea>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="text-xs font-extrabold text-[#333333]">Pertanyaan Latihan Soal (Opsional — Khusus Tahap 3: Latihan Soal)</div>
                        <input type="text" name="quiz_question" placeholder="Pertanyaan pilihan ganda..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <input type="text" name="quiz_options[0]" placeholder="Opsi A (Index 0)" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs">
                            <input type="text" name="quiz_options[1]" placeholder="Opsi B (Index 1)" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs">
                            <input type="text" name="quiz_options[2]" placeholder="Opsi C (Index 2)" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-[#757575]">Kunci Jawaban Benar:</label>
                            <select name="quiz_answer" class="ml-2 px-2.5 py-1 rounded border border-slate-200 bg-white text-xs">
                                <option value="0">Opsi A</option>
                                <option value="1">Opsi B</option>
                                <option value="2">Opsi C</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="document.getElementById('modal-mat-{{ $course->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#10A9BC] text-white text-xs font-extrabold cursor-pointer">Simpan Tahap Materi</button>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-xs text-[#757575]">
            Belum ada kursus yang Anda ampu.
        </div>
    @endforelse
</div>
@endsection

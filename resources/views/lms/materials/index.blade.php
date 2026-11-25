@extends('layouts.lms')

@section('title', 'Manajemen Materi & Modul | FKKSM MM2100')
@section('page_title', 'Manajemen Modul & Pelajaran')
@section('page_subtitle', 'Atur hierarki silabus, video instruksi, dan teks materi pada setiap kursus.')

@section('content')
<div class="space-y-8">
    @foreach ($courses as $course)
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                <div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        {{ $course->category }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900 mt-1">{{ $course->title }}</h3>
                </div>

                <!-- Modals Trigger Buttons -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="document.getElementById('moduleModal-{{ $course->id }}').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition">
                        + Tambah Modul
                    </button>
                    <button type="button" onclick="document.getElementById('materialModal-{{ $course->id }}').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition">
                        + Tambah Materi
                    </button>
                </div>
            </div>

            <!-- Modules and Materials List -->
            <div class="space-y-4">
                @forelse ($course->modules as $mod)
                    <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50/50">
                        <div class="px-4 py-3 bg-slate-100 flex items-center justify-between text-xs font-bold text-slate-800">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#10A9BC] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                <span>{{ $mod->title }}</span>
                            </span>
                            <form action="{{ route('lms.modules.destroy', $mod) }}" method="POST" onsubmit="return confirm('Hapus modul ini beserta seluruh materinya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-700 font-semibold text-[11px]">Hapus Modul</button>
                            </form>
                        </div>

                        <ul class="divide-y divide-slate-100">
                            @forelse ($mod->materials as $mat)
                                <li class="px-4 py-3 bg-white flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $mat->title }}</p>
                                            <span class="text-[10px] text-slate-400 uppercase font-semibold">{{ $mat->content_type_label }}</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('lms.materials.destroy', $mat) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition text-[11px]">Hapus</button>
                                    </form>
                                </li>
                            @empty
                                <li class="px-4 py-3 text-xs text-slate-400 text-center">Belum ada materi di modul ini.</li>
                            @endforelse
                        </ul>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Kursus ini belum memiliki modul. Klik "+ Tambah Modul" untuk memulai.</p>
                @endforelse
            </div>

            <!-- Modal Tambah Modul -->
            <div id="moduleModal-{{ $course->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl">
                    <h4 class="font-bold text-lg text-slate-900 mb-4">Tambah Modul ke: {{ $course->title }}</h4>
                    <form action="{{ route('lms.modules.store', $course) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Modul *</label>
                            <input type="text" name="title" required placeholder="Contoh: Modul 1: Pengenalan Regulasi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ringkasan</label>
                            <textarea name="summary" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" onclick="document.getElementById('moduleModal-{{ $course->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold">Batal</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs">Simpan Modul</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Tambah Materi -->
            <div id="materialModal-{{ $course->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl">
                    <h4 class="font-bold text-lg text-slate-900 mb-4">Tambah Materi Pelajaran</h4>
                    <form action="{{ route('lms.materials.store', $course) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Modul</label>
                            <select name="module_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                                @foreach ($course->modules as $mod)
                                    <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Materi *</label>
                            <input type="text" name="title" required placeholder="Contoh: Prosedur Penggunaan APAR" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Materi</label>
                                <select name="content_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                                    <option value="text">Teks Dokumen</option>
                                    <option value="link">Tautan / Video</option>
                                    <option value="file">Berkas Unduhan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">URL Tautan / Video (Opsional)</label>
                                <input type="url" name="content_url" placeholder="https://youtube.com/..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Teks Materi Lengkap</label>
                            <textarea name="content_text" rows="4" placeholder="Tuliskan naskah materi pembelajaran di sini..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" onclick="document.getElementById('materialModal-{{ $course->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold">Batal</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs">Simpan Materi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @if ($courses->hasPages())
        <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-xs">
            {{ $courses->links() }}
        </div>
    @endif
</div>
@endsection


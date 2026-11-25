@extends('layouts.lms')

@section('title', 'Buat Kursus Baru | FKKSM MM2100')
@section('page_title', 'Buat Kursus Pelatihan Baru')
@section('page_subtitle', 'Tambahkan silabus dan informasi program pelatihan untuk praktisi industri.')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
    <form action="{{ route('lms.courses.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Kursus Pelatihan *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Manajemen K3 & Penilaian Risiko Pabrik Manufaktur" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Program</label>
                <input type="text" name="category" value="{{ old('category', 'Keselamatan Kerja (K3)') }}" placeholder="K3 / Human Resources / Vokasi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Estimasi Durasi (Jam)</label>
                <input type="number" step="0.5" name="duration_hours" value="{{ old('duration_hours', '4.0') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ringkasan Singkat (Summary)</label>
            <textarea name="summary" rows="2" placeholder="Penjelasan 1-2 kalimat untuk kartu kursus..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('summary') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Lengkap Silabus</label>
            <textarea name="description" rows="5" placeholder="Tuliskan tujuan pelatihan, kompetensi yang diperoleh, dan target peserta..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Akses</label>
                <select name="access_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="free">Gratis</option>
                    <option value="paid">Berbayar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Biaya (Rp)</label>
                <input type="number" name="price" value="{{ old('price', '0') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Publikasi</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="published">Terbit (Published)</option>
                    <option value="draft">Draf</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('lms.courses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition">
                Simpan Kursus Baru →
            </button>
        </div>
    </form>
</div>
@endsection


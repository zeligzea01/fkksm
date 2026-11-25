@extends('layouts.lms')

@section('title', 'Edit Kursus: ' . $course->title)
@section('page_title', 'Perbarui Kursus: ' . $course->title)
@section('page_subtitle', 'Edit parameter program dan informasi instruktur.')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
    <form action="{{ route('lms.courses.update', $course) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Kursus Pelatihan *</label>
            <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Program</label>
                <input type="text" name="category" value="{{ old('category', $course->category) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Estimasi Durasi (Jam)</label>
                <input type="number" step="0.5" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ringkasan Singkat (Summary)</label>
            <textarea name="summary" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('summary', $course->summary) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Lengkap Silabus</label>
            <textarea name="description" rows="5" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('description', $course->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Akses</label>
                <select name="access_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="free" {{ $course->access_type === 'free' ? 'selected' : '' }}>Gratis</option>
                    <option value="paid" {{ $course->access_type === 'paid' ? 'selected' : '' }}>Berbayar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Biaya (Rp)</label>
                <input type="number" name="price" value="{{ old('price', (int)$course->price) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Publikasi</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Terbit (Published)</option>
                    <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draf</option>
                    <option value="archived" {{ $course->status === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('lms.courses.show', $course->slug) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition">
                Perbarui Kursus →
            </button>
        </div>
    </form>
</div>
@endsection


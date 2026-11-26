@extends('layouts.lms')

@section('title', 'Edit Kursus: ' . $course->title)
@section('header_eyebrow', 'Manajemen Kurikulum')
@section('header_title', 'Perbarui Kursus: ' . $course->title)

@section('content')
<div class="w-full max-w-5xl 2xl:max-w-6xl mx-auto bg-white rounded-xl p-6 sm:p-8 border border-slate-200">
    <form action="{{ route('lms.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-medium text-slate-700 mb-1">Judul Kursus Pelatihan *</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC] text-sm">
            </div>

            <div>
                <label class="block font-medium text-slate-700 mb-1">Kategori Program</label>
                <input type="text" name="category" value="{{ old('category', $course->category) }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Penempatan Section Halaman Home (/)</label>
                <select name="featured_section" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                    <option value="catalog" {{ $course->featured_section === 'catalog' ? 'selected' : '' }}>Katalog Umum</option>
                    <option value="bestseller" {{ $course->featured_section === 'bestseller' ? 'selected' : '' }}>Kursus Terlaris</option>
                    <option value="prakerja" {{ $course->featured_section === 'prakerja' ? 'selected' : '' }}>Pelatihan Prakerja</option>
                    <option value="webinar" {{ $course->featured_section === 'webinar' ? 'selected' : '' }}>Live Webinar</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-medium text-slate-700 mb-1">Ringkasan Singkat (Summary)</label>
            <textarea name="summary" rows="2" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">{{ old('summary', $course->summary) }}</textarea>
        </div>

        <div>
            <label class="block font-medium text-slate-700 mb-1">Deskripsi Lengkap Silabus</label>
            <textarea name="description" rows="4" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">{{ old('description', $course->description) }}</textarea>
        </div>

        <div>
            <label class="block font-medium text-slate-700 mb-1">Persyaratan Peserta</label>
            <input type="text" name="requirements" value="{{ old('requirements', $course->requirements) }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <label class="block font-medium text-slate-700 mb-1">Durasi (Jam)</label>
                <input type="number" step="0.5" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Tipe Akses</label>
                <select name="access_type" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                    <option value="free" {{ $course->access_type === 'free' ? 'selected' : '' }}>Gratis</option>
                    <option value="paid" {{ $course->access_type === 'paid' ? 'selected' : '' }}>Berbayar</option>
                </select>
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Biaya (Rp)</label>
                <input type="number" name="price" value="{{ old('price', (int)$course->price) }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Status Publikasi</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                    <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Terbit (Published)</option>
                    <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draf</option>
                    <option value="archived" {{ $course->status === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-slate-700 mb-1">Thumbnail Path</label>
                <input type="text" name="thumbnail" value="{{ old('thumbnail', $course->thumbnail) }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Atau Unggah Thumbnail Baru</label>
                <input type="file" name="thumbnail_file" accept="image/*" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50">
            </div>
        </div>

        <!-- Skill Badges Editor -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-slate-900">Lencana Kompetensi (Skill Badges)</h4>
                    <p class="text-[11px] text-slate-500">Ikon skill yang tampil di katalog web dan ruang belajar.</p>
                </div>
                <button type="button" onclick="addCourseSkillBadge()" class="px-3 py-1.5 rounded-lg border border-[#10A9BC] text-[#0E97A8] font-semibold hover:bg-[#E7F6F8]">+ Tambah Skill Badge</button>
            </div>
            <div id="courseSkillBadgesList" class="space-y-2">
                @foreach (($course->skill_badges ?? []) as $sb)
                    <div class="flex items-center gap-2 badge-row">
                        <img src="{{ asset('assets/fkksm/' . ($sb['icon'] ?? '1765179223375_166389665.png')) }}" class="w-7 h-7 rounded-full border border-slate-200 bg-white shrink-0">
                        <select name="badge_icons[]" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white w-56" onchange="this.previousElementSibling.src='{{ asset('assets/fkksm') }}/' + this.value">
                            @foreach ($skillBadgeCatalog as $opt)
                                <option value="{{ $opt['icon'] }}" {{ ($sb['icon'] ?? '') === $opt['icon'] ? 'selected' : '' }}>{{ $opt['default_name'] }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="badge_names[]" value="{{ $sb['name'] ?? '' }}" placeholder="Nama Skill..." class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white">
                        <button type="button" onclick="this.closest('.badge-row').remove()" class="text-rose-600 font-bold px-2">×</button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('lms.courses.show', $course->slug) }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold lms-transition">
                Perbarui Kursus →
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const badgeCatalog = @json($skillBadgeCatalog);
    const badgeAssetBase = "{{ asset('assets/fkksm') }}";
    function addCourseSkillBadge() {
        const list = document.getElementById('courseSkillBadgesList');
        const options = badgeCatalog.map(s => `<option value="${s.icon}">${s.default_name}</option>`).join('');
        const first = badgeCatalog[0];
        list.insertAdjacentHTML('beforeend', `
            <div class="flex items-center gap-2 badge-row">
                <img src="${badgeAssetBase}/${first.icon}" class="w-7 h-7 rounded-full border border-slate-200 bg-white shrink-0">
                <select name="badge_icons[]" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white w-56" onchange="this.previousElementSibling.src='${badgeAssetBase}/' + this.value">
                    ${options}
                </select>
                <input type="text" name="badge_names[]" value="${first.default_name}" placeholder="Nama Skill..." class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white">
                <button type="button" onclick="this.closest('.badge-row').remove()" class="text-rose-600 font-bold px-2">×</button>
            </div>
        `);
    }
</script>
@endpush
@endsection

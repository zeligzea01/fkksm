@extends('layouts.lms')

@section('title', 'Buat Kursus Baru | FKKSM LMS')
@section('header_eyebrow', 'Manajemen Kurikulum')
@section('header_title', 'Buat Kursus Pelatihan Baru')

@section('content')
<div class="w-full max-w-5xl 2xl:max-w-6xl mx-auto bg-white rounded-xl p-6 sm:p-8 border border-slate-200">
    <form action="{{ route('lms.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-medium text-slate-700 mb-1">Judul Kursus Pelatihan *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Manajemen K3 & Penilaian Risiko Pabrik Manufaktur" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC] text-sm">
            </div>

            <div>
                <label class="block font-medium text-slate-700 mb-1">Kategori Program</label>
                <input type="text" name="category" value="{{ old('category', 'Keselamatan Kerja (K3)') }}" placeholder="K3 / Human Resources / Bahasa" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Penempatan Section Halaman Home (/)</label>
                <select name="featured_section" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                    <option value="catalog">Katalog Umum</option>
                    <option value="bestseller">Kursus Terlaris</option>
                    <option value="prakerja">Pelatihan Prakerja</option>
                    <option value="webinar">Live Webinar</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-medium text-slate-700 mb-1">Ringkasan Singkat (Summary)</label>
            <textarea name="summary" rows="2" placeholder="Penjelasan 1-2 kalimat untuk kartu kursus..." class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">{{ old('summary') }}</textarea>
        </div>

        <div>
            <label class="block font-medium text-slate-700 mb-1">Deskripsi Lengkap Silabus</label>
            <textarea name="description" rows="4" placeholder="Tuliskan tujuan pelatihan dan kompetensi yang diperoleh..." class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block font-medium text-slate-700 mb-1">Persyaratan Peserta</label>
            <input type="text" name="requirements" value="{{ old('requirements', 'Terbuka untuk seluruh staf, operator, dan peserta FKKSM MM2100.') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200">
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <label class="block font-medium text-slate-700 mb-1">Durasi (Jam)</label>
                <input type="number" step="0.5" name="duration_hours" value="{{ old('duration_hours', '4.0') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Tipe Akses</label>
                <select name="access_type" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                    <option value="free">Gratis</option>
                    <option value="paid">Berbayar</option>
                </select>
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Biaya (Rp)</label>
                <input type="number" name="price" value="{{ old('price', '0') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 font-mono">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Status Publikasi</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 bg-white">
                    <option value="published">Terbit (Published)</option>
                    <option value="draft">Draf</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-slate-700 mb-1">Thumbnail Path (Opsional)</label>
                <input type="text" name="thumbnail" value="{{ old('thumbnail', 'assets/fkksm/_1795405504399_1756706515.png') }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
            </div>
            <div>
                <label class="block font-medium text-slate-700 mb-1">Atau Unggah Thumbnail Baru</label>
                <input type="file" name="thumbnail_file" accept="image/*" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50">
            </div>
        </div>

        <!-- Skill Badges Picker -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-slate-900">Lencana Kompetensi (Skill Badges)</h4>
                    <p class="text-[11px] text-slate-500">Ditampilkan sebagai badge melingkar di atas kartu kursus.</p>
                </div>
                <button type="button" onclick="addCourseSkillBadge()" class="px-3 py-1.5 rounded-lg border border-[#10A9BC] text-[#0E97A8] font-semibold hover:bg-[#E7F6F8]">+ Tambah Skill Badge</button>
            </div>
            <div id="courseSkillBadgesList" class="space-y-2"></div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('lms.courses.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold lms-transition">
                Simpan Kursus Baru →
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

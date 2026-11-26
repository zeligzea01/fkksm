<!-- Course Section: Paginated Courses Catalog with Component Cards -->
<section class="py-14 bg-white border-b border-slate-200" id="katalog-kursus">
    <div class="fkksm-container">
        <x-section-header
            badge="KURIKULUM RESMI INDUSTRI"
            title="Katalog Kursus &amp; Program Sertifikasi"
            subtitle="Tersedia program daring dan bauran (hybrid) bersertifikat kompetensi yang diakui kementerian dan industri manufaktur MM2100."
            :linkUrl="route('lms.courses.index')"
            linkText="Lihat Seluruh Kursus"
        />

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 min-[2000px]:grid-cols-5 gap-6">
            @forelse ($courses as $course)
                <x-course-card :course="$course" />
            @empty
                <div class="col-span-full text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <h3 class="font-heading font-bold text-lg text-slate-700 uppercase">Belum Ada Kursus Diterbitkan</h3>
                    <p class="text-xs text-slate-400 mt-1">Silakan periksa kembali nanti atau hubungi sekretariat FKKSM.</p>
                </div>
            @endforelse
        </div>

        <!-- Optimized Pagination -->
        @if ($courses->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $courses->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</section>


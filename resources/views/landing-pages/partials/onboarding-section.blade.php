<!-- Onboarding Section: Rocket banner for new learners -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-12 h-12 shrink-0 rounded-lg bg-sky-50 border border-sky-100 flex items-center justify-center p-2">
                <img src="{{ asset('cakap_files/blue-rocket.webp') }}" alt="Rocket" class="w-8 h-8 object-contain">
            </div>
            <div>
                <p class="font-heading font-bold text-base sm:text-lg text-slate-900 leading-snug">
                    Dapatkan voucher eksklusif dan kursus terbaik sesuai minatmu.
                </p>
                <p class="text-xs sm:text-sm text-slate-500 font-normal">
                    Bergabung bersama ribuan profesional industri MM2100 sekarang juga!
                </p>
            </div>
        </div>
        <div class="shrink-0 w-full sm:w-auto">
            @guest
                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-[#27ae60] hover:bg-[#219653] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                    Daftar Sekarang
                </a>
            @else
                <a href="{{ route('lms.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-[#0069b1] hover:bg-[#005a99] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs">
                    Masuk Belajar
                </a>
            @endguest
        </div>
    </div>
</section>


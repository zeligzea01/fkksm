@php
    $vb = $sections['voucher_banner'] ?? null;
    $vbc = $vb['content'] ?? [];
@endphp

@if($vb['is_active'] ?? true)
<!-- Section: Redeem Kode Belajar / Voucher Promo Banner (Dynamic via Admin CMS) -->
<section class="fkksm-container py-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-14 h-14 shrink-0 rounded-lg bg-cyan-50 border border-cyan-100 text-[#10A9BC] flex items-center justify-center p-2.5">
                @if(!empty($vbc['image']) && !str_contains($vbc['image'], 'voucher-icon'))
                    <img src="{{ \App\Models\LandingSection::resolveImageUrl($vbc['image']) }}" alt="Voucher Belajar" class="w-10 h-10 object-contain">
                @else
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                @endif
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg sm:text-xl text-[#0C3553] leading-snug">
                    {{ $vbc['heading'] ?? 'Punya kode belajar?' }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">
                    {{ $vbc['description'] ?? 'Tukar kode belajarmu di sini untuk membuka akses materi pelatihan dan kelas sertifikasi.' }}
                </p>
            </div>
        </div>

        <div class="shrink-0 w-full sm:w-auto">
            <button type="button" onclick="openVoucherModal()" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition shadow-xs cursor-pointer">
                {{ $vbc['cta_label'] ?? 'Tukar Kode' }}
            </button>
        </div>
    </div>
</section>
@endif

<!-- Modal: Tukar Kode Belajar -->
<div id="voucherModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full border border-slate-200 p-6 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2 text-[#10A9BC]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <h3 class="font-heading font-bold text-lg text-slate-900">Tukar Kode Belajar</h3>
            </div>
            <button type="button" onclick="closeVoucherModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @auth
            <form action="{{ route('lms.vouchers.redeem') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="voucher_code_input" class="block text-xs font-mono font-medium text-slate-700 uppercase tracking-wider mb-1.5">
                        Masukkan Kode Voucher / Token Pelatihan
                    </label>
                    <input type="text" id="voucher_code_input" name="code" required placeholder="Contoh: FKKSM2026" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-[#10A9BC] focus:ring-1 focus:ring-[#10A9BC] text-sm font-mono uppercase">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeVoucherModal()" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-heading font-bold uppercase tracking-wider">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-heading font-bold uppercase tracking-wider transition">
                        Gunakan Kode
                    </button>
                </div>
            </form>
        @else
            <div class="mt-4 space-y-4 text-center">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Silakan masuk atau daftar akun terlebih dahulu untuk mengklaim dan mengaktifkan kode voucher belajar ke akun Anda.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 pt-2">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-heading font-bold uppercase tracking-wider transition">
                        Masuk Akun
                    </a>
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-heading font-bold uppercase tracking-wider">
                        Daftar Baru
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>

<script>
    function openVoucherModal(prefillCode = null) {
        const modal = document.getElementById('voucherModal');
        const input = document.getElementById('voucher_code_input');
        if (input && prefillCode) {
            input.value = prefillCode;
        }
        modal.classList.remove('hidden');
    }
    function closeVoucherModal() {
        document.getElementById('voucherModal').classList.add('hidden');
    }
</script>

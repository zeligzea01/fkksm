@extends('layouts.lms')

@section('title', 'Voucher & Redemption | FKKSM LMS')
@section('header_eyebrow', '3. Voucher & Redemption System')
@section('header_title', 'Voucher & Penukaran Kode')

@section('content')
<div class="space-y-6">
    <!-- Top Section Context -->
    <div class="flex items-center justify-between text-xs text-slate-500">
        <span>3. Voucher &amp; Redemption System</span>
        <span class="font-mono uppercase text-slate-400">LAYAR 3B</span>
    </div>

    <!-- Hero Banner matching Figma Layar 3B (page_2.png) -->
    <div class="bg-lms-hero rounded-xl p-6 sm:p-8 text-white flex items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <h2 class="text-2xl font-bold tracking-tight">Redemption / Penukaran Kode</h2>
            <p class="text-xs sm:text-sm text-white/85 leading-relaxed">
                Gunakan kode voucher dari perusahaan atau sponsor FKKSM untuk mendapatkan akses gratis, potongan biaya, atau hak mengikuti program tertentu.
            </p>
        </div>

        <!-- Outline Ticket Icon matching Figma -->
        <div class="hidden sm:flex items-center justify-center shrink-0">
            <svg class="w-24 h-16 text-white/75" fill="none" stroke="currentColor" viewBox="0 0 96 64">
                <rect x="4" y="6" width="88" height="52" rx="10" stroke-width="2.5"/>
                <circle cx="4" cy="32" r="7" fill="#299E93" stroke="white" stroke-width="2.5"/>
                <circle cx="92" cy="32" r="7" fill="#299E93" stroke="white" stroke-width="2.5"/>
                <path d="M56 16V48" stroke-width="2" stroke-dasharray="4 4"/>
            </svg>
        </div>
    </div>

    <!-- 2-Column Interactive Redemption Grid matching Figma Layar 3B -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left Card: Tukarkan kode voucher -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 space-y-5">
            <h3 class="font-semibold text-base text-slate-900">Tukarkan kode voucher</h3>

            <form action="{{ route('lms.vouchers.redeem') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2.5">
                    <label for="voucherCodeInput" class="block text-xs font-medium text-slate-600">Kode voucher</label>
                    <input type="text" id="voucherCodeInput" name="code" value="{{ old('code') }}" required placeholder="Masukkan kode voucher (misal: FKKSM2026)" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm font-mono uppercase text-slate-900 focus:outline-none focus:border-[#10A9BC]">
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="checkVoucherCode()" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs inline-flex items-center gap-2 lms-transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            <span>Periksa kode</span>
                        </button>
                        <span id="checkFeedbackMsg" class="text-xs font-medium text-slate-500"></span>
                    </div>
                </div>

                <!-- Manfaat voucher Table matching Figma -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <h4 class="font-semibold text-sm text-slate-900">Manfaat voucher</h4>
                    <div class="divide-y divide-slate-100 text-xs">
                        <div class="py-2.5 flex items-center justify-between gap-4">
                            <span class="text-slate-500">Jenis manfaat</span>
                            <span id="previewBenefitType" class="font-semibold text-slate-800">[Akses gratis / diskon / program]</span>
                        </div>
                        <div class="py-2.5 flex items-center justify-between gap-4">
                            <span class="text-slate-500">Program</span>
                            <span id="previewProgram" class="font-semibold text-slate-800 text-right">[Nama program]</span>
                        </div>
                        <div class="py-2.5 flex items-center justify-between gap-4">
                            <span class="text-slate-500">Masa berlaku</span>
                            <span id="previewExpiry" class="font-mono font-semibold text-slate-800">[Masa berlaku]</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs inline-flex items-center justify-center gap-2 lms-transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Tukarkan kode</span>
                </button>
            </form>
        </div>

        <!-- Right Card: Status voucher Indicators matching Figma Layar 3B -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <div>
                <h3 class="font-semibold text-base text-slate-900">Status voucher</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kondisi yang tersedia dalam alur pemeriksaan &amp; penukaran kode.</p>
            </div>

            <div class="space-y-3 text-xs">
                <div id="statusCard-available" class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 flex items-start gap-3 lms-transition">
                    <svg class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <div>
                        <strong class="block text-emerald-900 font-semibold">Tersedia</strong>
                        <span class="text-emerald-700 text-[11px]">Kode dapat ditukarkan sesuai ketentuannya.</span>
                    </div>
                </div>

                <div id="statusCard-used" class="p-3.5 rounded-xl bg-[#E7F6F8]/80 border border-[#C7E8EE] flex items-start gap-3 lms-transition">
                    <svg class="w-4 h-4 text-[#0E97A8] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <div>
                        <strong class="block text-slate-900 font-semibold">Digunakan</strong>
                        <span class="text-slate-600 text-[11px]">Kode sudah digunakan oleh akun Anda.</span>
                    </div>
                </div>

                <div id="statusCard-expired" class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 flex items-start gap-3 lms-transition">
                    <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <strong class="block text-amber-900 font-semibold">Kedaluwarsa</strong>
                        <span class="text-amber-700 text-[11px]">Masa berlaku kode sudah berakhir.</span>
                    </div>
                </div>

                <div id="statusCard-invalid" class="p-3.5 rounded-xl bg-rose-50/70 border border-rose-200 flex items-start gap-3 lms-transition">
                    <svg class="w-4 h-4 text-rose-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <div>
                        <strong class="block text-rose-900 font-semibold">Tidak valid</strong>
                        <span class="text-rose-700 text-[11px]">Kode tidak dapat digunakan. Periksa kembali kode.</span>
                    </div>
                </div>
            </div>

            <p class="text-[11px] text-slate-400">Aturan kelayakan kode mengikuti pengaturan voucher resmi administrator FKKSM.</p>
        </div>
    </div>

    @if (auth()->user()->isAdmin())
        <!-- Admin: Create Voucher & Manage Registered Vouchers -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 space-y-5">
            <h3 class="font-semibold text-base text-slate-900 pb-3 border-b border-slate-100">+ Buat Kode Voucher Baru (Admin)</h3>
            <form action="{{ route('lms.vouchers.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Kode Voucher *</label>
                    <input type="text" name="code" required placeholder="MISAL: MM2100BEBAS" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 uppercase font-mono">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Judul / Deskripsi *</label>
                    <input type="text" name="title" required placeholder="Akses Gratis Anggota MM2100" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tipe Benefit</label>
                    <select name="benefit_type" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-white">
                        <option value="free_access">Akses Gratis Penuh</option>
                        <option value="discount">Potongan Harga</option>
                        <option value="program_access">Akses Khusus Program</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tautkan ke Kursus</label>
                    <select name="course_id" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-white">
                        <option value="">Semua Kursus</option>
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Kuota Maksimal (Opsional)</label>
                    <input type="number" name="max_uses" min="1" placeholder="Tanpa batas" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Berlaku Hingga</label>
                    <input type="date" name="expires_at" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                        Simpan Voucher
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200 space-y-4">
            <h3 class="font-semibold text-base text-slate-900 pb-3 border-b border-slate-100">Daftar Voucher Terdaftar</h3>
            <div class="divide-y divide-slate-100">
                @forelse ($vouchers as $v)
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="font-mono font-bold text-[#0E97A8] text-sm mr-2">{{ $v->code }}</span>
                            <span class="font-semibold text-slate-900">{{ $v->title }}</span>
                            <p class="text-slate-500 text-[11px] mt-0.5 font-mono">
                                Tipe: {{ $v->benefit_type }} &bull; Ditukarkan: {{ $v->redemptions_count }} kali
                                @if ($v->expires_at)
                                    &bull; Exp: {{ $v->expires_at->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-medium {{ $v->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ strtoupper($v->status) }}
                            </span>
                            <form action="{{ route('lms.vouchers.toggle', $v) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-[11px] font-semibold text-slate-700">
                                    {{ $v->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada voucher dibuat.</p>
                @endforelse
            </div>
            @if ($vouchers->hasPages())
                <div class="pt-3">{{ $vouchers->links() }}</div>
            @endif
        </div>
    @else
        <!-- Student History of Redeemed Vouchers & Active Promos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                <h3 class="font-semibold text-base text-slate-900">Riwayat Penukaran Anda</h3>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse ($redemptions as $red)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div>
                                <span class="font-mono font-bold text-[#0E97A8]">{{ $red->voucher->code }}</span>
                                <p class="font-medium text-slate-800">{{ $red->voucher->title }}</p>
                                @if ($red->voucher->course)
                                    <span class="text-[11px] text-slate-500">Kursus: {{ $red->voucher->course->title }}</span>
                                @endif
                            </div>
                            <span class="font-mono text-[11px] text-slate-400 shrink-0">{{ $red->redeemed_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <p class="py-6 text-center text-slate-400">Belum ada voucher yang Anda tukarkan.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                <h3 class="font-semibold text-base text-slate-900">Promo Voucher Aktif Tersedia</h3>
                <div class="space-y-2.5 text-xs">
                    @forelse ($activePromos as $promo)
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3">
                            <div>
                                <span class="font-mono font-bold text-[#0E97A8]">{{ $promo->code }}</span>
                                <p class="text-slate-700 font-medium">{{ $promo->title }}</p>
                            </div>
                            <button type="button" onclick="fillAndCheck('{{ $promo->code }}')" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:border-[#10A9BC] text-[11px] font-semibold text-slate-700">
                                Pakai
                            </button>
                        </div>
                    @empty
                        <p class="py-6 text-center text-slate-400">Belum ada promo aktif saat ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function fillAndCheck(code) {
        const input = document.getElementById('voucherCodeInput');
        input.value = code;
        checkVoucherCode();
    }

    async function checkVoucherCode() {
        const code = document.getElementById('voucherCodeInput').value.trim();
        const feedback = document.getElementById('checkFeedbackMsg');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Reset highlight borders on status cards
        ['available', 'used', 'expired', 'invalid'].forEach(st => {
            const card = document.getElementById('statusCard-' + st);
            if (card) card.classList.remove('ring-2', 'ring-[#10A9BC]', 'ring-offset-1');
        });

        if (!code) {
            window.fkksmModal({
                icon: 'warning',
                title: 'Kode Belum Diisi',
                text: 'Masukkan kode voucher atau token pelatihan terlebih dahulu.',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        try {
            const res = await fetch('{{ route('lms.vouchers.check') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ code })
            });
            const data = await res.json();

            document.getElementById('previewBenefitType').textContent = data.benefit_type_label || '—';
            document.getElementById('previewProgram').textContent = data.program || '—';
            document.getElementById('previewExpiry').textContent = data.expires_at || '—';
            feedback.textContent = data.message || '';

            const targetCard = document.getElementById('statusCard-' + data.status);
            if (targetCard) {
                targetCard.classList.add('ring-2', 'ring-[#10A9BC]', 'ring-offset-1');
            }

            const iconMap = {
                available: 'success',
                used: 'info',
                expired: 'warning',
                invalid: 'error'
            };
            const titleMap = {
                available: 'Kode Voucher Tersedia',
                used: 'Voucher Sudah Digunakan',
                expired: 'Masa Berlaku Berakhir',
                invalid: 'Kode Voucher Tidak Ditemukan'
            };
            window.fkksmModal({
                icon: iconMap[data.status] || 'info',
                title: titleMap[data.status] || 'Status Voucher',
                text: data.message || 'Pemeriksaan kode voucher selesai.',
                confirmButtonText: 'Oke'
            });
        } catch (err) {
            feedback.textContent = 'Gagal memeriksa kode. Silakan coba lagi.';
            window.fkksmModal({
                icon: 'error',
                title: 'Gagal Memeriksa Kode',
                text: 'Terjadi gangguan jaringan saat memeriksa kode voucher. Silakan coba lagi.',
                confirmButtonText: 'Tutup'
            });
        }
    }
</script>
@endpush
@endsection

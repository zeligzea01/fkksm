@extends('layouts.lms')

@section('title', 'Subscription & Paket Layanan | FKKSM LMS')
@section('header_eyebrow', '4. Subscription & Commercial Readiness')
@section('header_title', 'Subscription')

@section('content')
<div class="space-y-6">
    <!-- Top Section Label matching Figma Layar 4A (page_0.png) -->
    <div class="flex items-center justify-between text-xs text-slate-500">
        <span>4. Subscription &amp; Commercial Readiness</span>
        <span class="font-mono uppercase text-slate-400">LAYAR 4A</span>
    </div>

    <!-- Hero Banner matching Figma Layar 4A -->
    <div class="bg-lms-hero rounded-xl p-6 sm:p-8 text-white flex items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <h2 class="text-2xl font-bold tracking-tight">Subscription &amp; Commercial Readiness</h2>
            <p class="text-xs sm:text-sm text-white/85 leading-relaxed">
                Pilihan akses gratis dan berbayar berdasarkan periode atau paket layanan pelatihan FKKSM MM2100.
            </p>
        </div>

        <div class="hidden sm:flex items-center justify-center shrink-0">
            <div class="w-16 h-16 rounded-xl bg-white/15 border border-white/25 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>

    @if (!auth()->user()->isAdmin())
        <!-- 3-Column Student Cards matching Figma Layar 4A -->
        @php
            $featuredPaid = $paidPlans->first();
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Card 1: Akses gratis -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between gap-6">
                <div class="space-y-4">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Akses gratis</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            {{ $freePlan->description ?? 'Akses program dasar komunitas K3, seminar reguler, dan materi pengenalan FKKSM.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 space-y-2.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jenis akses</span>
                            <span class="font-semibold text-slate-900">Gratis</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Masa aktif</span>
                            <span class="font-mono font-semibold text-slate-900">{{ $freePlan ? $freePlan->duration_days . ' Hari' : '365 Hari' }}</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('lms.subscriptions.subscribe') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mode" value="free">
                    @if ($freePlan)
                        <input type="hidden" name="plan_id" value="{{ $freePlan->id }}">
                    @endif
                    <button type="submit" class="w-full py-2.5 rounded-lg border border-[#10A9BC] text-[#0E97A8] hover:bg-[#E7F6F8] font-semibold text-xs lms-transition">
                        Pilih akses gratis
                    </button>
                </form>
            </div>

            <!-- Card 2: Akses berbayar -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between gap-6">
                <div class="space-y-4">
                    <div class="w-9 h-9 rounded-lg bg-[#E7F6F8] text-[#0E97A8] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Akses berbayar</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            {{ $featuredPaid->description ?? 'Akses penuh seluruh kursus bersertifikat SKKNI/BNSP, kuis evaluasi, dan sertifikat digital.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 space-y-2.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Paket layanan</span>
                            <span class="font-semibold text-slate-900">{{ $featuredPaid->name ?? 'Paket Pro Kompetensi' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Periode</span>
                            <span class="font-mono font-semibold text-slate-900">{{ $featuredPaid ? $featuredPaid->duration_days . ' Hari' : '30 Hari' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Harga</span>
                            <span class="font-mono font-bold text-[#0E97A8]">
                                {{ $featuredPaid ? 'Rp ' . number_format((float)$featuredPaid->price, 0, ',', '.') : 'Rp 149.000' }}
                            </span>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="document.getElementById('planPickerModal').classList.remove('hidden')" class="w-full py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                    Pilih paket layanan
                </button>
            </div>

            <!-- Card 3: Subscription pengguna -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between gap-6">
                <div class="space-y-4">
                    <h3 class="font-bold text-lg text-slate-900">Subscription pengguna</h3>

                    <div class="space-y-3 text-xs pt-2">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Paket aktif</span>
                            <span class="font-semibold text-slate-900">{{ $activeSubscription?->plan?->name ?? 'Belum aktif' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Masa aktif</span>
                            <span class="font-mono font-semibold text-slate-900">
                                {{ $activeSubscription?->ends_at ? $activeSubscription->ends_at->format('d M Y') : '—' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Status akses</span>
                            @if ($activeSubscription)
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-[11px]">Aktif</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold text-[11px]">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-2.5">
                    @if ($activeSubscription)
                        <form action="{{ route('lms.subscriptions.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="mode" value="renew">
                            <button type="submit" class="w-full py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs inline-flex items-center justify-center gap-2 lms-transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Perpanjang akses</span>
                            </button>
                        </form>
                        <form action="{{ route('lms.subscriptions.cancel', $activeSubscription) }}" method="POST" data-confirm="Hentikan paket layanan saat ini?" data-confirm-title="Hentikan Paket Layanan" data-confirm-btn="Ya, Hentikan">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-lg border border-slate-200 hover:bg-rose-50 hover:border-rose-200 text-slate-700 hover:text-rose-700 font-semibold text-xs lms-transition">
                                Hentikan akses
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="document.getElementById('planPickerModal').classList.remove('hidden')" class="w-full py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                            Aktifkan Paket Sekarang
                        </button>
                    @endif
                    <p class="text-[11px] text-slate-400 leading-relaxed pt-1">
                        Aktivasi atau perpanjangan akses tercatat otomatis ke riwayat akun Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Picker for Paid Plans -->
        <div id="planPickerModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-base text-slate-900">Pilih Paket Layanan Berbayar</h3>
                    <button type="button" onclick="document.getElementById('planPickerModal').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="space-y-3">
                    @foreach ($plans as $plan)
                        <div class="p-4 rounded-xl border border-slate-200 hover:border-[#10A9BC] flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-sm text-slate-900">{{ $plan->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $plan->description }}</p>
                                <span class="inline-block mt-1.5 font-mono text-xs font-bold text-[#0E97A8]">
                                    {{ (float)$plan->price <= 0 ? 'GRATIS' : 'Rp ' . number_format((float)$plan->price, 0, ',', '.') }} &bull; {{ $plan->duration_days }} Hari
                                </span>
                            </div>
                            <form action="{{ route('lms.subscriptions.subscribe') }}" method="POST" class="shrink-0">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs">
                                    Aktifkan
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- Admin Management Panel: Create Plans & Manage User Subscriptions -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 p-6 space-y-5">
                <h3 class="font-semibold text-base text-slate-900 pb-3 border-b border-slate-100">+ Tambah Paket Subscription</h3>
                <form action="{{ route('lms.subscriptions.plans.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Nama Paket *</label>
                        <input type="text" name="name" required placeholder="Misal: Paket Industri Korporat" class="w-full px-3.5 py-2 rounded-lg border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Deskripsi Singkat</label>
                        <textarea name="description" rows="2" class="w-full px-3.5 py-2 rounded-lg border border-slate-200"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Harga (Rp) *</label>
                            <input type="number" name="price" required min="0" value="0" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Durasi (Hari) *</label>
                            <input type="number" name="duration_days" required min="1" value="30" class="w-full px-3.5 py-2 rounded-lg border border-slate-200 font-mono">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs">
                        Simpan Paket Baru
                    </button>
                </form>

                <div class="pt-4 border-t border-slate-100 space-y-2.5">
                    <h4 class="font-semibold text-xs uppercase tracking-wider text-slate-500">Daftar Paket Aktif</h4>
                    @foreach ($allPlans as $ap)
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-semibold text-slate-900 block">{{ $ap->name }}</span>
                                <span class="font-mono text-[11px] text-slate-500">Rp {{ number_format((float)$ap->price, 0, ',', '.') }} / {{ $ap->duration_days }}hr &bull; {{ $ap->subscriptions_count }} pengguna</span>
                            </div>
                            <form action="{{ route('lms.subscriptions.plans.toggle', $ap) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded border border-slate-200 bg-white text-[11px] font-medium {{ $ap->status === 'active' ? 'text-emerald-700' : 'text-slate-500' }}">
                                    {{ strtoupper($ap->status) }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                <h3 class="font-semibold text-base text-slate-900 pb-3 border-b border-slate-100">Subscription Pengguna Terdaftar</h3>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse ($subscriptions as $sub)
                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $sub->user->name ?? 'Pengguna' }} <span class="text-slate-400 font-mono font-normal">({{ $sub->user->email ?? '' }})</span></p>
                                <p class="text-[#0E97A8] font-medium mt-0.5">{{ $sub->plan->name ?? 'Paket' }}</p>
                                <span class="font-mono text-[11px] text-slate-500">
                                    Berlaku s/d: {{ $sub->ends_at ? $sub->ends_at->format('d M Y') : '—' }} &bull; Status: <strong>{{ strtoupper($sub->status) }}</strong>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form action="{{ route('lms.subscriptions.extend', $sub) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#E7F6F8] text-[#0E97A8] hover:bg-[#10A9BC] hover:text-white font-semibold text-[11px] lms-transition">
                                        + Perpanjang
                                    </button>
                                </form>
                                @if ($sub->status === 'active')
                                    <form action="{{ route('lms.subscriptions.cancel', $sub) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 text-rose-600 hover:bg-rose-50 font-semibold text-[11px]">
                                            Hentikan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="py-8 text-center text-slate-400">Belum ada subscription pengguna tercatat.</p>
                    @endforelse
                </div>
                @if ($subscriptions->hasPages())
                    <div class="pt-3">{{ $subscriptions->links() }}</div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection


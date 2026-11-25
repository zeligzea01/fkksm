@extends('layouts.lms')

@section('title', 'Voucher Promo & Akses Pelatihan | FKKSM MM2100')
@section('page_title', 'Voucher & Akses Pelatihan')
@section('page_subtitle', 'Tukarkan kode voucher sponsor industri atau kelola kupon akses.')

@section('content')
<div class="space-y-8">
    <!-- Student Redeem Voucher Card -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
        <div class="max-w-2xl">
            <span class="px-2.5 py-1 rounded text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30 uppercase tracking-wider">
                Klaim Akses Pelatihan
            </span>
            <h3 class="text-xl sm:text-2xl font-black mt-2 mb-2">Punya Kode Voucher dari Perusahaan?</h3>
            <p class="text-xs text-blue-200 leading-relaxed mb-6">
                Masukkan kode voucher yang diberikan oleh manajemen HR pabrik anggota FKKSM untuk mendapatkan akses gratis ke kursus bersertifikasi. (Contoh kode demo: <code class="font-mono bg-blue-800/80 px-1.5 py-0.5 rounded text-white font-bold">FKKSM2026</code>)
            </p>

            <form action="{{ route('lms.vouchers.redeem') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="code" required placeholder="MASUKKAN KODE VOUCHER" class="px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-300 text-xs font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1">
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-500 hover:bg-blue-400 text-slate-950 font-black text-xs transition shrink-0">
                    Klaim Voucher Sekarang →
                </button>
            </form>
        </div>
    </div>

    @if (auth()->user()->isAdmin())
        <!-- Admin: Create Voucher -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <h3 class="font-bold text-base text-slate-900 mb-4 pb-2 border-b border-slate-100">+ Buat Kode Voucher Baru</h3>
            <form action="{{ route('lms.vouchers.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Voucher *</label>
                    <input type="text" name="code" required placeholder="MISAL: MM2100BEBAS" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs uppercase font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul / Deskripsi</label>
                    <input type="text" name="title" required placeholder="Akses Gratis Anggota MM2100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Benefit</label>
                    <select name="benefit_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                        <option value="free_access">Akses Gratis Penuh</option>
                        <option value="discount">Potongan Harga</option>
                        <option value="program_access">Akses Khusus Program</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tautkan ke Kursus</label>
                    <select name="course_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                        <option value="">Semua Kursus</option>
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                        Simpan Voucher →
                    </button>
                </div>
            </form>
        </div>

        <!-- Admin: List Vouchers -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <h3 class="font-bold text-base text-slate-900 mb-4 pb-3 border-b border-slate-100">Daftar Voucher Terdaftar</h3>
            <div class="divide-y divide-slate-100">
                @forelse ($vouchers as $v)
                    <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs">
                        <div>
                            <span class="font-mono font-bold text-blue-600 text-sm mr-2">{{ $v->code }}</span>
                            <span class="font-bold text-slate-900">{{ $v->title }}</span>
                            <p class="text-slate-500 text-[11px] mt-0.5">Tipe: {{ $v->benefit_type }} • Ditukarkan: {{ $v->redemptions_count }} kali</p>
                        </div>
                        <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $v->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ strtoupper($v->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada voucher yang dibuat.</p>
                @endforelse
            </div>
            <div class="pt-4">
                {{ $vouchers->links() }}
            </div>
        </div>
    @endif
</div>
@endsection


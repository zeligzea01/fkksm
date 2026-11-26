@extends('layouts.lms')

@section('title', 'Data Calon Peserta & Leads | FKKSM Admin')
@section('header_eyebrow', 'Manajemen Permintaan & Prospek')
@section('header_title', 'Kelola Calon Peserta (Leads)')

@section('content')
<div class="space-y-6">
    <!-- Status Filter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <a href="{{ route('lms.leads.index', ['status' => 'all']) }}" class="p-4 rounded-xl border {{ $status === 'all' || !$status ? 'border-[#10A9BC] bg-[#E7F6F8]/70' : 'border-slate-200 bg-white' }} text-center lms-transition">
            <span class="text-xs text-slate-500 block">Semua</span>
            <span class="font-mono tabular-nums text-xl font-bold text-slate-900 mt-1 block">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'new']) }}" class="p-4 rounded-xl border {{ $status === 'new' ? 'border-amber-500 bg-amber-50' : 'border-slate-200 bg-white' }} text-center lms-transition">
            <span class="text-xs text-amber-700 font-medium block">Baru</span>
            <span class="font-mono tabular-nums text-xl font-bold text-amber-700 mt-1 block">{{ $counts['new'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'contacted']) }}" class="p-4 rounded-xl border {{ $status === 'contacted' ? 'border-[#0F766E] bg-teal-50' : 'border-slate-200 bg-white' }} text-center lms-transition">
            <span class="text-xs text-[#0E97A8] font-medium block">Dihubungi</span>
            <span class="font-mono tabular-nums text-xl font-bold text-[#0E97A8] mt-1 block">{{ $counts['contacted'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'qualified']) }}" class="p-4 rounded-xl border {{ $status === 'qualified' ? 'border-[#10A9BC] bg-[#E7F6F8]' : 'border-slate-200 bg-white' }} text-center lms-transition">
            <span class="text-xs text-[#0E97A8] font-medium block">Kualifikasi</span>
            <span class="font-mono tabular-nums text-xl font-bold text-[#0E97A8] mt-1 block">{{ $counts['qualified'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'converted']) }}" class="p-4 rounded-xl border {{ $status === 'converted' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white' }} text-center lms-transition">
            <span class="text-xs text-emerald-700 font-medium block">Bergabung</span>
            <span class="font-mono tabular-nums text-xl font-bold text-emerald-700 mt-1 block">{{ $counts['converted'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'closed']) }}" class="p-4 rounded-xl border {{ $status === 'closed' ? 'border-slate-500 bg-slate-100' : 'border-slate-200 bg-white' }} text-center lms-transition">
            <span class="text-xs text-slate-600 font-medium block">Ditutup</span>
            <span class="font-mono tabular-nums text-xl font-bold text-slate-700 mt-1 block">{{ $counts['closed'] }}</span>
        </a>
    </div>

    <!-- Leads List -->
    <div class="bg-white rounded-xl p-6 border border-slate-200">
        <div class="divide-y divide-slate-100">
            @forelse ($leads as $lead)
                <div class="py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-xs">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-900 text-sm">{{ $lead->full_name }}</span>
                            <span class="px-2 py-0.5 rounded font-mono text-[10px] font-semibold {{ $lead->status === 'new' ? 'bg-amber-100 text-amber-800' : ($lead->status === 'converted' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                {{ $lead->status_label }}
                            </span>
                        </div>
                        <p class="text-slate-500 font-mono">
                            {{ $lead->email ?? '—' }} &bull; {{ $lead->phone ?? '—' }}
                        </p>
                        <p class="text-[#0E97A8] font-medium">Minat: {{ $lead->interest }}</p>
                        @if ($lead->notes)
                            <p class="text-slate-600 text-[11px] bg-slate-50 p-2.5 rounded-lg border border-slate-200/70 mt-1">{{ $lead->notes }}</p>
                        @endif
                        <span class="text-[10px] font-mono text-slate-400 block">Sumber: {{ $lead->source }} &bull; {{ $lead->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <form action="{{ route('lms.leads.update-status', $lead) }}" method="POST" class="flex items-center gap-2 shrink-0">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs bg-white">
                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Sudah Dihubungi</option>
                            <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>Kualifikasi</option>
                            <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Dikonversi / Bergabung</option>
                            <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                        <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold text-xs lms-transition">
                            Simpan
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-8 text-center">Belum ada data calon peserta / leads yang masuk.</p>
            @endforelse
        </div>

        @if ($leads->hasPages())
            <div class="pt-4">{{ $leads->links() }}</div>
        @endif
    </div>
</div>
@endsection

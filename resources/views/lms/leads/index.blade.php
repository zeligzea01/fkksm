@extends('layouts.lms')

@section('title', 'Data Calon Peserta & Leads | FKKSM MM2100')
@section('page_title', 'Kelola Calon Peserta (Leads)')
@section('page_subtitle', 'Daftar permohonan keanggotaan dan pelatihan yang masuk dari portal publik.')

@section('content')
<div class="space-y-6">
    <!-- Status Filter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <a href="{{ route('lms.leads.index', ['status' => 'all']) }}" class="p-4 rounded-2xl border {{ $status === 'all' || !$status ? 'border-blue-500 bg-blue-50' : 'border-slate-200 bg-white' }} text-center transition">
            <span class="text-xs text-slate-500 block">Semua</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'new']) }}" class="p-4 rounded-2xl border {{ $status === 'new' ? 'border-amber-500 bg-amber-50' : 'border-slate-200 bg-white' }} text-center transition">
            <span class="text-xs text-amber-600 font-semibold block">Baru</span>
            <span class="text-xl font-black text-amber-600 mt-1 block">{{ $counts['new'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'contacted']) }}" class="p-4 rounded-2xl border {{ $status === 'contacted' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 bg-white' }} text-center transition">
            <span class="text-xs text-blue-600 font-semibold block">Dihubungi</span>
            <span class="text-xl font-black text-blue-600 mt-1 block">{{ $counts['contacted'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'qualified']) }}" class="p-4 rounded-2xl border {{ $status === 'qualified' ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white' }} text-center transition">
            <span class="text-xs text-purple-600 font-semibold block">Kualifikasi</span>
            <span class="text-xl font-black text-purple-600 mt-1 block">{{ $counts['qualified'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'converted']) }}" class="p-4 rounded-2xl border {{ $status === 'converted' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white' }} text-center transition">
            <span class="text-xs text-emerald-600 font-semibold block">Bergabung</span>
            <span class="text-xl font-black text-emerald-600 mt-1 block">{{ $counts['converted'] }}</span>
        </a>
        <a href="{{ route('lms.leads.index', ['status' => 'closed']) }}" class="p-4 rounded-2xl border {{ $status === 'closed' ? 'border-slate-500 bg-slate-100' : 'border-slate-200 bg-white' }} text-center transition">
            <span class="text-xs text-slate-600 font-semibold block">Ditutup</span>
            <span class="text-xl font-black text-slate-700 mt-1 block">{{ $counts['closed'] }}</span>
        </a>
    </div>

    <!-- Table of Leads -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="divide-y divide-slate-100">
            @forelse ($leads as $lead)
                <div class="py-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-xs">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900 text-sm">{{ $lead->full_name }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $lead->status === 'new' ? 'bg-amber-100 text-amber-800' : ($lead->status === 'converted' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                {{ $lead->status_label }}
                            </span>
                        </div>
                        <p class="text-slate-500 text-xs">
                            Email: <strong class="text-slate-700">{{ $lead->email ?? '—' }}</strong> • Telp: <strong class="text-slate-700">{{ $lead->phone ?? '—' }}</strong>
                        </p>
                        <p class="text-blue-600 font-medium">Minat: {{ $lead->interest }}</p>
                        @if ($lead->notes)
                            <p class="text-slate-600 text-[11px] bg-slate-50 p-2 rounded-lg border border-slate-100 mt-1">{{ $lead->notes }}</p>
                        @endif
                        <span class="text-[10px] text-slate-400 block">Sumber: {{ $lead->source }} • Dicatat: {{ $lead->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <!-- Quick Status Change Form -->
                    <form action="{{ route('lms.leads.update-status', $lead) }}" method="POST" class="flex items-center gap-2 shrink-0">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs bg-white">
                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Sudah Dihubungi</option>
                            <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>Kualifikasi</option>
                            <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Dikonversi / Bergabung</option>
                            <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition">
                            Ubah
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-8 text-center">Belum ada data calon peserta / leads yang masuk.</p>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $leads->links() }}
        </div>
    </div>
</div>
@endsection


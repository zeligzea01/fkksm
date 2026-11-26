@extends('layouts.lms')

@section('title', 'Sistem Audit & Log Keamanan | FKKSM Admin')
@section('header_eyebrow', 'Pengawasan & Keamanan Sistem')
@section('header_title', 'Audit Trail & Log Aktivitas')

@section('content')
<div class="space-y-6">
    <!-- Audit Security Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="p-4 rounded-xl bg-white border border-slate-200">
            <span class="text-xs font-medium text-slate-500 block">Total Aktivitas</span>
            <span class="font-mono tabular-nums font-bold text-2xl text-slate-900 mt-1 block">{{ $stats['total_logs'] }}</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200">
            <span class="text-xs font-medium text-slate-500 block">Hari Ini</span>
            <span class="font-mono tabular-nums font-bold text-2xl text-[#0E97A8] mt-1 block">{{ $stats['today_logs'] }}</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200">
            <span class="text-xs font-medium text-slate-500 block">Pengguna Unik</span>
            <span class="font-mono tabular-nums font-bold text-2xl text-emerald-700 mt-1 block">{{ $stats['unique_users'] }}</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200">
            <span class="text-xs font-medium text-slate-500 block">Login Gagal</span>
            <span class="font-mono tabular-nums font-bold text-2xl text-rose-600 mt-1 block">{{ $stats['failed_logins'] }}</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200">
            <span class="text-xs font-medium text-slate-500 block">Event Kritis</span>
            <span class="font-mono tabular-nums font-bold text-2xl text-[#0E97A8] mt-1 block">{{ $stats['security_events'] }}</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200">
        <form action="{{ route('lms.audit.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari IP, aksi, atau detail..." class="px-3.5 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:border-[#10A9BC]">
            <select name="action" class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white">
                <option value="all">Semua Jenis Aksi</option>
                @foreach ($actions as $act)
                    <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ $act }}</option>
                @endforeach
            </select>
            <select name="user_id" class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white">
                <option value="all">Semua Pengguna</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2 col-span-1 lg:col-span-2">
                <button type="submit" class="flex-1 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold lms-transition">
                    Saring Log Audit
                </button>
                <a href="{{ route('lms.audit.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[11px] font-semibold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Waktu (WIB)</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Aksi / Event</th>
                        <th class="py-3 px-4">Keterangan Aktivitas</th>
                        <th class="py-3 px-4">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-[11px] text-slate-500 tabular-nums">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-900 block">{{ $log->user->name ?? 'Sistem Publik' }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $log->user->email ?? '—' }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-[10px] font-semibold uppercase bg-[#E7F6F8] text-[#0E97A8]">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-700 max-w-md">
                                {{ $log->details }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-[11px] text-slate-500 tabular-nums">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-xs text-slate-400">
                                Tidak ada log audit yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.lms')

@section('title', 'Sistem Audit & Log Keamanan | FKKSM MM2100')
@section('header_title', 'SISTEM AUDIT TRAIL & LOG KEAMANAN')

@section('content')
<div class="space-y-6">
    <!-- Audit Security Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
            <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">TOTAL AKTIVITAS</span>
            <span class="font-heading font-bold text-3xl text-[#132B45] mt-1 block leading-none">{{ $stats['total_logs'] }}</span>
            <span class="text-[10px] text-[#61758A] font-mono">Terekam di Basis Data</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
            <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">AKTIVITAS HARI INI</span>
            <span class="font-heading font-bold text-3xl text-[#10A9BC] mt-1 block leading-none">{{ $stats['today_logs'] }}</span>
            <span class="text-[10px] text-[#61758A] font-mono">Sesi Operasional</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
            <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">AKTOR PENGGUNA UNIK</span>
            <span class="font-heading font-bold text-3xl text-emerald-600 mt-1 block leading-none">{{ $stats['unique_users'] }}</span>
            <span class="text-[10px] text-emerald-700 font-mono">Akun Terverifikasi</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
            <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">LOGIN GAGAL</span>
            <span class="font-heading font-bold text-3xl text-rose-600 mt-1 block leading-none">{{ $stats['failed_logins'] }}</span>
            <span class="text-[10px] text-rose-600 font-mono font-medium">Pemeriksaan Keamanan</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
            <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">EVENT DATA KRITIS</span>
            <span class="font-heading font-bold text-3xl text-[#123A5A] mt-1 block leading-none">{{ $stats['security_events'] }}</span>
            <span class="text-[10px] text-[#123A5A] font-mono font-medium">Audit Mutasi</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-xl border border-[#DCE6EE] shadow-xs">
        <form action="{{ route('lms.audit.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari IP, aksi, atau detail..." class="w-full px-3.5 py-2 rounded-lg border border-[#DCE6EE] text-xs bg-[#F5F8FC] focus:outline-none focus:border-[#10A9BC]">
            </div>
            <div>
                <select name="action" class="w-full px-3.5 py-2 rounded-lg border border-[#DCE6EE] text-xs bg-[#F5F8FC] focus:outline-none focus:border-[#10A9BC]">
                    <option value="all">Semua Jenis Aksi</option>
                    @foreach ($actions as $act)
                        <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="user_id" class="w-full px-3.5 py-2 rounded-lg border border-[#DCE6EE] text-xs bg-[#F5F8FC] focus:outline-none focus:border-[#10A9BC]">
                    <option value="all">Semua Pengguna</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 col-span-1 lg:col-span-2">
                <button type="submit" class="flex-1 py-2 rounded-lg bg-gradient-to-r from-[#118DAB] to-[#13AEB9] hover:brightness-105 text-white font-heading font-bold uppercase tracking-wider text-xs transition">
                    Saring Log Audit
                </button>
                <a href="{{ route('lms.audit.index') }}" class="px-4 py-2 rounded-lg bg-[#F5F8FC] border border-[#DCE6EE] text-[#132B45] font-heading font-bold uppercase text-xs hover:bg-[#D8EDF7] transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-xl border border-[#DCE6EE] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#132B45] text-white uppercase text-[10px] font-heading tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Waktu (WIB)</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Aksi / Event</th>
                        <th class="py-3 px-4">Keterangan Aktivitas</th>
                        <th class="py-3 px-4">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DCE6EE]">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-[#F5F8FC] transition">
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-[11px] text-[#61758A]">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-bold text-[#132B45] block">{{ $log->user->name ?? 'Sistem Publik' }}</span>
                                <span class="text-[10px] text-[#61758A] font-mono">{{ $log->user->email ?? '—' }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-[#D8EDF7] text-[#123A5A]">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#132B45] max-w-md">
                                {{ $log->details }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-[11px] text-[#61758A]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-xs text-[#61758A]">
                                Tidak ada log audit yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="p-4 border-t border-[#DCE6EE]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

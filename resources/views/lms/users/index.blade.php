@extends('layouts.lms')

@section('title', 'Manajemen Pengguna & Peran | FKKSM Admin')
@section('header_eyebrow', 'Kontrol Akses Pengguna')
@section('header_title', 'Kelola Pengguna Sistem')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl p-6 border border-slate-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-semibold text-base text-slate-900">Daftar Akun Pengguna</h3>
                <p class="text-xs text-slate-500">Kelola status aktif/nonaktif dan hak akses pengguna FKKSM MM2100.</p>
            </div>
            <form action="{{ route('lms.users.index') }}" method="GET" class="flex items-center gap-2 text-xs">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / email..." class="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50">
                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#10A9BC] text-white font-semibold">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="pb-3 pr-4">Nama Lengkap &amp; Email</th>
                        <th class="pb-3 px-4">Username</th>
                        <th class="pb-3 px-4">Peran (Role)</th>
                        <th class="pb-3 px-4">Status</th>
                        <th class="pb-3 px-4">Kursus &amp; Sertifikat</th>
                        <th class="pb-3 pl-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3.5 pr-4">
                                <span class="font-semibold text-slate-900 block">{{ $user->name }}</span>
                                <span class="text-slate-500 text-[11px] font-mono">{{ $user->email }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $user->username ?? '—' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded font-mono text-[10px] font-semibold uppercase {{ $user->role === 'super_admin' ? 'bg-[#E7F6F8] text-[#0E97A8]' : ($user->role === 'instructor' ? 'bg-amber-50 text-amber-800' : 'bg-teal-50 text-[#0E97A8]') }}">
                                    {{ $user->role_name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded font-mono text-[10px] font-semibold {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ strtoupper($user->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono tabular-nums text-slate-500">
                                {{ $user->enrollments_count }} Kursus &bull; {{ $user->certificates_count }} Sertifikat
                            </td>
                            <td class="py-3.5 pl-4 text-right">
                                @if ($user->role !== 'super_admin' && $user->id !== auth()->id())
                                    <form action="{{ route('lms.users.toggle-status', $user) }}" method="POST" class="inline" data-confirm="Ubah status akun {{ $user->name }}?" data-confirm-title="Konfirmasi Status Akun" data-confirm-btn="Ya, Ubah">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 text-[11px] font-semibold text-slate-700 hover:bg-slate-50">
                                            {{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] font-mono text-slate-400">Terlindungi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="pt-5 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

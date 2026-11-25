@extends('layouts.lms')

@section('title', 'Manajemen Pengguna & Peran | FKKSM MM2100')
@section('page_title', 'Kelola Pengguna Sistem')
@section('page_subtitle', 'Manajemen akses Administrator, Instruktur, Perusahaan MM2100, dan Peserta.')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-base text-slate-900">Daftar Akun Pengguna</h3>
                <p class="text-xs text-slate-500">Kelola status aktif/nonaktif dan hak akses pengguna.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3 pr-4">Nama Lengkap & Email</th>
                        <th class="pb-3 px-4">Username</th>
                        <th class="pb-3 px-4">Peran (Role)</th>
                        <th class="pb-3 px-4">Status</th>
                        <th class="pb-3 px-4">Kursus & Sertifikat</th>
                        <th class="pb-3 pl-4 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 pr-4">
                                <span class="font-bold text-slate-900 block">{{ $user->name }}</span>
                                <span class="text-slate-500 text-[11px]">{{ $user->email }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $user->username ?? '—' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'instructor' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $user->role_name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ strtoupper($user->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $user->enrollments_count }} Kursus • {{ $user->certificates_count }} Sertifikat
                            </td>
                            <td class="py-3.5 pl-4 text-right">
                                @if ($user->role !== 'super_admin' && $user->id !== auth()->id())
                                    <form action="{{ route('lms.users.toggle-status', $user) }}" method="POST" class="inline" onsubmit="return confirm('Ubah status akun ini?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 text-[11px] font-semibold text-slate-700 hover:bg-slate-100 transition">
                                            {{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-400 font-medium">Terlindungi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-6 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection


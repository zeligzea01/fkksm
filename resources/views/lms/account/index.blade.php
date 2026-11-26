@extends('layouts.lms')

@section('title', 'Pengaturan Akun | FKKSM LMS')
@section('header_eyebrow', 'Preferensi Pengguna')
@section('header_title', 'Pengaturan Akun & Keamanan')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
    <!-- Profile Form -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="font-semibold text-base text-slate-900">Informasi Profil</h3>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui nama lengkap (tertera pada sertifikat digital) dan alamat email Anda.</p>
        </div>

        <form action="{{ route('lms.account.profile') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC]">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Alamat Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC]">
                </div>
            </div>
            <div class="flex items-center justify-between pt-2">
                <span class="font-mono text-[11px] text-slate-400">Username: {{ $user->username }} &bull; Peran: {{ $user->role_name }}</span>
                <button type="submit" class="px-5 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-semibold lms-transition">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="font-semibold text-base text-slate-900">Ubah Kata Sandi</h3>
            <p class="text-xs text-slate-500 mt-0.5">Gunakan kata sandi minimal 8 karakter untuk menjaga keamanan akun.</p>
        </div>

        <form action="{{ route('lms.account.password') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-medium text-slate-700 mb-1">Kata Sandi Saat Ini *</label>
                <input type="password" name="current_password" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC]">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Kata Sandi Baru *</label>
                    <input type="password" name="password" required minlength="8" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC]">
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Konfirmasi Kata Sandi Baru *</label>
                    <input type="password" name="password_confirmation" required minlength="8" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:border-[#10A9BC]">
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold lms-transition">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


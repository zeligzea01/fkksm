@extends('layouts.app')

@section('title', 'Pendaftaran Akun Baru | FKKSM MM2100 LMS')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-[#F5F8FC]">
    <div class="max-w-md w-full bg-white rounded-xl p-8 border border-[#DCE6EE] shadow-sm">
        <div class="text-center mb-8">
            <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="Logo FKKSM" class="h-12 w-auto mx-auto object-contain mb-3">
            <h1 class="font-heading font-extrabold text-3xl uppercase tracking-tight text-[#132B45]">
                DAFTAR AKUN BARU
            </h1>
            <p class="text-xs text-[#61758A] mt-1">Bergabung dengan platform pelatihan industri FKKSM MM2100</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Nama Lengkap &amp; Gelar *</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso, S.T." class="w-full px-4 py-2.5 rounded-lg border @error('name') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                @error('name')
                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Nama Pengguna (Username) *</label>
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="budisantoso" class="w-full px-4 py-2.5 rounded-lg border @error('username') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                @error('username')
                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Alamat Email Perusahaan / Pribadi *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="budi@perusahaan.co.id" class="w-full px-4 py-2.5 rounded-lg border @error('email') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                @error('email')
                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Kata Sandi (Min. 8 Karakter) *</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-lg border @error('password') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                @error('password')
                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Konfirmasi Kata Sandi *</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-lg border border-[#DCE6EE] focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
            </div>

            <button type="submit" class="w-full py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-sm uppercase tracking-wider shadow-sm transition mt-2">
                Daftar Akun Peserta →
            </button>
        </form>

        <p class="text-center text-xs text-[#61758A] mt-6">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-[#10A9BC] font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection

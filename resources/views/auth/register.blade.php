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

        <div class="mt-5">
            <div class="relative flex items-center justify-center my-4">
                <div class="border-t border-[#DCE6EE] w-full"></div>
                <span class="px-3 bg-white text-[10px] font-mono uppercase tracking-wider text-[#61758A] whitespace-nowrap">Atau Daftar Instan SSO</span>
                <div class="border-t border-[#DCE6EE] w-full"></div>
            </div>
            <a href="{{ route('sso.google.redirect') }}" class="w-full py-2.5 px-4 rounded-lg border border-[#DCE6EE] hover:border-[#10A9BC] bg-white hover:bg-[#F5F8FC] text-[#132B45] font-semibold text-xs flex items-center justify-center gap-2.5 transition shadow-2xs">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.14C3.26 21.3 7.31 24 12 24Z"/>
                    <path fill="#FBBC05" d="M5.28 14.24c-.24-.72-.38-1.49-.38-2.24s.14-1.52.38-2.24V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.99-3.14Z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.99 3.14c.95-2.85 3.6-4.96 6.72-4.96Z"/>
                </svg>
                <span>Lanjutkan dengan Google</span>
            </a>
        </div>

        <p class="text-center text-xs text-[#61758A] mt-6">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-[#10A9BC] font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection

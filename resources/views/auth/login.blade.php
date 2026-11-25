@extends('layouts.app')

@section('title', 'Masuk Akun | FKKSM MM2100 LMS')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-[#F5F8FC]">
    <div class="max-w-md w-full bg-white rounded-xl p-8 border border-[#DCE6EE] shadow-sm">
        <div class="text-center mb-8">
            <img src="{{ asset('fkksm/logo-transparent.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.jpeg') }}'" alt="Logo FKKSM" class="h-12 w-auto mx-auto object-contain mb-3">
            <h1 class="font-heading font-extrabold text-3xl uppercase tracking-tight text-[#132B45]">
                Masuk LMS FKKSM
            </h1>
            <p class="text-xs text-[#61758A] mt-1">Akses pelatihan, evaluasi K3, dan audit sertifikasi MM2100</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Email atau Username</label>
                <input id="loginInput" type="text" name="login" value="{{ old('login') }}" required autofocus placeholder="admin@fkksm.local" class="w-full px-4 py-2.5 rounded-lg border @error('login') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                @error('login')
                    <p class="mt-1 text-[11px] text-rose-600 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-[#132B45] uppercase tracking-wider mb-1">Kata Sandi</label>
                <input id="passwordInput" type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-lg border @error('password') border-rose-500 ring-1 ring-rose-500 @else border-[#DCE6EE] @enderror focus:outline-none focus:border-[#10A9BC] text-xs text-[#132B45] bg-[#F5F8FC]">
                @error('password')
                    <p class="mt-1 text-[11px] text-rose-600 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-[#61758A]">
                    <input type="checkbox" name="remember" class="rounded border-[#DCE6EE] text-[#10A9BC] focus:ring-[#10A9BC]">
                    <span>Ingat sesi saya</span>
                </label>
                <a href="{{ route('hubungi') }}" class="text-[#10A9BC] hover:underline font-semibold">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="w-full py-3 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-sm uppercase tracking-wider shadow-sm transition mt-2">
                Masuk ke Portal LMS →
            </button>
        </form>

        <!-- Quick Demo Switcher -->
        <div class="mt-8 pt-6 border-t border-[#DCE6EE]">
            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-[#61758A] text-center mb-3">
                AKUN DEMO PENGUJIAN CEPAT
            </span>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="fillCreds('admin@fkksm.local', 'AdminFKKSM2026!')" class="p-2 rounded-lg bg-[#F5F8FC] hover:bg-[#D8EDF7] hover:text-[#10A9BC] text-[#132B45] text-[11px] font-mono font-bold transition text-center border border-[#DCE6EE]">
                    Admin
                </button>
                <button type="button" onclick="fillCreds('hendra@fkksm.local', 'AdminFKKSM2026!')" class="p-2 rounded-lg bg-[#F5F8FC] hover:bg-[#D8EDF7] hover:text-[#10A9BC] text-[#132B45] text-[11px] font-mono font-bold transition text-center border border-[#DCE6EE]">
                    Instruktur
                </button>
                <button type="button" onclick="fillCreds('peserta@fkksm.local', 'AdminFKKSM2026!')" class="p-2 rounded-lg bg-[#F5F8FC] hover:bg-[#D8EDF7] hover:text-[#10A9BC] text-[#132B45] text-[11px] font-mono font-bold transition text-center border border-[#DCE6EE]">
                    Peserta
                </button>
            </div>
        </div>

        <p class="text-center text-xs text-[#61758A] mt-6">
            Belum memiliki akun? <a href="{{ route('register') }}" class="text-[#10A9BC] font-bold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>

<script>
    function fillCreds(login, pass) {
        document.getElementById('loginInput').value = login;
        document.getElementById('passwordInput').value = pass;
    }
</script>
@endsection

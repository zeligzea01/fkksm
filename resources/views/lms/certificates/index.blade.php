@extends('layouts.lms')

@section('title', 'Sertifikat Kompetensi | FKKSM MM2100')
@section('page_title', 'Sertifikat Kompetensi Terbit')
@section('page_subtitle', 'Dokumen pengakuan keahlian dan kepatuhan standar industri MM2100.')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-base text-slate-900">Daftar Sertifikat Terdaftar</h3>
                <p class="text-xs text-slate-500">Seluruh nomor sertifikat tersinkronisasi dengan portal audit publik.</p>
            </div>
            <a href="{{ route('certificates.verify') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                Buka Portal Verifikasi Publik →
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($certificates as $cert)
                <div class="py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-xs">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full {{ $cert->is_revoked ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                            <span class="font-mono font-bold text-slate-900">{{ $cert->certificate_no }}</span>
                            @if ($cert->is_revoked)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-100 text-rose-700">DICABUT</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700">VALID</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-sm text-slate-800">{{ $cert->course->title }}</h4>
                        <p class="text-slate-500 mt-0.5">Diberikan kepada: <strong class="text-slate-700">{{ $cert->user->name }}</strong> • Tanggal: {{ $cert->issued_at->format('d M Y') }}</p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('lms.certificates.show', $cert) }}" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition">
                            Lihat & Cetak →
                        </a>

                        @if (auth()->user()->isAdmin())
                            <form action="{{ route('lms.certificates.toggle-revoke', $cert) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status sertifikat ini?')">
                                @csrf
                                <button type="submit" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 font-semibold text-xs transition">
                                    {{ $cert->is_revoked ? 'Pulihkan' : 'Cabut' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-8 text-center">Belum ada sertifikat yang diterbitkan.</p>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $certificates->links() }}
        </div>
    </div>
</div>
@endsection


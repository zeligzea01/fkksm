@extends('layouts.lms')

@section('title', 'Dashboard LMS | FKKSM MM2100')
@section('header_title', 'DASHBOARD KONTROL LMS & KOMPETENSI')

@section('content')
    <!-- Top Welcome & Industrial Indicator -->
    <div class="mb-8 p-6 rounded-xl bg-gradient-to-r from-[#0C3553] via-[#123A5A] to-[#132B45] text-white border border-[#176D91]/40 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-industrial-grid-dark opacity-30 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#54D6D0]"></span>
                    <span class="font-mono text-[10px] text-[#54D6D0] uppercase tracking-widest font-bold">
                        SESI AKTIF KAWASAN MM2100
                    </span>
                </div>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl uppercase tracking-wide">
                    SELAMAT DATANG, {{ auth()->user()->name }}
                </h1>
                <p class="text-xs text-[#D8EDF7] mt-0.5 font-medium">
                    Hak Akses: <strong class="text-white uppercase font-mono">{{ auth()->user()->role_name }}</strong> · Perusahaan: {{ auth()->user()->company ?? 'Kawasan Industri MM2100' }}
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('lms.courses.index') }}" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#54D6D0] hover:text-[#132B45] text-white font-heading font-bold uppercase tracking-wider text-xs transition shadow-sm">
                    Katalog Pelatihan →
                </a>
            </div>
        </div>
    </div>

    @if (auth()->user()->isAdmin())
        <!-- Administrator Dashboard -->
        <div class="space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">TOTAL PENGGUNA</span>
                    <span class="font-heading font-bold text-3xl text-[#132B45] mt-1 block leading-none">{{ $stats['total_users'] }}</span>
                    <span class="text-[10px] text-[#10A9BC] font-mono mt-1 block">● Terdaftar di Basis Data</span>
                </div>
                <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">PROGRAM KURSUS</span>
                    <span class="font-heading font-bold text-3xl text-[#10A9BC] mt-1 block leading-none">{{ $stats['total_courses'] }}</span>
                    <span class="text-[10px] text-[#61758A] font-mono mt-1 block">Standar BNSP &amp; K3</span>
                </div>
                <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">PESERTA AKTIF</span>
                    <span class="font-heading font-bold text-3xl text-[#176D91] mt-1 block leading-none">{{ $stats['active_enrollments'] }}</span>
                    <span class="text-[10px] text-emerald-600 font-mono mt-1 block">Sedang Mengikuti Kelas</span>
                </div>
                <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">SERTIFIKAT SAH</span>
                    <span class="font-heading font-bold text-3xl text-[#123A5A] mt-1 block leading-none">{{ $stats['issued_certificates'] }}</span>
                    <span class="text-[10px] text-[#54D6D0] font-mono mt-1 block bg-[#132B45] px-1 rounded inline-block">QR Verified</span>
                </div>
                <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">LEADS / PIC MASUK</span>
                    <span class="font-heading font-bold text-3xl text-amber-600 mt-1 block leading-none">{{ $stats['pending_leads'] }}</span>
                    <span class="text-[10px] text-amber-700 font-mono mt-1 block">Perlu Follow-up</span>
                </div>
                <div class="p-4 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">TUGAS MASUK</span>
                    <span class="font-heading font-bold text-3xl text-rose-600 mt-1 block leading-none">{{ $stats['submissions_to_review'] }}</span>
                    <span class="text-[10px] text-rose-600 font-mono mt-1 block">Antrean Penilaian</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Recent Activity & Audit Logs -->
                <div class="lg:col-span-7 bg-white rounded-xl p-6 border border-[#DCE6EE] shadow-xs">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#DCE6EE]">
                        <div>
                            <h3 class="font-heading font-bold text-lg text-[#132B45] uppercase tracking-wide">
                                AKTIVITAS SISTEM &amp; AUDIT TRAIL TERKINI
                            </h3>
                            <p class="text-xs text-[#61758A]">Pencatatan real-time jejak keamanan, perubahan data, dan otentikasi.</p>
                        </div>
                        <a href="{{ route('lms.audit.index') }}" class="text-xs font-bold text-[#10A9BC] hover:underline font-heading uppercase tracking-wider">
                            Semua Log (100% Audit) →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @forelse ($recentActivities as $log)
                            <div class="p-3 rounded-lg bg-[#F5F8FC] border border-[#DCE6EE] flex items-start justify-between gap-3 text-xs">
                                <div class="flex items-start gap-2.5">
                                    <span class="w-2 h-2 rounded-full bg-[#10A9BC] mt-1.5 shrink-0"></span>
                                    <div>
                                        <p class="font-bold text-[#132B45] uppercase font-mono text-[11px]">{{ $log->action_label }}</p>
                                        <p class="text-[#61758A] text-xs mt-0.5 leading-relaxed">{{ $log->details }}</p>
                                        <span class="text-[10px] text-[#A5B4C2] font-mono mt-1 block">
                                            User: {{ $log->user->name ?? 'Sistem Publik' }} · IP: {{ $log->ip_address ?? '127.0.0.1' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-[10px] text-[#61758A] font-mono shrink-0 whitespace-nowrap">
                                    {{ $log->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-[#61758A] py-6 text-center">Belum ada aktivitas tercatat pada log sistem.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Leads & Quick Actions -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-xl p-6 border border-[#DCE6EE] shadow-xs">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#DCE6EE]">
                            <div>
                                <h3 class="font-heading font-bold text-lg text-[#132B45] uppercase tracking-wide">
                                    PEMINAT PELATIHAN MASUK
                                </h3>
                                <p class="text-xs text-[#61758A]">Pengajuan dari PIC korporasi kawasan MM2100.</p>
                            </div>
                            <a href="{{ route('lms.leads.index') }}" class="text-xs font-bold text-[#10A9BC] hover:underline font-heading uppercase tracking-wider">
                                Kelola Leads →
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($recentLeads as $lead)
                                <div class="p-3 rounded-lg bg-[#F5F8FC] border border-[#DCE6EE] text-xs space-y-1">
                                    <div class="flex justify-between items-start">
                                        <span class="font-bold text-[#132B45]">{{ $lead->full_name }}</span>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold uppercase {{ $lead->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-700' }}">
                                            {{ $lead->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-[#61758A] font-mono">{{ $lead->email }} · {{ $lead->phone }}</p>
                                    <p class="text-[11px] text-[#10A9BC] font-semibold truncate">{{ $lead->interest }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-[#61758A] py-6 text-center">Belum ada calon peserta baru.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Industrial Security Card -->
                    <div class="bg-[#132B45] rounded-xl p-6 text-white border border-[#0C3553] shadow-xs space-y-3">
                        <span class="text-[10px] font-mono text-[#54D6D0] uppercase tracking-widest block font-bold">
                            ● INTEGRITAS SISTEM AUDIT KAWASAN
                        </span>
                        <h4 class="font-heading font-bold text-xl uppercase tracking-wide text-white leading-snug">
                            STANDAR KEPATUHAN KEMNAKER &amp; ISO 27001
                        </h4>
                        <p class="text-xs text-[#A5B4C2] leading-relaxed">
                            Seluruh operasi penerbitan sertifikat digital, perubahan silabus, serta permohonan in-house training terikat dengan jejak audit permanen.
                        </p>
                        <a href="{{ route('lms.audit.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition">
                            Buka Laporan Audit Lengkap →
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @elseif (auth()->user()->isInstructor())
        <!-- Instructor Dashboard -->
        <div class="space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="p-5 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">KURSUS SAYA</span>
                    <span class="font-heading font-bold text-4xl text-[#10A9BC] mt-2 block leading-none">{{ $stats['my_courses'] }}</span>
                    <span class="text-xs text-[#61758A] mt-1 block">Kelas Diampu Aktif</span>
                </div>
                <div class="p-5 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">TOTAL PESERTA</span>
                    <span class="font-heading font-bold text-4xl text-[#132B45] mt-2 block leading-none">{{ $stats['total_students'] }}</span>
                    <span class="text-xs text-emerald-600 mt-1 block font-mono">Peserta Terdaftar</span>
                </div>
                <div class="p-5 rounded-xl bg-white border border-[#DCE6EE] shadow-xs">
                    <span class="text-[10px] font-bold text-[#61758A] uppercase tracking-wider block font-heading">EVALUASI MENUNGGU REVIEW</span>
                    <span class="font-heading font-bold text-4xl text-rose-600 mt-2 block leading-none">{{ $stats['pending_submissions'] }}</span>
                    <span class="text-xs text-rose-600 mt-1 block font-mono">Jawaban Kuis / Tugas K3</span>
                </div>
            </div>

            <!-- Instructor Courses -->
            <div class="bg-white rounded-xl p-6 border border-[#DCE6EE] shadow-xs">
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-[#DCE6EE]">
                    <div>
                        <h3 class="font-heading font-bold text-xl text-[#132B45] uppercase tracking-wide">
                            KURSUS YANG ANDA KELOLA
                        </h3>
                        <p class="text-xs text-[#61758A]">Kelola kurikulum, modul video, materi bacaan, dan kuis kelulusan.</p>
                    </div>
                    <a href="{{ route('lms.courses.create') }}" class="px-4 py-2 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition">
                        + Tambah Kursus Baru
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($myCourses as $course)
                        <div class="p-5 rounded-xl border border-[#DCE6EE] bg-[#F5F8FC] hover:bg-white hover:border-[#10A9BC] transition flex flex-col justify-between shadow-xs">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#D8EDF7] text-[#123A5A] uppercase tracking-wider">
                                    {{ $course->category }}
                                </span>
                                <h4 class="font-heading font-bold text-xl text-[#132B45] mt-2 leading-snug line-clamp-2">
                                    {{ $course->title }}
                                </h4>
                                <div class="flex items-center gap-4 text-xs text-[#61758A] mt-3 font-mono">
                                    <span>{{ $course->enrollments_count }} Peserta</span>
                                    <span>•</span>
                                    <span>{{ $course->materials_count }} Materi</span>
                                </div>
                            </div>
                            <div class="pt-4 mt-4 border-t border-[#DCE6EE] flex items-center justify-between">
                                <a href="{{ route('lms.courses.edit', $course) }}" class="text-xs font-bold text-[#61758A] hover:text-[#132B45]">
                                    Edit Info Kursus
                                </a>
                                <a href="{{ route('lms.materials.index') }}" class="text-xs font-bold text-[#10A9BC] hover:underline font-heading uppercase tracking-wider">
                                    Kelola Modul →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @else
        <!-- Student Dashboard -->
        <div class="space-y-8">
            <!-- Active Enrollments -->
            <div class="bg-white rounded-xl p-6 border border-[#DCE6EE] shadow-xs">
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-[#DCE6EE]">
                    <div>
                        <h3 class="font-heading font-bold text-xl text-[#132B45] uppercase tracking-wide">
                            KURSUS YANG SEDANG SAYA IKUTI
                        </h3>
                        <p class="text-xs text-[#61758A]">Lanjutkan modul pembelajaran dan raih sertifikat kompetensi resmi.</p>
                    </div>
                    <a href="{{ route('lms.courses.index') }}" class="text-xs font-bold text-[#10A9BC] hover:underline font-heading uppercase tracking-wider">
                        Katalog Kursus Lengkap →
                    </a>
                </div>

                @if ($enrollments->isEmpty())
                    <div class="text-center py-12 bg-[#F5F8FC] rounded-xl border border-dashed border-[#DCE6EE]">
                        <div class="w-12 h-12 rounded-full bg-[#D8EDF7] text-[#123A5A] flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-[#10A9BC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h4 class="font-heading font-bold text-xl text-[#132B45] uppercase">Belum Ada Kursus yang Diikuti</h4>
                        <p class="text-xs text-[#61758A] max-w-sm mx-auto mt-1 mb-4">
                            Pilih program pelatihan industri yang sesuai dengan kebutuhan peningkatan kompetensi Anda di MM2100.
                        </p>
                        <a href="{{ route('lms.courses.index') }}" class="px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition">
                            Lihat Katalog Pelatihan
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($enrollments as $enrollment)
                            @php
                                $totalMat = $enrollment->course->materials->count();
                                $completedMat = $enrollment->course->materials->filter(fn($m) => isset($completedMaterialIds[$m->id]))->count();
                                $pct = $totalMat > 0 ? (int)round(($completedMat / $totalMat) * 100) : 0;
                            @endphp
                            <div class="p-5 rounded-xl border border-[#DCE6EE] bg-white hover:border-[#10A9BC] transition flex flex-col justify-between shadow-xs">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#D8EDF7] text-[#123A5A] uppercase">
                                            {{ $enrollment->course->category }}
                                        </span>
                                        <span class="text-xs font-mono font-bold {{ $pct >= 100 ? 'text-emerald-600' : 'text-[#10A9BC]' }}">
                                            {{ $pct }}% SELESAI
                                        </span>
                                    </div>
                                    <h4 class="font-heading font-bold text-xl text-[#132B45] leading-snug line-clamp-2">
                                        {{ $enrollment->course->title }}
                                    </h4>
                                    <p class="text-xs text-[#61758A] mt-1">Instruktur: {{ $enrollment->course->instructor->name ?? 'Tim Instruktur FKKSM' }}</p>

                                    <!-- Progress bar -->
                                    <div class="w-full bg-[#EDF5FA] rounded-full h-2 mt-4 overflow-hidden border border-[#DCE6EE]">
                                        <div class="bg-gradient-to-r from-[#10A9BC] to-[#54D6D0] h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] text-[#61758A] font-mono mt-1.5">
                                        <span>{{ $completedMat }} dari {{ $totalMat }} materi</span>
                                        <span class="font-bold {{ $pct >= 100 ? 'text-emerald-600' : 'text-[#132B45]' }}">
                                            {{ $pct >= 100 ? 'LULUS KOMPETENSI' : 'DALAM PROSES' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="pt-4 mt-4 border-t border-[#DCE6EE]">
                                    <a href="{{ route('lms.classroom.show', $enrollment->course) }}" class="w-full py-2.5 rounded-lg bg-[#132B45] hover:bg-[#10A9BC] text-white font-heading font-bold text-xs uppercase tracking-wider text-center block transition shadow-xs">
                                        {{ $pct >= 100 ? 'Tinjau Materi Kelas' : 'Lanjutkan Belajar →' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Earned Certificates -->
            @if ($certificates->isNotEmpty())
                <div class="bg-white rounded-xl p-6 border border-[#DCE6EE] shadow-xs">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#DCE6EE]">
                        <div>
                            <h3 class="font-heading font-bold text-xl text-[#132B45] uppercase tracking-wide">
                                SERTIFIKAT KOMPETENSI RESMI SAYA
                            </h3>
                            <p class="text-xs text-[#61758A]">Sertifikat digital dengan tanda tangan elektronik dan verifikasi QR Code.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($certificates as $cert)
                            <div class="p-4 rounded-xl border border-emerald-300 bg-emerald-50/40 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        <span class="text-xs font-mono font-bold text-emerald-900">{{ $cert->certificate_no }}</span>
                                    </div>
                                    <h4 class="font-heading font-bold text-base text-[#132B45] line-clamp-1">{{ $cert->course->title }}</h4>
                                    <span class="text-[11px] text-[#61758A] font-mono block mt-1">Diterbitkan: {{ $cert->issued_at->format('d M Y') }}</span>
                                </div>
                                <div class="pt-3 mt-3 border-t border-emerald-200 flex items-center justify-between">
                                    <a href="{{ route('lms.certificates.show', $cert) }}" class="text-xs font-bold text-emerald-800 hover:underline font-heading uppercase tracking-wider">
                                        Lihat Sertifikat Digital →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
@endsection

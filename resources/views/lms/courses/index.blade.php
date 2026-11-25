@extends('layouts.lms')

@section('title', 'Katalog Kursus Pelatihan | FKKSM MM2100')
@section('header_title', 'KATALOG PROGRAM PELATIHAN & KOMPETENSI')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-xl border border-[#DCE6EE] shadow-xs flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('lms.courses.index') }}" method="GET" class="flex-1 flex flex-col sm:flex-row gap-3 w-full">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kursus, topik K3, manajemen..." class="w-full px-4 py-2.5 rounded-lg border border-[#DCE6EE] focus:outline-none focus:border-[#10A9BC] text-xs bg-[#F5F8FC]">
            </div>

            <select name="category" class="px-4 py-2.5 rounded-lg border border-[#DCE6EE] text-xs bg-[#F5F8FC] focus:outline-none focus:border-[#10A9BC]">
                <option value="all">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="access_type" class="px-4 py-2.5 rounded-lg border border-[#DCE6EE] text-xs bg-[#F5F8FC] focus:outline-none focus:border-[#10A9BC]">
                <option value="all">Semua Akses</option>
                <option value="free" {{ $accessType === 'free' ? 'selected' : '' }}>Gratis / Subsidi</option>
                <option value="paid" {{ $accessType === 'paid' ? 'selected' : '' }}>Berbayar</option>
            </select>

            <button type="submit" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-[#118DAB] to-[#13AEB9] hover:brightness-105 text-white font-heading font-bold uppercase tracking-wider text-xs shadow-xs transition">
                Filter Kelas
            </button>
        </form>

        @if (auth()->user()->isInstructor())
            <a href="{{ route('lms.courses.create') }}" class="px-4 py-2.5 rounded-lg bg-[#132B45] hover:bg-[#0C3553] text-white font-heading font-bold uppercase tracking-wider text-xs shrink-0 transition">
                + Tambah Kursus
            </a>
        @endif
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($courses as $course)
            <div class="bg-white rounded-xl border border-[#DCE6EE] shadow-xs hover:shadow-md hover:border-[#10A9BC] transition flex flex-col justify-between overflow-hidden group">
                <div>
                    <div class="h-36 bg-gradient-to-r from-[#132B45] to-[#123A5A] p-5 flex flex-col justify-between text-white relative">
                        <div class="flex justify-between items-start z-10">
                            <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#10A9BC] text-white uppercase tracking-wider">
                                {{ $course->category ?? 'Manufaktur' }}
                            </span>
                            <span class="text-xs font-mono font-bold px-2 py-0.5 rounded {{ $course->access_type === 'free' ? 'bg-emerald-500/20 text-[#54D6D0]' : 'bg-amber-500/20 text-amber-300' }}">
                                {{ $course->formatted_price }}
                            </span>
                        </div>
                        <div class="z-10 flex items-center justify-between text-xs text-[#D8EDF7] font-mono">
                            <span>{{ $course->duration_hours }} Jam Pelatihan</span>
                            <span>{{ $course->materials_count }} Materi</span>
                        </div>
                    </div>

                    <div class="p-5 space-y-3">
                        <h3 class="font-heading font-bold text-xl text-[#132B45] group-hover:text-[#10A9BC] transition-colors leading-snug line-clamp-2">
                            {{ $course->title }}
                        </h3>
                        <p class="text-xs text-[#61758A] line-clamp-2 leading-relaxed">
                            {{ $course->description }}
                        </p>

                        <div class="pt-2 flex items-center gap-2 border-t border-[#DCE6EE]">
                            <div class="w-7 h-7 rounded-full bg-[#132B45] text-[#54D6D0] flex items-center justify-center font-heading font-bold text-xs shrink-0">
                                {{ substr($course->instructor->name ?? 'I', 0, 1) }}
                            </div>
                            <span class="text-xs text-[#132B45] font-semibold truncate">{{ $course->instructor->name ?? 'Instruktur FKKSM' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-[#F5F8FC] border-t border-[#DCE6EE] flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-[#10A9BC]">{{ $course->level ? strtoupper($course->level) : 'ALL LEVEL' }}</span>
                    <a href="{{ route('lms.courses.show', $course) }}" class="px-4 py-1.5 rounded-lg bg-[#132B45] hover:bg-[#10A9BC] text-white font-heading font-bold text-xs uppercase tracking-wider transition">
                        Detail Kelas →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-8 text-center bg-white rounded-xl border border-[#DCE6EE]">
                <p class="text-xs text-[#61758A]">Tidak ditemukan kursus yang cocok dengan kriteria pencarian.</p>
            </div>
        @endforelse
    </div>

    @if ($courses->hasPages())
        <div class="bg-white rounded-xl border border-[#DCE6EE] p-3 shadow-xs">
            {{ $courses->links() }}
        </div>
    @endif
</div>
@endsection

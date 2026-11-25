@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between py-4 px-2 select-none">
        <div class="flex justify-between flex-1 sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-[#61758A] bg-[#F5F8FC] border border-[#DCE6EE] rounded-lg cursor-not-allowed">
                    « Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-[#132B45] bg-white border border-[#DCE6EE] rounded-lg hover:border-[#10A9BC] hover:text-[#10A9BC] transition shadow-2xs">
                    « Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-white bg-[#10A9BC] hover:brightness-105 rounded-lg transition shadow-2xs">
                    Berikutnya »
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-[#61758A] bg-[#F5F8FC] border border-[#DCE6EE] rounded-lg cursor-not-allowed">
                    Berikutnya »
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-[#61758A] font-mono">
                    Menampilkan
                    <span class="font-bold text-[#132B45]">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold text-[#132B45]">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-bold text-[#10A9BC]">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-2xs rounded-lg overflow-hidden border border-[#DCE6EE]">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Sebelumnya" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[#61758A] bg-[#F5F8FC] cursor-not-allowed border-r border-[#DCE6EE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[#132B45] bg-white hover:bg-[#D8EDF7] hover:text-[#10A9BC] border-r border-[#DCE6EE] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true" class="relative inline-flex items-center px-3 py-2 text-xs font-mono font-bold text-[#61758A] bg-[#F5F8FC] border-r border-[#DCE6EE]">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="relative inline-flex items-center px-3.5 py-2 text-xs font-mono font-bold text-white bg-[#10A9BC] border-r border-[#DCE6EE]">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-3.5 py-2 text-xs font-mono font-bold text-[#132B45] bg-white hover:bg-[#D8EDF7] hover:text-[#10A9BC] border-r border-[#DCE6EE] transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[#132B45] bg-white hover:bg-[#D8EDF7] hover:text-[#10A9BC] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Berikutnya" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[#61758A] bg-[#F5F8FC] cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif


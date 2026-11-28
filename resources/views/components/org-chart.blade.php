@php
    $pembina = [
        'ketua' => 'Darwoto',
        'anggota' => ['Lis', 'Musfir', 'Vidi Christianto'],
    ];

    $pengurusHarian = [
        ['role' => 'Ketua', 'name' => 'Effendi'],
        ['role' => 'Sekretaris', 'name' => 'Susi Rahmawati'],
        ['role' => 'Bendahara', 'name' => 'Rahmalia'],
        ['role' => 'Admin', 'name' => 'Titis Marheni'],
    ];

    $advisor = ['Sutopoh', 'Yayat Ruhimat', 'Denny Herjaman'];

    $teamPakar = [
        'ketua' => 'Wikan Sakarinto',
        'anggota' => ['M. Aditya Warman', 'Agus Razmajaya'],
    ];

    $wakilKetuaList = [
        [
            'title' => 'WAKIL KETUA 1',
            'subtitle' => 'Bidang Legal & HI',
            'name' => 'Damsiar',
            'divisions' => [
                [
                    'title' => 'LEGAL',
                    'icon' => 'scale',
                    'ketua' => 'Agung',
                    'anggota' => ['Petra', 'Fajar', 'Dadang'],
                ],
                [
                    'title' => 'HUB. INDUSTRIAL',
                    'icon' => 'handshake',
                    'ketua' => 'Ridwan',
                    'anggota' => ['Galuh', 'Erri', 'Rino', 'Yogi'],
                ],
            ],
        ],
        [
            'title' => 'WAKIL KETUA 2',
            'subtitle' => 'Bidang Training & Kegiatan',
            'name' => 'Giad Widianto',
            'divisions' => [
                [
                    'title' => 'TRAINING',
                    'icon' => 'presentation',
                    'ketua' => 'Dewi Kristin',
                    'anggota' => ['Titi', 'Sri Kusmiati', 'Haekal'],
                ],
                [
                    'title' => 'KEGIATAN BLN-THN',
                    'icon' => 'calendar',
                    'ketua' => 'Ismail Jasno',
                    'anggota' => ['Bismo', 'Eva M', 'Nike', 'Rosiana'],
                ],
            ],
        ],
        [
            'title' => 'WAKIL KETUA 3',
            'subtitle' => 'Bidang Comdev & Organisasi',
            'name' => 'Edi Santoso',
            'divisions' => [
                [
                    'title' => 'COMDEV',
                    'icon' => 'users',
                    'ketua' => 'Lucky L',
                    'anggota' => ['Nia', 'Anto', 'Sugeng'],
                ],
                [
                    'title' => 'ORGANISASI',
                    'icon' => 'sitemap',
                    'ketua' => 'Widya',
                    'anggota' => ['Sugianto', 'Usman', 'Robby', 'Dedi Hermawan'],
                ],
            ],
        ],
    ];

    $publicRelations = ['Yusuf Abdullah', 'Hana Hanifah'];
@endphp

<div class="relative overflow-hidden rounded-2xl border border-[#DCE6EE] bg-gradient-to-br from-[#F8FAFC] via-white to-[#EEF4F9] bg-industrial-grid p-5 sm:p-8 lg:p-10 shadow-sm">
    {{-- Header Title Block --}}
    <div class="mb-8 lg:mb-0 lg:absolute lg:top-8 lg:left-8 z-10">
        <span class="inline-block rounded-md bg-[#0C3553] px-3 py-1 font-heading text-xs font-bold uppercase tracking-widest text-white shadow-xs">
            STRUKTUR KEPENGURUSAN
        </span>
        <h3 class="mt-2 font-heading text-3xl sm:text-4xl font-extrabold uppercase tracking-tight text-[#0C3553] leading-none">
            FKKSM MM2100
        </h3>
        <p class="mt-1 font-heading text-base sm:text-lg font-bold uppercase tracking-wider text-[#132B45]">
            PERIODE 2026 - 2031
        </p>
        <div class="mt-1.5 h-1 w-20 rounded-full bg-[#F58220]"></div>
    </div>

    {{-- Top & Middle Hierarchy --}}
    <div class="relative flex flex-col items-center">
        {{-- 1. PEMBINA --}}
        <div class="relative z-10 w-full max-w-[270px]">
            <div class="rounded-xl border border-[#DCE6EE] bg-white shadow-md">
                <div class="relative flex items-center rounded-t-xl bg-[#0C3553] py-2 pl-12 pr-4">
                    <span class="absolute -left-2 -top-2 flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-[#0C3553] text-white shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </span>
                    <span class="font-heading text-sm font-bold uppercase tracking-wider text-white">PEMBINA</span>
                </div>
                <div class="p-3.5 text-xs text-[#132B45] space-y-1.5">
                    <div class="flex">
                        <span class="w-16 shrink-0 font-semibold text-[#61758A]">Ketua</span>
                        <span class="mr-1.5 text-[#61758A]">:</span>
                        <span class="font-semibold">{{ $pembina['ketua'] }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-[#61758A]">Anggota :</span>
                        <ol class="mt-1 space-y-0.5 pl-6 list-decimal text-[#132B45]">
                            @foreach ($pembina['anggota'] as $member)
                                <li>{{ $member }}</li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vertical Connector: Pembina -> Pengurus Harian --}}
        <div class="h-6 lg:h-8 w-0.5 bg-[#3B5978]"></div>

        {{-- 2. PENGURUS HARIAN --}}
        <div class="relative z-10 w-full max-w-[270px]">
            <div class="rounded-xl border border-[#DCE6EE] bg-white shadow-md">
                <div class="relative flex items-center rounded-t-xl bg-[#0C3553] py-2 pl-12 pr-4">
                    <span class="absolute -left-2 -top-2 flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-[#0C3553] text-white shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </span>
                    <span class="font-heading text-sm font-bold uppercase tracking-wider text-white">PENGURUS HARIAN</span>
                </div>
                <div class="p-3.5 text-xs text-[#132B45] space-y-1">
                    @foreach ($pengurusHarian as $item)
                        <div class="flex">
                            <span class="w-20 shrink-0 font-semibold text-[#61758A]">{{ $item['role'] }}</span>
                            <span class="mr-1.5 text-[#61758A]">:</span>
                            <span class="font-semibold">{{ $item['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Spine + Advisor & Team Pakar Row --}}
        <div class="relative w-full mt-6 lg:mt-0">
            {{-- Desktop center vertical spine --}}
            <div class="hidden lg:block mx-auto h-28 w-0.5 bg-[#3B5978]"></div>

            {{-- Desktop dashed horizontal connector between Advisor & Team Pakar --}}
            <div class="hidden lg:block absolute top-14 left-1/2 -translate-x-1/2 w-[62%] border-t-2 border-dashed border-[#3B5978]"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-0 lg:absolute lg:inset-x-0 lg:top-2 lg:flex lg:justify-between lg:px-10">
                {{-- ADVISOR --}}
                <div class="w-full lg:w-[230px] relative z-10">
                    <div class="rounded-xl border border-[#DCE6EE] bg-white shadow-md">
                        <div class="relative flex items-center rounded-t-xl bg-[#0C3553] py-2 pl-12 pr-4">
                            <span class="absolute -left-2 -top-2 flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-[#F58220] text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <span class="font-heading text-sm font-bold uppercase tracking-wider text-white">ADVISOR</span>
                        </div>
                        <div class="p-3.5 text-xs text-[#132B45]">
                            <ol class="space-y-1 pl-5 list-decimal font-medium">
                                @foreach ($advisor as $name)
                                    <li>{{ $name }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- TEAM PAKAR --}}
                <div class="w-full lg:w-[250px] relative z-10">
                    <div class="rounded-xl border border-[#DCE6EE] bg-white shadow-md">
                        <div class="relative flex items-center rounded-t-xl bg-[#0C3553] py-2 pl-12 pr-4">
                            <span class="absolute -left-2 -top-2 flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-[#F58220] text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                                </svg>
                            </span>
                            <span class="font-heading text-sm font-bold uppercase tracking-wider text-white">TEAM PAKAR</span>
                        </div>
                        <div class="p-3.5 text-xs text-[#132B45] space-y-1.5">
                            <div class="flex">
                                <span class="w-16 shrink-0 font-semibold text-[#61758A]">Ketua</span>
                                <span class="mr-1.5 text-[#61758A]">:</span>
                                <span class="font-semibold">{{ $teamPakar['ketua'] }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-[#61758A]">Anggota :</span>
                                <ol class="mt-1 space-y-0.5 pl-6 list-decimal">
                                    @foreach ($teamPakar['anggota'] as $member)
                                        <li>{{ $member }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom 4-Column Branch --}}
    <div class="relative mt-8 lg:mt-16">
        {{-- Desktop horizontal branch line across all 4 columns --}}
        <div class="hidden lg:block absolute -top-6 left-[12.5%] right-[12.5%] h-0.5 bg-[#3B5978]"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
            @foreach ($wakilKetuaList as $wk)
                <div class="relative flex flex-col">
                    {{-- Vertical drop from horizontal branch --}}
                    <div class="hidden lg:block mx-auto -mt-6 mb-2 h-4 w-0.5 bg-[#3B5978]"></div>

                    {{-- Wakil Ketua Card --}}
                    <div class="relative z-10 rounded-xl border border-[#DCE6EE] bg-white shadow-md">
                        <div class="relative flex items-center rounded-t-xl bg-[#0C3553] py-2 pl-12 pr-3">
                            <span class="absolute -left-2 -top-2 flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-[#F58220] text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <span class="font-heading text-sm font-bold uppercase tracking-wider text-white">{{ $wk['title'] }}</span>
                        </div>
                        <div class="p-3 text-center">
                            <p class="text-[11px] font-medium text-[#61758A]">{{ $wk['subtitle'] }}</p>
                            <p class="mt-0.5 text-sm font-bold text-[#132B45]">{{ $wk['name'] }}</p>
                        </div>
                    </div>

                    {{-- Sub-divisions connected along left rail --}}
                    <div class="relative mt-4 pl-5 space-y-4">
                        <div class="absolute left-2 -top-4 bottom-8 w-0.5 bg-[#3B5978]"></div>

                        @foreach ($wk['divisions'] as $div)
                            <div class="relative">
                                {{-- Horizontal tick from left rail to card --}}
                                <div class="absolute -left-3 top-6 h-0.5 w-3 bg-[#3B5978]"></div>

                                <div class="rounded-xl border border-[#DCE6EE] bg-white shadow-sm">
                                    <div class="relative flex items-center rounded-t-xl bg-[#0C3553] py-1.5 pl-11 pr-3">
                                        <span class="absolute -left-2 -top-1.5 flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-[#0C3553] text-white shadow-sm">
                                            @if ($div['icon'] === 'scale')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z" />
                                                </svg>
                                            @elseif ($div['icon'] === 'handshake')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                                </svg>
                                            @elseif ($div['icon'] === 'presentation')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
                                                </svg>
                                            @elseif ($div['icon'] === 'calendar')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                            @elseif ($div['icon'] === 'users')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                                </svg>
                                            @endif
                                        </span>
                                        <span class="font-heading text-xs font-bold uppercase tracking-wider text-white">{{ $div['title'] }}</span>
                                    </div>
                                    <div class="p-3 text-xs text-[#132B45] space-y-1">
                                        <div class="flex">
                                            <span class="w-14 shrink-0 font-semibold text-[#61758A]">Ketua</span>
                                            <span class="mr-1 text-[#61758A]">:</span>
                                            <span class="font-semibold">{{ $div['ketua'] }}</span>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-[#61758A]">Anggota :</span>
                                            <ol class="mt-0.5 space-y-0.5 pl-6 list-decimal">
                                                @foreach ($div['anggota'] as $member)
                                                    <li>{{ $member }}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Column 4: PUBLIC RELATIONS --}}
            <div class="relative flex flex-col">
                <div class="hidden lg:block mx-auto -mt-6 mb-2 h-4 w-0.5 bg-[#3B5978]"></div>

                <div class="relative z-10 rounded-xl border border-[#DCE6EE] bg-white shadow-md">
                    <div class="relative flex items-center rounded-t-xl bg-[#0C3553] py-2 pl-12 pr-3">
                        <span class="absolute -left-2 -top-2 flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-[#F58220] text-white shadow-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.855 2.358A1.125 1.125 0 0021 17.55V6.45a1.125 1.125 0 00-1.805-.927A23.848 23.848 0 0110.34 7.88" />
                            </svg>
                        </span>
                        <span class="font-heading text-sm font-bold uppercase tracking-wider text-white">PUBLIC RELATIONS</span>
                    </div>
                    <div class="p-3.5 text-center text-xs font-semibold text-[#132B45] space-y-1">
                        @foreach ($publicRelations as $name)
                            <p>{{ $name }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('layouts.lms', ['title' => 'Ruang Belajar: ' . $course->title . ' — FKKSM MM2100'])

@section('content')
@php
    $circleCircumference = 47.12388980384689; // 2 * pi * 7.5
    $materialProgressRatio = $totalMaterials > 0 ? min(1, count($completedMaterialIds) / $totalMaterials) : 1;
    $circleDashoffset = $circleCircumference * (1 - $materialProgressRatio);
@endphp

<style>
    .fkksm-player-shell {
        display: grid;
        grid-template-columns: 280px 1fr;
        grid-template-rows: 56px minmax(0, 1fr) 72px;
        min-height: calc(100vh - 130px);
        max-height: calc(100vh - 110px);
        background: #F7F9FB;
        border: 1px solid #E5E9F0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: grid-template-columns 0.28s ease-in-out;
        font-family: 'Nunito Sans', sans-serif;
    }
    @media (min-width: 1600px) {
        .fkksm-player-shell {
            grid-template-columns: 320px 1fr;
        }
    }
    @media (min-width: 2200px) {
        .fkksm-player-shell {
            grid-template-columns: 360px 1fr;
        }
    }
    .fkksm-player-shell.hide-sidebar {
        grid-template-columns: 0px 1fr;
    }
    .fkksm-player-shell.hide-sidebar .fkksm-player-sidebar {
        opacity: 0;
        transform: translateX(-100%);
        pointer-events: none;
    }
    @media (max-width: 900px) {
        .fkksm-player-shell {
            grid-template-columns: 1fr;
            max-height: none;
            min-height: 82vh;
        }
        .fkksm-player-sidebar {
            border-bottom: 1px solid #F2F2F2;
            max-height: 360px;
        }
        .fkksm-player-shell.hide-sidebar .fkksm-player-sidebar {
            display: none;
        }
    }
    .fkksm-player-header {
        grid-column: 1 / -1;
        grid-row: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        background: #FFFFFF;
        border-bottom: 1px solid #F2F2F2;
        z-index: 20;
    }
    .fkksm-player-sidebar {
        grid-column: 1;
        grid-row: 2;
        background: #FFFFFF;
        border-right: 1px solid #F2F2F2;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        padding: 16px 14px 24px 20px;
        transition: transform 0.28s ease, opacity 0.28s ease;
    }
    .fkksm-player-sidebar::-webkit-scrollbar { width: 5px; }
    .fkksm-player-sidebar::-webkit-scrollbar-thumb { background: #C7D2E1; border-radius: 999px; }
    .fkksm-player-stage {
        grid-column: 2;
        grid-row: 2;
        background: #F9F9F9;
        padding: 24px;
        overflow-y: auto;
    }
    @media (max-width: 900px) {
        .fkksm-player-stage { grid-column: 1; grid-row: auto; padding: 16px; }
    }
    .fkksm-player-footer {
        grid-column: 1 / -1;
        grid-row: 3;
        background: #FFFFFF;
        box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.08);
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 30;
    }
    .lms-sidebar-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 10px;
        min-height: 44px;
        border-radius: 8px;
        color: #19191B;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.18s ease;
    }
    .lms-sidebar-item:hover:not(.is-locked) {
        background: rgba(225, 249, 250, 0.65);
        font-weight: 700;
    }
    .lms-sidebar-item.is-active {
        background: #E7F6F8;
        font-weight: 700;
        color: #0C3553;
    }
    .lms-sidebar-item.is-locked {
        opacity: 0.78;
        cursor: not-allowed;
        user-select: none;
    }
    .lms-group-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 10px 10px;
        min-height: 46px;
        border-radius: 8px;
        background: transparent;
        border: 0;
        color: #2A2A2E;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.18s ease;
    }
    .lms-group-btn:hover:not(.is-locked) {
        background: #E9F6FB;
    }
    .lms-group-btn.is-active {
        background: #E7F6F8;
        color: #0C3553;
    }
    .lms-group-btn.is-locked {
        opacity: 0.8;
        cursor: not-allowed;
    }
    .link-material-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 36px 24px;
        background: #FFFFFF;
        border-radius: 10px;
        border: 1px solid #EBEBEB;
        text-align: center;
        max-width: 640px;
        margin: 0 auto;
    }
</style>

<div id="fkksmPlayerShell" class="fkksm-player-shell">

    {{-- 1. TOP PLAYER HEADER BAR (class-details-content__header) --}}
    <header class="fkksm-player-header">
        <div class="flex items-center gap-3">
            <a href="{{ route('lms.courses.show', $course) }}"
               class="inline-flex items-center gap-2 text-[#57575B] hover:text-[#0C3553] transition-transform hover:scale-105"
               title="Kembali ke Detail Modul Kursus">
                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                    <path d="M17.5 9.16666H4.75272L8.87569 5.63273C9.04274 5.48865 9.14585 5.28422 9.16242 5.06425C9.179 4.84428 9.10769 4.62671 8.96412 4.45923C8.82055 4.29175 8.61643 4.18802 8.39651 4.17078C8.17659 4.15354 7.9588 4.2242 7.79089 4.36726L1.95756 9.36726C1.86631 9.44551 1.79306 9.54257 1.74284 9.65179C1.69263 9.761 1.66663 9.87979 1.66663 9.99999C1.66663 10.1202 1.69263 10.239 1.74284 10.3482C1.79306 10.4574 1.86631 10.5545 1.95756 10.6327L7.79089 15.6327C7.9588 15.7758 8.17659 15.8464 8.39651 15.8292C8.61643 15.812 8.82055 15.7082 8.96412 15.5408C9.10769 15.3733 9.179 15.1557 9.16242 14.9357C9.14585 14.7158 9.04274 14.5113 8.87569 14.3673L4.75272 10.8333H17.5C17.721 10.8333 17.9329 10.7455 18.0892 10.5892C18.2455 10.433 18.3333 10.221 18.3333 9.99999C18.3333 9.77898 18.2455 9.56702 18.0892 9.41074C17.9329 9.25446 17.721 9.16666 17.5 9.16666Z" fill="currentColor"></path>
                </svg>
                <span class="hidden sm:inline text-xs font-bold text-[#57575B]">Detail Modul</span>
            </a>
        </div>

        {{-- Centered FKKSM Brand Logo --}}
        <div class="flex items-center justify-center gap-2 pointer-events-none">
            <img src="{{ asset('fkksm/logo-transparent.png') }}" alt="FKKSM MM2100" class="h-6 w-auto object-contain">
            <span class="text-xs font-extrabold tracking-wide text-[#2A2A2E] hidden sm:inline">FKKSM MM2100 UPSKILL</span>
        </div>

        {{-- Right Progress / Certificate Pill --}}
        <div class="flex items-center gap-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-[#F2F2F2] bg-[#FAFAFA]">
                <span class="w-2 h-2 rounded-full {{ $progressPercent >= 100 ? 'bg-[#98C93C]' : 'bg-[#10A9BC]' }}"></span>
                <span class="text-xs font-bold text-[#2A2A2E]">{{ $completedCount }}/{{ $totalSteps }} Tahap</span>
            </div>
            @if($certificate)
                <a href="{{ route('lms.certificates.show', $certificate) }}"
                   class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#E7F6F8] text-[#10A9BC] text-xs font-extrabold hover:bg-[#d5eff3] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Sertifikat Terbit
                </a>
            @endif
        </div>
    </header>

    {{-- 2. LEFT COLLAPSIBLE SYLLABUS SIDEBAR (280px, lms-sidebar) --}}
    <aside id="fkksmPlayerSidebar" class="fkksm-player-sidebar">
        {{-- Sticky Course Title & Progress Bar --}}
        <div class="sticky top-0 bg-white z-10 pb-3 mb-3 border-b border-[#F2F2F2]">
            <div class="text-[12px] font-semibold text-[#7E7E81] leading-[18px] mb-2">
                {{ $course->title }}
            </div>

            {{-- Progress Indicator with Badge Check SVG --}}
            <div class="flex items-center gap-2.5">
                <div class="relative flex-1 h-[6px] bg-[#DCE6EE] rounded-[6px]">
                    <div class="h-full bg-[#0C3553] rounded-[6px] transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                    <span class="absolute -right-1 top-1/2 -translate-y-1/2">
                        <svg fill="none" viewBox="2 2 16 16" width="18" height="18">
                            <circle cx="10" cy="10" r="5" fill="white"></circle>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.26701 3.45496C6.91008 3.40364 7.52057 3.15077 8.01158 2.73234C9.15738 1.75589 10.8426 1.75589 11.9884 2.73234C12.4794 3.15077 13.0899 3.40364 13.733 3.45496C15.2336 3.57471 16.4253 4.76636 16.545 6.26701C16.5964 6.91008 16.8492 7.52057 17.2677 8.01158C18.2441 9.15738 18.2441 10.8426 17.2677 11.9884C16.8492 12.4794 16.5964 13.0899 16.545 13.733C16.4253 15.2336 15.2336 16.4253 13.733 16.545C13.0899 16.5964 12.4794 16.8492 11.9884 17.2677C10.8426 18.2441 9.15738 18.2441 8.01158 17.2677C7.52057 16.8492 6.91008 16.5964 6.26701 16.545C4.76636 16.4253 3.57471 15.2336 3.45496 13.733C3.40364 13.0899 3.15077 12.4794 2.73234 11.9884C1.75589 10.8426 1.75589 9.15738 2.73234 8.01158C3.15077 7.52057 3.40364 6.91008 3.45496 6.26701C3.57471 4.76636 4.76636 3.57471 6.26701 3.45496ZM13.7071 8.70711C14.0976 8.31658 14.0976 7.68342 13.7071 7.29289C13.3166 6.90237 12.6834 6.90237 12.2929 7.29289L9 10.5858L7.70711 9.29289C7.31658 8.90237 6.68342 8.90237 6.29289 9.29289C5.90237 9.68342 5.90237 10.3166 6.29289 10.7071L8.29289 12.7071C8.68342 13.0976 9.31658 13.0976 9.70711 12.7071L13.7071 8.70711Z" fill="{{ $progressPercent >= 100 ? '#98C93C' : '#00ADC6' }}"></path>
                        </svg>
                    </span>
                </div>
                <span class="text-[12px] font-bold text-[#19191B] min-w-[32px] text-right">{{ $progressPercent }}%</span>
            </div>
        </div>

        {{-- Navigation Groups: 1. Materi Pembelajaran (Video -> Materi -> Latihan Soal), 2. Latihan Akhir, 3. Sertifikat --}}
        <div class="space-y-2 flex-1">
            {{-- GROUP 1: Materi Pembelajaran --}}
            <div>
                <button type="button"
                        onclick="document.getElementById('sidebarMateriSubmenu').classList.toggle('hidden')"
                        class="lms-group-btn {{ $activeStepMode === 'material' ? 'is-active' : '' }}">
                    <span class="flex items-center gap-2 min-w-0">
                        {{-- Circular Progress Ring SVG (lms-circle-bar) --}}
                        <svg width="18" height="18" viewBox="0 0 18 18" class="shrink-0">
                            <g transform="rotate(-90 9 9)">
                                <circle fill="none" cx="9" cy="9" r="7.5" stroke-width="3" stroke="#D8D8D9"></circle>
                                <circle stroke-linecap="round" fill="none" cx="9" cy="9" r="7.5" stroke-width="3"
                                        stroke="{{ $materialProgressRatio >= 1 ? '#98C93C' : '#0C3553' }}"
                                        stroke-dasharray="{{ $circleCircumference }}"
                                        stroke-dashoffset="{{ $circleDashoffset }}"></circle>
                            </g>
                        </svg>
                        <span class="truncate">Materi Pembelajaran</span>
                    </span>
                    <svg viewBox="0 0 24 24" fill="none" width="18" height="18" class="shrink-0 text-[#1D1B20]">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.35147 8.75186C6.8201 8.28323 7.5799 8.28323 8.04853 8.75186L12 12.7033L15.9515 8.75186C16.4201 8.28323 17.1799 8.28323 17.6485 8.75186C18.1172 9.22049 18.1172 9.98029 17.6485 10.4489L12.8485 15.2489C12.3799 15.7175 11.6201 15.7175 11.1515 15.2489L6.35147 10.4489C5.88284 9.98029 5.88284 9.22049 6.35147 8.75186Z" fill="currentColor"></path>
                    </svg>
                </button>

                <div id="sidebarMateriSubmenu" class="pl-4 pr-1 mt-2 space-y-4">
                    @foreach($course->modules as $modIndex => $module)
                        <div>
                            <div class="mb-2">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-[#7E7E81] leading-tight">
                                    PEMBELAJARAN {{ $modIndex + 1 }} - {{ strtoupper($module->title) }}
                                </span>
                                <div class="h-[1px] bg-[#D9D9D9] mt-1.5"></div>
                            </div>

                            <ul class="space-y-1">
                                @foreach($module->materials as $mat)
                                    @php
                                        $isDone = in_array($mat->id, $completedMaterialIds, true);
                                        $isUnlocked = in_array($mat->id, $unlockedMaterialIds, true);
                                        $isActive = $activeStepMode === 'material' && $currentMaterial && $currentMaterial->id === $mat->id;
                                    @endphp
                                    <li>
                                        @if($isUnlocked)
                                            <a href="{{ route('lms.classroom.show', ['course' => $course->id, 'material' => $mat->id]) }}"
                                               class="lms-sidebar-item {{ $isActive ? 'is-active' : '' }}"
                                               title="{{ $mat->stage_label }}: {{ $mat->title }}">
                                                {{-- Stage / Status Icon --}}
                                                @if($isDone)
                                                    <span class="w-4 h-4 rounded-full bg-[#2ECC71] text-white flex items-center justify-center shrink-0">
                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                                    </span>
                                                @elseif($mat->content_type === 'video')
                                                    <svg class="w-4 h-4 shrink-0 text-[#10A9BC]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                                @elseif(in_array($mat->content_type, ['practice', 'quiz'], true))
                                                    <svg class="w-4 h-4 shrink-0 text-[#10A9BC]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                                                @else
                                                    <svg class="w-4 h-4 shrink-0 text-[#10A9BC]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                                @endif

                                                <div class="min-w-0 flex-1">
                                                    <div class="truncate leading-snug">{{ $mat->sidebar_type_label }}</div>
                                                    <div class="text-[11px] text-[#7E7E81] font-normal truncate">{{ $mat->title }}</div>
                                                </div>
                                            </a>
                                        @else
                                            <button type="button"
                                                    onclick="window.fkksmModal({icon:'warning', title:'Tahap Masih Terkunci', text:'Harap selesaikan tahapan sebelumnya (Video → Materi → Latihan Soal) satu per satu secara berurutan.'})"
                                                    class="lms-sidebar-item is-locked w-full text-left"
                                                    title="Terkunci: Selesaikan tahap sebelumnya">
                                                {{-- Reference icon-brown-lock SVG --}}
                                                <svg viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg" width="15" height="15" class="shrink-0">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82456 13.3929V9.55357C9.82456 7.94643 10.4172 6.57738 11.6023 5.44643C12.8499 4.25595 14.3158 3.66071 16 3.66071C17.6842 3.66071 19.1189 4.25595 20.3041 5.44643C21.5517 6.57738 22.1754 7.94643 22.1754 9.55357V13.3929H9.82456ZM30.7836 14.5536C29.9727 13.7798 29.037 13.3929 27.9766 13.3929H26.0117V9.55357C26.0117 6.93452 25.0136 4.70238 23.0175 2.85714C21.0838 0.952381 18.7446 0 16 0C13.2554 0 10.885 0.952381 8.88889 2.85714C6.95517 4.70238 5.9883 6.93452 5.9883 9.55357V13.3929H4.02339C2.96296 13.3929 2.02729 13.7798 1.21637 14.5536C0.405458 15.2679 0 16.131 0 17.1429V36.25C0 37.2619 0.405458 38.1548 1.21637 38.9286C2.02729 39.6429 2.96296 40 4.02339 40H27.9766C29.037 40 29.9727 39.6429 30.7836 38.9286C31.5945 38.1548 32 37.2619 32 36.25V17.1429C32 16.131 31.5945 15.2679 30.7836 14.5536ZM20 24C20 25.5109 19.1623 26.8262 17.9259 27.5067V30.7936C17.9259 31.7755 17.13 32.5714 16.1481 32.5714C15.1663 32.5714 14.3704 31.7755 14.3704 30.7936V27.6541C12.9735 27.0302 12 25.6288 12 24C12 21.7909 13.7909 20 16 20C18.2091 20 20 21.7909 20 24Z" fill="#BCBCBD"></path>
                                                </svg>
                                                <div class="min-w-0 flex-1">
                                                    <div class="truncate leading-snug text-[#7E7E81]">{{ $mat->sidebar_type_label }}</div>
                                                    <div class="text-[11px] text-[#98989A] font-normal truncate">{{ $mat->title }}</div>
                                                </div>
                                            </button>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- GROUP 2: Latihan Akhir / Penilaian Akhir --}}
            @if($finalAssessment)
                <div class="pt-2 border-t border-[#F2F2F2]">
                    @if($finalAssessmentUnlocked)
                        <a href="{{ route('lms.classroom.show', ['course' => $course->id, 'step' => 'final']) }}"
                           class="lms-group-btn {{ $activeStepMode === 'final_assessment' ? 'is-active' : '' }}"
                           style="text-decoration: none;">
                            <span class="flex items-center gap-2.5 min-w-0">
                                @if($finalAssessmentPassed)
                                    <span class="w-5 h-5 rounded-full bg-[#2ECC71] text-white flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @else
                                    <svg class="w-5 h-5 text-[#0C3553] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                @endif
                                <span class="truncate">Latihan Akhir</span>
                            </span>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded bg-[#E7F6F8] text-[#10A9BC]">
                                {{ $finalAssessmentPassed ? 'Lulus' : 'Terbuka' }}
                            </span>
                        </a>
                    @else
                        <button type="button"
                                onclick="window.fkksmModal({icon:'warning', title:'Latihan Akhir Terkunci', text:'Harap selesaikan seluruh tahapan Video, Materi, dan Latihan Soal pada setiap modul sebelum mengerjakan Latihan Akhir.'})"
                                class="lms-group-btn is-locked">
                            <span class="flex items-center gap-2.5 min-w-0">
                                <svg viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="shrink-0">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82456 13.3929V9.55357C9.82456 7.94643 10.4172 6.57738 11.6023 5.44643C12.8499 4.25595 14.3158 3.66071 16 3.66071C17.6842 3.66071 19.1189 4.25595 20.3041 5.44643C21.5517 6.57738 22.1754 7.94643 22.1754 9.55357V13.3929H9.82456ZM30.7836 14.5536C29.9727 13.7798 29.037 13.3929 27.9766 13.3929H26.0117V9.55357C26.0117 6.93452 25.0136 4.70238 23.0175 2.85714C21.0838 0.952381 18.7446 0 16 0C13.2554 0 10.885 0.952381 8.88889 2.85714C6.95517 4.70238 5.9883 6.93452 5.9883 9.55357V13.3929H4.02339C2.96296 13.3929 2.02729 13.7798 1.21637 14.5536C0.405458 15.2679 0 16.131 0 17.1429V36.25C0 37.2619 0.405458 38.1548 1.21637 38.9286C2.02729 39.6429 2.96296 40 4.02339 40H27.9766C29.037 40 29.9727 39.6429 30.7836 38.9286C31.5945 38.1548 32 37.2619 32 36.25V17.1429C32 16.131 31.5945 15.2679 30.7836 14.5536ZM20 24C20 25.5109 19.1623 26.8262 17.9259 27.5067V30.7936C17.9259 31.7755 17.13 32.5714 16.1481 32.5714C15.1663 32.5714 14.3704 31.7755 14.3704 30.7936V27.6541C12.9735 27.0302 12 25.6288 12 24C12 21.7909 13.7909 20 16 20C18.2091 20 20 21.7909 20 24Z" fill="#BCBCBD"></path>
                                </svg>
                                <span class="truncate text-[#7E7E81]">Latihan Akhir</span>
                            </span>
                        </button>
                    @endif
                </div>
            @endif

            {{-- GROUP 3: Sertifikat Kompetensi --}}
            <div class="pt-2 border-t border-[#F2F2F2]">
                @if($certificate)
                    <a href="{{ route('lms.classroom.show', ['course' => $course->id, 'step' => 'certificate']) }}"
                       class="lms-group-btn {{ $activeStepMode === 'certificate' ? 'is-active' : '' }}"
                       style="text-decoration: none;">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <span class="w-5 h-5 rounded-full bg-[#98C93C] text-white flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="truncate">Sertifikat Kompetensi</span>
                        </span>
                    </a>
                @else
                    <button type="button"
                            onclick="window.fkksmModal({icon:'info', title:'Sertifikat Belum Terbuka', text:'Selesaikan seluruh tahapan Video, Materi, Latihan Soal, dan lulus Latihan Akhir untuk menerbitkan Sertifikat Kompetensi FKKSM MM2100.'})"
                            class="lms-group-btn is-locked">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82456 13.3929V9.55357C9.82456 7.94643 10.4172 6.57738 11.6023 5.44643C12.8499 4.25595 14.3158 3.66071 16 3.66071C17.6842 3.66071 19.1189 4.25595 20.3041 5.44643C21.5517 6.57738 22.1754 7.94643 22.1754 9.55357V13.3929H9.82456ZM30.7836 14.5536C29.9727 13.7798 29.037 13.3929 27.9766 13.3929H26.0117V9.55357C26.0117 6.93452 25.0136 4.70238 23.0175 2.85714C21.0838 0.952381 18.7446 0 16 0C13.2554 0 10.885 0.952381 8.88889 2.85714C6.95517 4.70238 5.9883 6.93452 5.9883 9.55357V13.3929H4.02339C2.96296 13.3929 2.02729 13.7798 1.21637 14.5536C0.405458 15.2679 0 16.131 0 17.1429V36.25C0 37.2619 0.405458 38.1548 1.21637 38.9286C2.02729 39.6429 2.96296 40 4.02339 40H27.9766C29.037 40 29.9727 39.6429 30.7836 38.9286C31.5945 38.1548 32 37.2619 32 36.25V17.1429C32 16.131 31.5945 15.2679 30.7836 14.5536ZM20 24C20 25.5109 19.1623 26.8262 17.9259 27.5067V30.7936C17.9259 31.7755 17.13 32.5714 16.1481 32.5714C15.1663 32.5714 14.3704 31.7755 14.3704 30.7936V27.6541C12.9735 27.0302 12 25.6288 12 24C12 21.7909 13.7909 20 16 20C18.2091 20 20 21.7909 20 24Z" fill="#BCBCBD"></path>
                            </svg>
                            <span class="truncate text-[#7E7E81]">Sertifikat Kompetensi</span>
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </aside>

    {{-- 3. MAIN LEARNING STAGE (class-details-content__content) --}}
    <main class="fkksm-player-stage">
        <div class="w-full max-w-5xl 2xl:max-w-6xl min-[2200px]:max-w-7xl mx-auto">

            {{-- Validation Errors Banner --}}
            @if($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-bold">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- MODE A: FINAL ASSESSMENT (LATIHAN AKHIR) --}}
            @if($activeStepMode === 'final_assessment' && $finalAssessment)
                <div class="bg-white rounded-xl border border-[#EBEBEB] shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#F2F2F2] bg-gradient-to-r from-[#0C3553]/5 to-[#10A9BC]/10 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#10A9BC] text-white text-[10px] font-extrabold uppercase tracking-wider mb-2">
                                Tahap 4 dari 4 • Latihan Akhir (Penilaian Akhir)
                            </div>
                            <h1 class="text-xl font-extrabold text-[#2A2A2E]">{{ $finalAssessment->title }}</h1>
                            <p class="text-xs text-[#62616B] mt-1">
                                Batas nilai minimum kelulusan: <strong>{{ $finalAssessment->passing_score ?: 70 }}%</strong>.
                                Selesaikan latihan akhir ini untuk menerbitkan Sertifikat Kompetensi FKKSM MM2100.
                            </p>
                        </div>

                        @if($finalSubmission && $finalSubmission->score !== null)
                            <div class="px-4 py-2.5 rounded-xl border {{ $finalAssessmentPassed ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-amber-50 border-amber-200 text-amber-800' }} text-right">
                                <div class="text-[10px] font-bold uppercase">Skor Terakhir Anda</div>
                                <div class="text-xl font-extrabold">{{ (int) $finalSubmission->score }}%</div>
                            </div>
                        @endif
                    </div>

                    <form id="activeStepCompleteForm"
                          method="POST"
                          action="{{ route('lms.classroom.final-assessment.submit', ['course' => $course->id, 'assessment' => $finalAssessment->id]) }}"
                          class="p-6 space-y-6">
                        @csrf

                        @if($finalAssessment->instructions)
                            <div class="p-4 rounded-xl bg-[#F7F9FB] border border-[#E5E9F0] text-sm text-[#2A2A2E] leading-relaxed">
                                <div class="text-xs font-extrabold uppercase tracking-wider text-[#0C3553] mb-1">Instruksi Evaluasi Akhir</div>
                                {{ $finalAssessment->instructions }}
                            </div>
                        @endif

                        @php
                            $finalQuestions = !empty($finalAssessment->quiz_payload) ? array_values((array) $finalAssessment->quiz_payload) : [];
                        @endphp

                        @if(!empty($finalQuestions))
                            <div class="space-y-5">
                                @foreach($finalQuestions as $qIdx => $qItem)
                                    <div class="p-5 rounded-xl border border-[#E5E9F0] bg-[#FAFAFA]" data-quiz-question="{{ $qIdx }}">
                                        <div class="text-sm font-extrabold text-[#2A2A2E] mb-3">
                                            {{ $qIdx + 1 }}. {{ $qItem['question'] ?? '' }}
                                        </div>
                                        <div class="space-y-2.5">
                                            @foreach(($qItem['options'] ?? []) as $optIdx => $optText)
                                                <label class="flex items-start gap-3 p-3 rounded-lg border border-[#E5E9F0] bg-white hover:border-[#10A9BC] cursor-pointer transition-colors">
                                                    <input type="radio"
                                                           name="answers[{{ $qIdx }}]"
                                                           value="{{ $optIdx }}"
                                                           {{ (string) old("answers.{$qIdx}") === (string) $optIdx ? 'checked' : '' }}
                                                           class="mt-0.5 text-[#10A9BC] focus:ring-[#10A9BC]"
                                                           required>
                                                    <span class="text-sm text-[#2A2A2E]">{{ $optText }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-extrabold text-[#2A2A2E] uppercase tracking-wider mb-2">
                                Catatan Implementasi / Rencana Penerapan di Tempat Kerja {{ empty($finalQuestions) ? '(Wajib)' : '(Opsional)' }}
                            </label>
                            <textarea name="response_text"
                                      rows="3"
                                      {{ empty($finalQuestions) ? 'required minlength=10' : '' }}
                                      placeholder="Tuliskan ringkasan penerapan standar kompetensi ini di lingkungan pabrik / perusahaan Anda..."
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9D9D9] text-sm focus:border-[#10A9BC] focus:outline-none">{{ old('response_text', $finalSubmission?->response_text) }}</textarea>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-[#F2F2F2]">
                            <span class="text-xs text-[#62616B]">
                                Pastikan seluruh jawaban telah terisi sebelum menekan tombol kumpulkan.
                            </span>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-sm font-extrabold transition-colors shadow-sm cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                {{ $finalAssessmentPassed ? 'Perbarui Jawaban Latihan Akhir' : 'Kumpulkan Latihan Akhir & Terbitkan Sertifikat' }}
                            </button>
                        </div>
                    </form>
                </div>

            {{-- MODE B: CERTIFICATE VIEW --}}
            @elseif($activeStepMode === 'certificate' && $certificate)
                <div class="link-material-card shadow-sm">
                    <div class="w-20 h-20 rounded-full bg-[#98C93C]/15 text-[#83B32F] flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-extrabold mb-2">
                        Seluruh Tahapan Selesai (100%)
                    </div>
                    <h2 class="text-xl font-extrabold text-[#2A2A2E] mb-2">Selamat! Sertifikat Kompetensi Anda Telah Terbit</h2>
                    <p class="text-sm text-[#62616B] max-w-md mb-4">
                        Anda telah menyelesaikan seluruh tahapan <strong>Video → Materi → Latihan Soal → Latihan Akhir</strong> pada program <strong>{{ $course->title }}</strong>.
                    </p>
                    <div class="px-4 py-2 rounded-lg bg-[#F7F9FB] border border-[#E5E9F0] font-mono text-xs font-bold text-[#0C3553] mb-6">
                        Nomor Sertifikat: {{ $certificate->certificate_no }}
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('lms.certificates.show', $certificate) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-sm font-extrabold transition-colors">
                            Lihat & Cetak Sertifikat Resmi
                        </a>
                        <a href="{{ route('certificates.verify', $certificate->certificate_no) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#D9D9D9] text-[#2A2A2E] hover:bg-slate-50 text-sm font-bold transition-colors">
                            Verifikasi Publik
                        </a>
                    </div>
                </div>

            {{-- MODE C: ACTIVE LEARNING MATERIAL (1. VIDEO / 2. MATERI / 3. LATIHAN SOAL) --}}
            @elseif($currentMaterial)
                @php
                    $isCurrentCompleted = in_array($currentMaterial->id, $completedMaterialIds, true);
                    $stageBadge = match($currentMaterial->content_type) {
                        'video' => 'Tahap 1 • Video Pembelajaran',
                        'practice', 'quiz' => 'Tahap 3 • Latihan Soal Modul',
                        default => 'Tahap 2 • Materi & E-Book',
                    };
                @endphp

                <div class="bg-white rounded-xl border border-[#EBEBEB] shadow-sm overflow-hidden">
                    {{-- Material Stage Header --}}
                    <div class="p-6 border-b border-[#F2F2F2] flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="px-2.5 py-0.5 rounded-full bg-[#E7F6F8] text-[#0C3553] text-[11px] font-extrabold">
                                    {{ $stageBadge }}
                                </span>
                                @if($currentMaterial->module)
                                    <span class="text-xs font-bold text-[#7E7E81]">
                                        • {{ $currentMaterial->module->title }}
                                    </span>
                                @endif
                                <span class="text-xs text-[#98989A]">
                                    • Estimasi {{ $currentMaterial->duration_minutes ?: 5 }} menit
                                </span>
                            </div>
                            <h1 class="text-lg sm:text-xl font-extrabold text-[#2A2A2E]">{{ $currentMaterial->title }}</h1>
                        </div>

                        @if($isCurrentCompleted)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-extrabold">
                                <svg class="w-4 h-4 text-[#2ECC71]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                Tahap Selesai
                            </span>
                        @endif
                    </div>

                    {{-- Stage Body Content --}}
                    <div class="p-6 space-y-6">

                        {{-- STAGE 1: LIGHTWEIGHT LOCAL STORAGE HTML5 VIDEO --}}
                        @if($currentMaterial->content_type === 'video')
                            <div class="rounded-xl overflow-hidden bg-[#0F172A] border border-slate-800 shadow-md">
                                <div class="aspect-video w-full relative bg-black flex items-center justify-center">
                                    <video id="fkksmLocalVideoPlayer"
                                           class="w-full h-full object-contain"
                                           controls
                                           preload="metadata"
                                           playsinline
                                           poster="{{ asset($course->thumbnail ?: 'fkksm/assets/images/activity-02.jpg') }}">
                                        <source src="{{ $currentMaterial->video_stream_url }}" type="video/mp4">
                                        Browser Anda tidak mendukung pemutar video HTML5.
                                    </video>
                                </div>
                            </div>

                            @if($currentMaterial->content_text)
                                <div class="p-5 rounded-xl bg-[#F7F9FB] border border-[#E5E9F0]">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#0C3553] mb-2">Ringkasan Video Pembelajaran</h3>
                                    <div class="text-sm text-[#2A2A2E] leading-relaxed whitespace-pre-line">{{ $currentMaterial->content_text }}</div>
                                </div>
                            @endif

                            <form id="activeStepCompleteForm"
                                  method="POST"
                                  action="{{ route('lms.classroom.material.complete', [$course, $currentMaterial]) }}"
                                  data-step-type="video"
                                  data-already-done="{{ $isCurrentCompleted ? '1' : '0' }}"
                                  class="pt-4 border-t border-[#F2F2F2] flex flex-wrap items-center justify-between gap-4">
                                @csrf
                                <label class="inline-flex items-center gap-2.5 text-xs font-bold text-[#2A2A2E] cursor-pointer select-none">
                                    <input type="checkbox"
                                           id="videoWatchConfirmCheckbox"
                                           name="watch_confirmed"
                                           value="1"
                                           {{ $isCurrentCompleted ? 'checked' : '' }}
                                           class="w-4 h-4 rounded text-[#10A9BC] focus:ring-[#10A9BC]">
                                    <span>Saya telah menyimak dan memahami materi video pembelajaran ini</span>
                                </label>

                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-extrabold transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                    {{ $isCurrentCompleted ? 'Video Selesai — Lanjut ke Materi' : 'Selesaikan Video & Lanjut ke Materi' }}
                                </button>
                            </form>

                        {{-- STAGE 3: LATIHAN SOAL (PRACTICE QUIZ PER MODULE) --}}
                        @elseif(in_array($currentMaterial->content_type, ['practice', 'quiz'], true))
                            @php
                                $practiceQuestions = !empty($currentMaterial->quiz_payload) ? array_values((array) $currentMaterial->quiz_payload) : [];
                            @endphp

                            @if($currentMaterial->content_text)
                                <div class="p-4 rounded-xl bg-[#F7F9FB] border border-[#E5E9F0] text-sm text-[#2A2A2E]">
                                    {{ $currentMaterial->content_text }}
                                </div>
                            @endif

                            <form id="activeStepCompleteForm"
                                  method="POST"
                                  action="{{ route('lms.classroom.material.complete', [$course, $currentMaterial]) }}"
                                  data-step-type="practice"
                                  data-already-done="{{ $isCurrentCompleted ? '1' : '0' }}"
                                  class="space-y-5">
                                @csrf

                                @if(!empty($practiceQuestions))
                                    @foreach($practiceQuestions as $qIdx => $qItem)
                                        <div class="p-5 rounded-xl border border-[#E5E9F0] bg-[#FAFAFA]" data-quiz-question="{{ $qIdx }}">
                                            <div class="text-sm font-extrabold text-[#2A2A2E] mb-3">
                                                Soal {{ $qIdx + 1 }}: {{ $qItem['question'] ?? '' }}
                                            </div>
                                            <div class="space-y-2.5">
                                                @foreach(($qItem['options'] ?? []) as $optIdx => $optText)
                                                    <label class="flex items-start gap-3 p-3 rounded-lg border border-[#E5E9F0] bg-white hover:border-[#10A9BC] cursor-pointer transition-colors">
                                                        <input type="radio"
                                                               name="answers[{{ $qIdx }}]"
                                                               value="{{ $optIdx }}"
                                                               {{ (string) old("answers.{$qIdx}") === (string) $optIdx || ($isCurrentCompleted && (int) ($qItem['answer'] ?? -1) === $optIdx) ? 'checked' : '' }}
                                                               class="mt-0.5 text-[#10A9BC] focus:ring-[#10A9BC]">
                                                        <span class="text-sm text-[#2A2A2E]">{{ $optText }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="pt-4 border-t border-[#F2F2F2] flex flex-wrap items-center justify-between gap-4">
                                    <span class="text-xs text-[#62616B]">
                                        Jawab seluruh soal latihan di atas dengan minimal skor <strong>70%</strong> untuk membuka tahapan berikutnya.
                                    </span>
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-extrabold transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                        {{ $isCurrentCompleted ? 'Latihan Soal Selesai — Lanjutkan' : 'Periksa Jawaban & Lanjutkan' }}
                                    </button>
                                </div>
                            </form>

                        {{-- STAGE 2: MATERI / E-BOOK / LINK CARD --}}
                        @else
                            @if($currentMaterial->content_url)
                                {{-- Reference .link-material card layout --}}
                                <div class="link-material-card mb-4">
                                    <div class="w-16 h-16 rounded-2xl bg-[#E7F6F8] text-[#10A9BC] flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-[#2A2A30] mb-1.5">{{ $currentMaterial->title }}</h3>
                                    <p class="text-sm text-[#62616B] break-all mb-4">{{ $currentMaterial->content_url }}</p>
                                    <a href="{{ \App\Support\SecurityHelper::sanitizeUrl($currentMaterial->content_url, '#', false) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#10A9BC] hover:bg-[#008892] text-white text-sm font-bold transition-colors">
                                        Buka Tautan Materi
                                    </a>
                                </div>
                            @endif

                            @if($currentMaterial->content_text)
                                <div class="p-6 rounded-xl bg-[#FAFAFA] border border-[#E5E9F0] text-[#2A2A2E] text-sm leading-relaxed whitespace-pre-line">
                                    {{ $currentMaterial->content_text }}
                                </div>
                            @endif

                            <form id="activeStepCompleteForm"
                                  method="POST"
                                  action="{{ route('lms.classroom.material.complete', [$course, $currentMaterial]) }}"
                                  data-step-type="materi"
                                  data-already-done="{{ $isCurrentCompleted ? '1' : '0' }}"
                                  class="pt-4 border-t border-[#F2F2F2] flex flex-wrap items-center justify-between gap-4">
                                @csrf
                                <label class="inline-flex items-center gap-2.5 text-xs font-bold text-[#2A2A2E] cursor-pointer select-none">
                                    <input type="checkbox"
                                           id="materiReadConfirmCheckbox"
                                           name="read_confirmed"
                                           value="1"
                                           {{ $isCurrentCompleted ? 'checked' : '' }}
                                           class="w-4 h-4 rounded text-[#10A9BC] focus:ring-[#10A9BC]">
                                    <span>Saya telah membaca dan memahami seluruh isi materi / e-book ini</span>
                                </label>

                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs font-extrabold transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                    {{ $isCurrentCompleted ? 'Materi Selesai — Lanjut ke Latihan Soal' : 'Selesaikan Materi & Lanjut ke Latihan Soal' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl border border-[#EBEBEB] p-12 text-center">
                    <h2 class="text-base font-extrabold text-[#2A2A2E]">Materi Belum Tersedia</h2>
                    <p class="text-xs text-[#7E7E81] mt-1">Instruktur sedang menyiapkan modul pembelajaran untuk kursus ini.</p>
                </div>
            @endif

        </div>
    </main>

    {{-- 4. STICKY BOTTOM NAVIGATION FOOTER (class-details-content__footer) --}}
    <footer class="fkksm-player-footer">
        {{-- Left: Konten Kursus Toggle Button with exact Book SVG from Reference --}}
        <div class="flex items-center gap-3">
            <button type="button"
                    id="btnToggleCourseSidebar"
                    onclick="fkksmToggleCourseSidebar()"
                    class="inline-flex items-center justify-center gap-2 px-4 h-10 rounded-lg border border-[#10A9BC] bg-[#F5F5F5] text-[#10A9BC] text-sm font-bold cursor-pointer transition-colors hover:bg-[#E7F6F8]">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="12.3212" y="6.33923" width="4.25595" height="6.19048" fill="#D9D9D9"></rect>
                    <rect x="9.22607" y="5.95239" width="1.54762" height="7.73809" rx="0.77381" fill="#98C93C"></rect>
                    <path d="M17.5446 3.63098H12.9018C12.2861 3.63098 11.6956 3.87556 11.2603 4.31091C10.8249 4.74626 10.5804 5.33673 10.5804 5.95241V12.3168C10.5824 12.4665 10.5276 12.6115 10.4271 12.7225C10.3265 12.8336 10.1877 12.9024 10.0384 12.9152C9.95906 12.9205 9.87944 12.9094 9.80453 12.8826C9.72962 12.8558 9.66102 12.8139 9.60299 12.7595C9.54495 12.7051 9.49873 12.6393 9.46719 12.5662C9.43565 12.4932 9.41947 12.4144 9.41964 12.3349V5.95241C9.41964 5.33673 9.17506 4.74626 8.73971 4.31091C8.30436 3.87556 7.7139 3.63098 7.09821 3.63098H2.45536C2.30144 3.63098 2.15382 3.69213 2.04498 3.80096C1.93614 3.9098 1.875 4.05742 1.875 4.21134V14.6578C1.875 14.8117 1.93614 14.9593 2.04498 15.0681C2.15382 15.177 2.30144 15.2381 2.45536 15.2381H7.67857C8.13958 15.2381 8.58177 15.421 8.90816 15.7465C9.23455 16.0721 9.41849 16.5138 9.41964 16.9748C9.41732 17.0933 9.45179 17.2096 9.51831 17.3077C9.58483 17.4058 9.68013 17.4809 9.79107 17.5226C9.87914 17.5565 9.97418 17.5685 10.0679 17.5575C10.1617 17.5464 10.2513 17.5127 10.3291 17.4591C10.4068 17.4056 10.4704 17.3339 10.5142 17.2503C10.5579 17.1667 10.5807 17.0736 10.5804 16.9792C10.5804 16.5174 10.7638 16.0746 11.0903 15.7481C11.4168 15.4216 11.8597 15.2381 12.3214 15.2381H17.5446C17.6986 15.2381 17.8462 15.177 17.955 15.0681C18.0639 14.9593 18.125 14.8117 18.125 14.6578V4.21134C18.125 4.05742 18.0639 3.9098 17.955 3.80096C17.8462 3.69213 17.6986 3.63098 17.5446 3.63098Z" fill="#0C3553"></path>
                </svg>
                <span>Konten Kursus</span>
            </button>
        </div>

        {{-- Right: Sebelumnya & Selanjutnya Buttons --}}
        <div class="flex items-center gap-2.5 shrink-0">
            @if($prevNav)
                <a href="{{ $prevNav['url'] }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3.5 h-10 rounded-lg border border-[#D9D9D9] bg-white hover:bg-[#F5F5F5] text-[#2A2A2E] text-xs sm:text-sm font-bold transition-colors whitespace-nowrap"
                   style="text-decoration: none;">
                    <svg viewBox="0 0 24 24" fill="none" width="16" height="16">
                        <path d="M15 19l-7-7 7-7" stroke="#57575B" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Sebelumnya</span>
                </a>
            @else
                <button type="button" disabled
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 h-10 rounded-lg border border-[#BCBCBD] bg-[#D8D8D9] text-[#57575B] text-xs sm:text-sm font-bold cursor-not-allowed whitespace-nowrap">
                    <svg viewBox="0 0 24 24" fill="none" width="16" height="16">
                        <path d="M15 19l-7-7 7-7" stroke="#57575B" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Sebelumnya</span>
                </button>
            @endif

            @if($nextNav && !empty($nextNav['unlocked']))
                <a href="{{ $nextNav['url'] }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 h-10 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs sm:text-sm font-bold transition-colors whitespace-nowrap"
                   style="text-decoration: none;">
                    <span>Selanjutnya</span>
                    <svg viewBox="0 0 24 24" fill="none" width="16" height="16">
                        <path d="M9 5l7 7-7 7" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @else
                <button type="button"
                        onclick="fkksmHandleNextAction()"
                        class="inline-flex items-center justify-center gap-1.5 px-4 h-10 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white text-xs sm:text-sm font-bold transition-colors cursor-pointer whitespace-nowrap">
                    <span>Selanjutnya</span>
                    <svg viewBox="0 0 24 24" fill="none" width="16" height="16">
                        <path d="M9 5l7 7-7 7" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            @endif
        </div>
    </footer>

</div>

<script>
    function fkksmToggleCourseSidebar() {
        const shell = document.getElementById('fkksmPlayerShell');
        const btn = document.getElementById('btnToggleCourseSidebar');
        if (!shell) return;
        shell.classList.toggle('hide-sidebar');
        if (btn) {
            btn.classList.toggle('active');
        }
    }

    function fkksmValidateAndSubmitForm(form) {
        if (!form) return;
        const stepType = form.getAttribute('data-step-type');
        const alreadyDone = form.getAttribute('data-already-done') === '1';

        if (stepType === 'video' && !alreadyDone) {
            const cb = document.getElementById('videoWatchConfirmCheckbox');
            if (cb && !cb.checked) {
                window.fkksmModal({
                    icon: 'warning',
                    title: 'Konfirmasi Video Diperlukan',
                    text: 'Silakan tonton video pembelajaran atau centang konfirmasi bahwa Anda telah menyimak video ini sebelum melanjutkan ke tahap Materi.'
                });
                return false;
            }
        }

        if (stepType === 'materi' && !alreadyDone) {
            const cb = document.getElementById('materiReadConfirmCheckbox');
            if (cb && !cb.checked) {
                window.fkksmModal({
                    icon: 'warning',
                    title: 'Konfirmasi Bacaan Materi',
                    text: 'Silakan centang konfirmasi bahwa Anda telah membaca dan memahami materi ini sebelum melanjutkan ke tahap Latihan Soal.'
                });
                return false;
            }
        }

        const questionBlocks = form.querySelectorAll('[data-quiz-question]');
        if (questionBlocks.length > 0) {
            for (let i = 0; i < questionBlocks.length; i++) {
                const idx = questionBlocks[i].getAttribute('data-quiz-question');
                const checked = form.querySelector('input[name="answers[' + idx + ']"]:checked');
                if (!checked) {
                    window.fkksmModal({
                        icon: 'warning',
                        title: 'Jawaban Belum Lengkap',
                        text: 'Harap pilih jawaban untuk Soal nomor ' + (i + 1) + ' sebelum melanjutkan ke tahap berikutnya.'
                    });
                    return false;
                }
            }
        }

        form.submit();
        return true;
    }

    function fkksmHandleNextAction() {
        const form = document.getElementById('activeStepCompleteForm');
        if (form) {
            fkksmValidateAndSubmitForm(form);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('activeStepCompleteForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                fkksmValidateAndSubmitForm(form);
            });
        }

        const video = document.getElementById('fkksmLocalVideoPlayer');
        const videoCheckbox = document.getElementById('videoWatchConfirmCheckbox');
        const videoBadge = document.getElementById('videoWatchStatusBadge');
        if (video && videoCheckbox) {
            video.addEventListener('ended', function () {
                videoCheckbox.checked = true;
                if (videoBadge) {
                    videoBadge.textContent = 'Video selesai diputar — siap lanjut ke tahap Materi';
                }
            });
            video.addEventListener('timeupdate', function () {
                if (video.duration > 0 && (video.currentTime / video.duration) >= 0.8) {
                    videoCheckbox.checked = true;
                }
            });
        }
    });
</script>
@endsection

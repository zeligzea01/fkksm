@php
    $fq = $sections['faq_items'] ?? null;
    $fc = $fq['content'] ?? [];
    $items = $fc['items'] ?? [];
@endphp

@if($fq['is_active'] ?? true)
<!-- Frequently Asked Questions Accordion (Dynamic via Admin CMS) -->
<section class="py-16 bg-[#F8FAFC] border-b border-slate-200" id="faq">
    <div class="max-w-4xl 2xl:max-w-5xl min-[2200px]:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-header
            :badge="$fc['badge'] ?? 'PERTANYAAN UMUM'"
            :title="$fc['heading'] ?? 'Pertanyaan yang Sering Diajukan (FAQ)'"
            :subtitle="$fc['subtitle'] ?? ''"
            align="center"
        />

        <div class="space-y-4">
            @foreach($items as $faq)
                <details class="group bg-white rounded-xl border border-slate-200 p-5 shadow-2xs open:border-[#10A9BC]">
                    <summary class="font-heading font-bold text-base text-[#132B45] cursor-pointer flex items-center justify-between select-none">
                        <span>{{ $faq['question'] ?? '' }}</span>
                        <span class="transition-transform group-open:rotate-180 text-[#10A9BC]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <div class="mt-3 pt-3 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $faq['answer'] ?? '' }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
@endif

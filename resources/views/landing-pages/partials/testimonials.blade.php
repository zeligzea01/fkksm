@php
    $ts = $sections['testimonials'] ?? null;
    $tc = $ts['content'] ?? [];
    $items = $tc['items'] ?? [];
@endphp

@if($ts['is_active'] ?? true)
<!-- Testimonials Section: Real Industrial Voice (Dynamic via Admin CMS) -->
<section class="py-14 bg-[#F8FAFC] border-b border-slate-200">
    <div class="fkksm-container">
        <x-section-header
            :badge="$tc['badge'] ?? 'ULASAN ALUMNI & PRAKTISI'"
            :title="$tc['heading'] ?? 'Apa Kata Rekan Praktisi HR & K3 MM2100?'"
            :subtitle="$tc['subtitle'] ?? ''"
            align="center"
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($items as $idx => $item)
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-500 mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed italic">
                            "{{ $item['quote'] ?? '' }}"
                        </p>
                    </div>
                    <div class="pt-4 mt-6 border-t border-slate-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full {{ $idx % 2 === 1 ? 'bg-[#10A9BC] text-white' : 'bg-[#0C3553] text-[#54D6D0]' }} flex items-center justify-center font-heading font-bold text-xs">
                            {{ $item['initials'] ?? 'AL' }}
                        </div>
                        <div>
                            <strong class="font-heading font-bold text-sm text-[#132B45] block">{{ $item['name'] ?? '' }}</strong>
                            <span class="text-[11px] text-slate-500">{{ $item['role'] ?? '' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

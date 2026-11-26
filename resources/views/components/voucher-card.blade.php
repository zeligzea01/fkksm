@props(['voucher'])

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs hover:shadow-md transition relative flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-3">
            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-[#D8EDF7] text-[#123A5A] uppercase tracking-wider">
                VOUCHER RESMI
            </span>
            <span class="text-[11px] text-slate-400 font-mono">
                S/D {{ $voucher->expires_at ? $voucher->expires_at->format('d M Y') : '31 Des 2026' }}
            </span>
        </div>
        <h4 class="font-heading font-bold text-base text-[#132B45] leading-snug">
            {{ $voucher->title ?? $voucher->name }}
        </h4>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            {{ $voucher->benefit_description ?? $voucher->description ?? 'Gunakan kode voucher ini untuk klaim subsidi pelatihan vokasi dan K3 kawasan MM2100.' }}
        </p>
    </div>

    <div class="pt-4 mt-4 border-t border-dashed border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="font-mono text-xs font-bold bg-slate-100 px-2.5 py-1 rounded text-[#132B45] border border-slate-200">
                {{ $voucher->code }}
            </span>
            <button type="button" onclick="navigator.clipboard.writeText('{{ $voucher->code }}'); window.fkksmModal({ icon: 'success', title: 'Kode Voucher Disalin', text: 'Kode {{ $voucher->code }} berhasil disalin ke clipboard.', confirmButtonText: 'Siap' })" class="text-xs text-[#10A9BC] hover:underline font-bold" title="Salin kode">
                Salin
            </button>
        </div>
        <button type="button" onclick="openVoucherModal('{{ $voucher->code }}')" class="px-3.5 py-1.5 rounded-lg bg-[#10A9BC] hover:bg-[#0E97A8] text-white font-heading font-bold text-xs uppercase tracking-wider transition">
            Klaim
        </button>
    </div>
</div>

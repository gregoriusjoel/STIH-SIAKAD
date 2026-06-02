@props([
    'id',
    'name',
    'label' => '',
    'placeholder' => 'Cari...',
    'labelClass' => 'text-xs font-bold text-[#6B7280] uppercase tracking-wider',
    'containerClass' => 'space-y-2'
])

<div class="{{ $containerClass }}">
    @if($label)
        <label class="{{ $labelClass }}">{{ $label }}</label>
    @endif
    <select name="{{ $name }}" id="{{ $id }}" class="hidden">
        {{ $slot }}
    </select>
    <div x-data="customSelectWrapper('{{ $id }}')" class="relative" x-init="init()">
        <button type="button" @click="if(!disabled) open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
            :disabled="disabled"
            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white text-left flex items-center justify-between disabled:bg-gray-50 disabled:cursor-not-allowed">
            <span x-text="selectedText" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="open" @click.away="open = false; search = ''" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 w-full mt-1 bg-white border border-[#E5E7EB] rounded-xl shadow-lg overflow-hidden" style="display: none;">
            <div class="p-2 border-b border-gray-100 sticky top-0 bg-white">
                <input type="text" x-model="search" x-ref="searchInput" @click.stop @keydown.escape="open = false"
                    placeholder="{{ $placeholder }}"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#8B1538] focus:ring-2 focus:ring-[#8B1538]/10 outline-none">
            </div>
            <div class="max-h-52 overflow-y-auto font-medium">
                <template x-for="opt in filteredOptions" :key="opt.value">
                    <button type="button" @click="selectOption(opt)"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        :class="selected === opt.value ? 'bg-[#8B1538]/5 text-[#8B1538] font-bold' : ''"
                        x-text="opt.text"></button>
                </template>
                <div x-show="filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                    Tidak ada data ditemukan
                </div>
            </div>
        </div>
    </div>
</div>

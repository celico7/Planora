{{-- filepath: resources/views/livewire/avatar-color-picker.blade.php --}}
<div class="relative inline-block">
    <!-- Avatar avec hover (CSS pur) -->
    <div class="relative cursor-pointer group" wire:click="togglePalette">

        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background={{ $currentColor }}&color=fff"
             alt="Avatar"
             class="w-20 h-20 rounded-full shadow transition-all duration-300 group-hover:brightness-75">

        <!-- Icône de crayon qui apparaît au hover (CSS pur) -->
        <div class="absolute inset-0 flex items-center justify-center rounded-full bg-gray-950 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </div>
    </div>

    <!-- Palette de couleurs -->
    @if($showPalette)
    <div class="absolute z-50 mt-2 bg-white rounded-lg shadow-2xl border border-gray-200 p-4 animate-in fade-in zoom-in-95 duration-200"
         style="min-width: 280px;">

        <div class="flex items-center justify-between mb-3 pb-2 border-b">
            <h3 class="text-sm font-semibold text-gray-700">Choisir une couleur</h3>
            <button wire:click="togglePalette" type="button" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-4 gap-3">
            @foreach($colors as $hex => $label)
            <button wire:click="selectColor('{{ $hex }}')"
                    type="button"
                    class="group relative w-12 h-12 rounded-full transition-transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary {{ $currentColor === $hex ? 'ring-2 ring-primary ring-offset-2' : '' }}"
                    style="background-color: #{{ $hex }};"
                    title="{{ $label }}">
                @if($currentColor === $hex)
                <svg class="absolute inset-0 m-auto w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                @endif
            </button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Message de succès -->
    @if (session()->has('message'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-4 right-4 px-4 py-2 bg-green-500 text-white text-sm rounded-md shadow-lg z-50">
        {{ session('message') }}
    </div>
    @endif
</div>

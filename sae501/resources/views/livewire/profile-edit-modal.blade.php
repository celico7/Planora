<div>
    <!-- Message de succès -->
    @if (session()->has('message'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-24 right-4 px-6 py-3 bg-green-500 text-white text-sm rounded-lg shadow-lg z-50">
        <i class="bi bi-check-circle-fill mr-2"></i>{{ session('message') }}
    </div>
    @endif

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto"
         x-data="{ show: true }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/50 transition-opacity"
             wire:click="closeModal"></div>

        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="$wire.closeModal()">

                <!-- Header -->
                <div class="text-black px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h3 class="text-xl font-bold">
                        <i class="bi bi-person-fill-gear mr-2"></i>
                        {{ $updateMode === 'info' ? 'Modifier mon profil' : 'Changer mon mot de passe' }}
                    </h3>
                    <button wire:click="closeModal" class="text-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6">
                    @if($updateMode === 'info')
                        <!-- Formulaire Informations -->
                        <form wire:submit.prevent="updateProfile" class="space-y-4">
                            <!-- Nom -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-person mr-1"></i>Nom<span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       wire:model.blur="name"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                       placeholder="Votre nom">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600"><i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-envelope mr-1"></i>Email<span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       wire:model.blur="email"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                       placeholder="votre@email.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600"><i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-3 mt-6">
                                <button type="submit"
                                        class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#089a8f] transition font-semibold">
                                    <i class="bi bi-check-lg mr-1"></i>Enregistrer
                                </button>
                                <button type="button"
                                        wire:click="closeModal"
                                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                    <i class="bi bi-x-lg mr-1"></i>Annuler
                                </button>
                            </div>

                            <!-- Lien vers changement de mot de passe -->
                            <div class="text-center mt-4">
                                <button type="button"
                                        wire:click="openModal('password')"
                                        class="text-sm text-primary hover:underline">
                                    <i class="bi bi-key mr-1"></i>Changer mon mot de passe
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Formulaire Mot de passe -->
                        <form wire:submit.prevent="updatePassword" class="space-y-4" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                            <!-- Mot de passe actuel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-key mr-1"></i>Mot de passe actuel<span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showCurrent ? 'text' : 'password'"
                                           wire:model.blur="current_password"
                                           class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                           placeholder="••••••••">
                                    <button type="button"
                                            @click="showCurrent = !showCurrent"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="bi" :class="showCurrent ? 'bi-eye-slash' : 'bi-eye'"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600"><i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-shield-lock mr-1"></i>Nouveau mot de passe<span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showNew ? 'text' : 'password'"
                                           wire:model.blur="password"
                                           class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                           placeholder="••••••••">
                                    <button type="button"
                                            @click="showNew = !showNew"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="bi" :class="showNew ? 'bi-eye-slash' : 'bi-eye'"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600"><i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmation -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-shield-check mr-1"></i>Confirmer le mot de passe<span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showConfirm ? 'text' : 'password'"
                                           wire:model.blur="password_confirmation"
                                           class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                           placeholder="••••••••">
                                    <button type="button"
                                            @click="showConfirm = !showConfirm"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="bi" :class="showConfirm ? 'bi-eye-slash' : 'bi-eye'"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600"><i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-3 mt-6">
                                <button type="submit"
                                        class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#089a8f] transition font-semibold">
                                    <i class="bi bi-check-lg mr-1"></i>Mettre à jour
                                </button>
                                <button type="button"
                                        wire:click="closeModal"
                                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                    <i class="bi bi-x-lg mr-1"></i>Annuler
                                </button>
                            </div>

                            <!-- Lien retour -->
                            <div class="text-center mt-4">
                                <button type="button"
                                        wire:click="openModal('info')"
                                        class="text-sm text-primary hover:underline">
                                    <i class="bi bi-arrow-left mr-1"></i>Retour aux informations
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

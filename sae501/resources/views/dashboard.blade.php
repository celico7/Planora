@extends('layouts.app')

@section('title', 'Mon compte')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-dark-bg py-12 px-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-secondary dark:text-primary">Mon compte</h1>
            <a href="{{ route('home') }}"
               class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 dark:hover:bg-primary/80 transition">
                Retour à l'accueil
            </a>
        </div>

        <!-- Carte profil utilisateur -->
        <div class="bg-white dark:bg-dark-card shadow-md dark:shadow-none rounded-lg p-8 mb-10 border-t-4 border-primary dark:border dark:border-dark-border">
            <div class="flex flex-row md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    @livewire('avatar-color-picker')
                    <div>
                        <div class="flex items-center gap-2 group">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-dark-text">{{ Auth::user()->name }}</h2>
                            <button type="button"
                                    x-data
                                    @click="Livewire.dispatch('openProfileModal', { mode: 'info' })"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-gray-400 dark:text-dark-muted hover:text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-gray-500 dark:text-dark-muted">{{ Auth::user()->email }}</p>
                        <p class="mt-2 text-sm text-gray-400 dark:text-dark-muted">Membre depuis le {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-md bg-red-500 dark:bg-red-600 text-white hover:bg-red-600 dark:hover:bg-red-700 transition">
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Section Bienvenue -->
        <div class="mt-12 bg-gradient-blue dark:bg-gradient-to-br dark:from-blue-900/40 dark:to-blue-800/30 rounded-lg p-8 text-white shadow-lg dark:shadow-xl border dark:border-blue-700/30">
            <h2 class="text-2xl font-bold mb-3">Bonjour, {{ Auth::user()->name }}</h2>
            <p class="text-lg text-white/90 dark:text-blue-100">Ravi de vous revoir ! Gérez vos projets, sprints et tâches directement depuis votre tableau de bord. Commencez dès maintenant à faire progresser vos objectifs.</p>
        </div>
    </div>
</div>

@livewire('profile-edit-modal')

@endsection

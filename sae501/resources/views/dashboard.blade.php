@extends('layouts.app')

@section('title', 'Mon compte')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-6">
    <div class="max-w-5xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-secondary">Mon compte</h1>
            <a href="{{ route('profile.edit') }}"
               class="px-4 py-2 rounded-md bg-primary text-white hover:bg-[#089a8f] transition">
                Modifier le profil
            </a>
        </div>

        <!-- Carte profil utilisateur -->
        <div class="bg-white shadow-md rounded-lg p-8 mb-10 border-t-4 border-primary">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0cbaba&color=fff"
                         alt="Avatar"
                         class="w-20 h-20 rounded-full shadow">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="mt-2 text-sm text-gray-400">Membre depuis le {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 rounded-md bg-secondary text-white hover:bg-[#5a0063] transition">
                        Retour à l’accueil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 transition">
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistiques du profil -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-primary">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Projets</h3>
                <p class="text-4xl font-bold text-secondary">
                    {{ \App\Models\Project::where('chef_projet', Auth::id())->count() }}
                </p>
                <p class="text-sm text-gray-400">Projets créés par vous</p>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-primary">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Sprints actifs</h3>
                <p class="text-4xl font-bold text-secondary">
                    {{ \App\Models\Sprint::count() }}
                </p>
                <p class="text-sm text-gray-400">Tous les sprints en cours</p>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-primary">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tâches assignées</h3>
                <p class="text-4xl font-bold text-secondary">
                    {{ \App\Models\Task::where('responsable_id', Auth::id())->count() }}
                </p>
                <p class="text-sm text-gray-400">Tâches attribuées à vous</p>
            </div>
        </div>

        <!-- Section Bienvenue -->
        <div class="mt-12 bg-gradient-to-r from-primary to-[#380036] rounded-lg p-8 text-white shadow-lg">
            <h2 class="text-2xl font-bold mb-3">Bonjour, {{ Auth::user()->name }} 👋</h2>
            <p class="text-lg">Ravi de vous revoir ! Gérez vos projets, sprints et tâches directement depuis votre tableau de bord. Commencez dès maintenant à faire progresser vos objectifs.</p>
        </div>
    </div>
</div>
@endsection

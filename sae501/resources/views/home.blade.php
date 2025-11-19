@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-dark-bg dark:to-dark-card">
    @auth
        <!-- Hero Section -->
        <div class="bg-white dark:bg-dark-card text-gray-800 dark:text-dark-text rounded-2xl py-12 px-6 p-6 rounded-b-3xl shadow-2xl dark:shadow-none dark:border dark:border-dark-border">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background={{ Auth::user()->avatar_color ?? '0cbaba' }}&color=fff"
                             alt="Avatar"
                             class="w-16 h-16 rounded-full shadow-lg">
                        <div>
                            <h1 class="text-4xl font-bold mb-2 dark:text-dark-text">
                                Bonjour, {{ Auth::user()->name }}
                            </h1>
                            <p class="text-gray-600 dark:text-dark-muted text-lg">
                                Bienvenue sur votre tableau de bord de gestion de projets
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('projects.create') }}" class="px-6 py-3 bg-primary dark:bg-primary/90 text-white rounded-lg font-semibold hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-plus-circle"></i>
                            <span>Nouveau Projet</span>
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-white/10 dark:bg-dark-hover backdrop-blur-md rounded-xl p-4 border border-white/20 dark:border-dark-border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-dark-muted text-sm font-medium">Nombre de Projets</p>
                                <p class="text-3xl font-bold text-gray-800 dark:text-dark-text mt-1">{{ auth()->user()->projects->count() }}</p>
                            </div>
                            <div class="bg-white/20 dark:bg-dark-border p-3 rounded-lg">
                                <i class="bi bi-folder-fill text-2xl text-gray-600 dark:text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">
            <!-- Projets Récents -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg dark:shadow-none dark:border dark:border-dark-border p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary dark:bg-primary/90 p-2 rounded-lg">
                            <i class="bi bi-clock-history text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-dark-text">Projets Récents</h2>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-dark-muted bg-gray-100 dark:bg-dark-hover px-3 py-1 rounded-full">
                        {{ auth()->user()->projects->sortByDesc('created_at')->take(5)->count() }} projets
                    </span>
                </div>

                <div class="grid grid-cols md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    @forelse(auth()->user()->projects->sortByDesc('created_at')->take(5) as $project)
                        <div class="group relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-dark-hover dark:to-dark-card rounded-xl p-5 hover:shadow-xl dark:hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-dark-border hover:border-primary">
                            <div class="absolute top-3 right-3">
                                <div class="bg-primary/10 dark:bg-primary/20 text-primary text-xs font-bold px-2 py-1 rounded-full">
                                    Récent
                                </div>
                            </div>

                            <div class="mb-4 pt-4">
                                <div class="bg-primary dark:bg-primary/90 w-12 h-12 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="bi bi-folder-fill text-white text-xl"></i>
                                </div>
                                <h3 class="font-bold text-gray-800 dark:text-dark-text text-lg truncate mb-2">{{ $project->nom }}</h3>
                                <p class="text-sm text-gray-600 dark:text-dark-muted line-clamp-2 min-h-[40px]">{{ $project->description ?? 'Aucune description' }}</p>
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-dark-muted mb-4">
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $project->created_at->format('d/m/Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-people-fill"></i>
                                    {{ $project->users->count() }}
                                </span>
                            </div>

                            <a href="{{ route('projects.show', $project) }}" class="block">
                                <button class="w-full px-4 py-2 bg-gray-800 dark:bg-primary text-white rounded-lg font-semibold hover:bg-primary dark:hover:bg-primary/80 hover:shadow-lg hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                                    <span>Ouvrir</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12">
                            <div class="bg-gray-100 dark:bg-dark-hover rounded-full p-6 mb-4">
                                <i class="bi bi-folder-x text-5xl text-gray-400 dark:text-dark-muted"></i>
                            </div>
                            <p class="text-gray-500 dark:text-dark-muted text-lg font-medium">Aucun projet récent</p>
                            <p class="text-gray-400 dark:text-dark-muted text-sm mb-4">Commencez par créer votre premier projet</p>
                            <a href="{{ route('projects.create') }}" class="px-6 py-2 bg-primary text-white rounded-lg hover:shadow-lg transition">
                                Créer un projet
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tous les Projets -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg dark:shadow-none dark:border dark:border-dark-border p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-secondary dark:bg-secondary/90 p-2 rounded-lg">
                            <i class="bi bi-grid-fill text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-dark-text">Tous les Projets</h2>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-dark-muted bg-gray-100 dark:bg-dark-hover px-3 py-1 rounded-full">
                        {{ auth()->user()->projects->count() }} projets au total
                    </span>
                </div>

                <div class="grid grid-cols md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    @forelse(auth()->user()->projects as $project)
                        <div class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-dark-card dark:to-dark-hover rounded-xl p-5 hover:shadow-xl dark:hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-dark-border hover:border-secondary">
                            <div class="mb-4">
                                <div class="bg-secondary dark:bg-secondary/90 w-12 h-12 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="bi bi-folder2-open text-white text-xl"></i>
                                </div>
                                <h3 class="font-bold text-gray-800 dark:text-dark-text text-lg truncate mb-2">{{ $project->nom }}</h3>
                                <p class="text-sm text-gray-600 dark:text-dark-muted line-clamp-2 min-h-[40px]">{{ $project->description ?? 'Aucune description' }}</p>
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-dark-muted mb-4">
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $project->created_at->format('d/m/Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-people-fill"></i>
                                    {{ $project->users->count() }}
                                </span>
                            </div>

                            <a href="{{ route('projects.show', $project) }}" class="block">
                                <button class="w-full px-4 py-2 bg-gray-800 dark:bg-primary text-white rounded-lg font-semibold hover:bg-primary dark:hover:bg-primary/80 hover:shadow-lg hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                                    <span>Voir le projet</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12">
                            <div class="bg-gray-100 dark:bg-dark-hover rounded-full p-6 mb-4">
                                <i class="bi bi-inbox text-5xl text-gray-400 dark:text-dark-muted"></i>
                            </div>
                            <p class="text-gray-500 dark:text-dark-muted text-lg font-medium">Aucun projet disponible</p>
                            <p class="text-gray-400 dark:text-dark-muted text-sm mb-4">Créez votre premier projet pour commencer</p>
                            <a href="{{ route('projects.create') }}" class="px-6 py-2 bg-primary text-white rounded-lg hover:shadow-lg transition">
                                Créer un projet
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Section Activité Récente -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg dark:shadow-none dark:border dark:border-dark-border p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-green-500 dark:bg-green-600 p-2 rounded-lg">
                        <i class="bi bi-activity text-white text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-dark-text">Activité Récente</h2>
                </div>
                <div class="space-y-3">
                    @forelse(auth()->user()->projects->sortByDesc('updated_at')->take(5) as $project)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-dark-hover rounded-lg dark:bg-dark-card hover:bg-gray-100 dark:hover:bg-dark-border transition">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary/10 dark:bg-primary/20 p-2 rounded-lg">
                                    <i class="bi bi-folder-fill text-primary"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-dark-text">{{ $project->nom }}</p>
                                    <p class="text-sm text-gray-500 dark:text-dark-muted">Modifié {{ $project->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('projects.show', $project) }}" class="text-primary hover:text-secondary transition">
                                <i class="bi bi-arrow-right-circle text-xl"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-dark-muted py-8">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endauth
</div>
@endsection

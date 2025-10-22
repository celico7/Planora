@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="p-4">
    @auth
        <p class="mb-5 text-lg font-semibold">Bonjour {{ Auth::user()->name }}</p>
        
        <div class="space-y-8 bg-gray-100 rounded-md">
            <!-- Projets Récents -->
            <div class="bg-gray-200 p-4 rounded-md">
                <div class="mb-3">
                    <input type="text" value="projets récents" class="text-sm px-3 py-1 rounded bg-white border-none w-48" readonly>
                </div>
                <div class="grid grid-cols-5 gap-6">
                    @forelse(auth()->user()->projects->sortByDesc('created_at')->take(5) as $project)
                        <div class="bg-gray-500 rounded-md h-48 flex flex-col justify-between p-4">
                            <div>
                                <div class="font-bold text-white truncate">{{ $project->nom }}</div>
                                <div class="text-sm text-white/90">{{ $project->description }}</div>
                            </div>
                            <a href="{{ route('projects.show', $project) }}" class="mt-2">
                                <button class="px-3 py-1 bg-white text-gray-700 rounded shadow text-xs font-semibold hover:bg-[#0CBABA] hover:text-white transition">
                                    Voir le projet
                                </button>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-5 text-center text-gray-500">
                            Aucun projet récent.
                        </div>
                    @endforelse
                </div>
            </div>
            <!-- Tous les projets -->
            <div class="bg-gray-200 p-4 rounded-md">
                <div class="mb-3">
                    <input type="text" value="tous les projets" class="text-sm px-3 py-1 rounded bg-white border-none w-48" readonly>
                </div>
                <div class="grid grid-cols-5 gap-6">
                    @forelse(auth()->user()->projects as $project)
                        <div class="bg-gray-500 rounded-md h-48 flex flex-col justify-between p-4">
                            <div>
                                <div class="font-bold text-white truncate">{{ $project->nom }}</div>
                                <div class="text-sm text-white/90">{{ $project->description }}</div>
                            </div>
                            <a href="{{ route('projects.show', $project) }}" class="mt-2">
                                <button class="px-3 py-1 bg-white text-gray-700 rounded shadow text-xs font-semibold hover:bg-[#0CBABA] hover:text-white transition">
                                    Voir le projet
                                </button>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-5 text-center text-gray-500">
                            Aucun projet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endauth
</div>
@endsection

@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="p-4">

<!-- en étant connecté -->
    @auth
        <p>Bonjour {{ Auth::user()->name }}</p>

        <a href="{{ route('projects.create') }}">
            <x-secondary-button>
                Créer un projet
            </x-secondary-button>
        </a>

    <h3>Vos projets récents :</h3>
    @foreach(auth()->user()->projects as $project)
        <strong>{{ $project->nom }}</strong> - {{ $project->description }} <br>
    @endforeach



        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                class="border border-gray-800 rounded-lg">
                Déconnexion
            </button>
        </form>
    @endauth

<!-- en tant que invité -->
    @guest
        <p>
            Bonjour invité !  
            <a href="{{ route('login') }}"
                class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 
                       focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg 
                       text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 
                       dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">
                Se connecter
            </a>
        </p>
    @endguest

    <p class="text-gray-600 dark:text-gray-400 mt-6">
        Gérez vos projets, sprints et tâches facilement avec la méthode Agile.
    </p>
</div>
@endsection

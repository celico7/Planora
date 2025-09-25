@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    
        <div class="p-4">
    @auth
        <p>Bonjour {{ Auth::user()->name }}</p>

        <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Déconnexion</button>
    </form>
    @endauth

    @guest
        <p>Bonjour invité ! <a href="{{ route('login') }}">Se connecter</a></p>
    @endguest
</div>

@endsection
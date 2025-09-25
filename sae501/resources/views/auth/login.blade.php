@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <h2 class="text-2xl font-bold text-green-600 mb-6">Se connecter</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4 max-w-md mx-auto bg-white p-6 rounded shadow">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 p-2" />
            @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input id="password" type="password" name="password" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 p-2" />
            @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center mt-4">
            <a href="{{ route('register') }}" class="text-sm text-green-600 hover:underline">Créer un compte</a>

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 font-semibold px-4 py-2 rounded shadow">
                Connexion
            </button>
        </div>
    </form>
@endsection

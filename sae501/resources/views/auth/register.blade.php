@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <h2 class="text-2xl font-bold text-green-600 mb-6">Créer un compte</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 max-w-md mx-auto bg-white p-6 rounded shadow">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nom*</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 p-2" />
            @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse email*</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 p-2" />
            @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe*</label>
            <input id="password" type="password" name="password" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 p-2" />
            @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe*</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 p-2" />
            @error('password_confirmation')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-green-600 hover:underline">Déjà inscrit ?</a>

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 font-semibold px-4 py-2 rounded shadow">
                Inscription
            </button>
        </div>
    </form>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Planora</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    </head>

    <body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-green-600">Planora</a>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="#" class="text-gray-700 hover:text-green-600">À propos</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-green-600">Contact</a></li>
                    <li class="relative group">
                        <a href="#" class="text-gray-700 flex items-center">
                            <i class="bi bi-person-circle text-xl hover:text-green-600"></i>
                        </a>
                        <ul class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                            @guest
                            <li>
                                <a href="/inscription" class="block px-4 py-2 text-gray-700 hover:text-green-600">S'inscrire</a>
                            </li>
                            <li>
                                <a href="/connexion" class="block px-4 py-2 text-gray-700 hover:text-green-600">Se connecter</a>
                            </li>
                            @endguest
                            @auth
                            <li>
                                <a href="/dashboard" class="block px-4 py-2 text-gray-700 hover:text-green-600">Mon compte</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:text-green-600">
                                        Se déconnecter
                                    </button>
                                </form>
                            </li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main -->
    <main class="flex-1 container px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-4 py-4 text-center text-gray-500">
            &copy; {{ date('Y') }} Célia Hoffmann. Tous droits réservés.
        </div>
    </footer>

</body>
</html>

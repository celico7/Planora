<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Planora</title>
        @livewireStyles
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
        @yield('styles')
    </head>

    <body class="bg-gray-100 min-h-screen flex flex-col @yield('body-class')">
@livewireScripts
<header class="fixed top-0 left-0 z-40 w-full">
    <div class="bg-[rgba(56,0,54,0.90)] backdrop-blur-sm border-b border-[#0cbaba57] shadow-[0_2px_16px_0_#38003657]">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between h-[5rem]">
            <a href="/" class="text-3xl font-extrabold tracking-wide select-none" style="color:#0CBABA;">
                Planora
            </a>
            <nav aria-label="Main navigation">
                <ul class="flex items-center gap-6 text-white">
                    <li><a href="#" class="hover:text-[#0CBABA] transition">À propos</a></li>
                    <li><a href="#" class="hover:text-[#0CBABA] transition">Contact</a></li>
                    <li class="relative group">
                        <button type="button" class="flex items-center gap-2 text-white hover:text-[#0CBABA] focus:outline-none group" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-person-circle text-2xl"></i>
                            <span class="sr-only">Menu utilisateur</span>
                        </button>
                        <ul class="absolute right-0 mt-2 w-44 bg-white text-gray-700 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 opacity-0 invisible group-focus-within:opacity-100 group-hover:opacity-100 group-focus-within:visible group-hover:visible transition-all duration-150 z-50">
                            @guest
                            <li><a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-[#0cbaba]/10 hover:text-[#0cbaba]">S'inscrire</a></li>
                            <li><a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-[#0cbaba]/10 hover:text-[#0cbaba]">Se connecter</a></li>
                            @endguest
                            @auth
                            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-[#0cbaba]/10 hover:text-[#0cbaba]">Mon compte</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-[#0cbaba]/10 hover:text-[#0cbaba]">Se déconnecter</button>
                                </form>
                            </li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

    <!-- Main -->
<main class="pt-24 shadow-lg p-6 relative flex-1 mb-6">
    <div class="max-w-6xl mx-auto">
        @yield('content')
    </div>
</main>

@if (!request()->routeIs('login') && !request()->routeIs('register'))
<aside class="rounded-tr-lg fixed top-20 z-20 flex flex-col items-center gap-6 w-20 h-[calc(100vh-5rem)] bg-gradient-to-b from-[#0CBABA] via-[#0CBABA]/70 to-[#380036]/80 shadow-xl py-6 backdrop-blur-lg border border-[#0CBABA]/30" aria-label="Sidebar navigation"> 
    <nav class="flex-1 flex flex-col gap-6 items-center justify-start mt-4">
        <a href="{{ route('projects.create') }}" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-[#0CBABA]/80 hover:text-white text-[#380036] transition">
            <i class="bi bi-plus-circle-fill text-2xl"></i>
        </a>
        <a href="" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-[#0CBABA]/80 hover:text-white text-[#380036] transition">
            <i class="bi bi-kanban text-2xl"></i>
        </a>
        <a href="" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-[#0CBABA]/80 hover:text-white text-[#380036] transition">
            <i class="bi bi-calendar-event text-2xl"></i>
        </a>
        <a href="" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-[#0CBABA]/80 hover:text-white text-[#380036] transition">
            <i class="bi bi-people-fill text-2xl"></i>
        </a>
        <a href="" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-[#0CBABA]/80 hover:text-white text-[#380036] transition">
            <i class="bi bi-gear-fill text-2xl"></i>
        </a>
    </nav>
</aside>
@endif

</body>
</html>


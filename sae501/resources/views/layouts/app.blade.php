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
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @yield('styles')
</head>

<body class="bg-gray-100 min-h-screen flex flex-col @yield('body-class') relative">
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
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="pt-24 shadow-lg p-6 relative flex-1 mb-6">
    <div class="max-w-6xl mx-auto">
        @yield('content')
    </div>
</main>

@if (!request()->routeIs('login') && !request()->routeIs('register'))
<!-- Sidebar + Drawer Alpine -->
<aside 
    class="rounded-tr-lg fixed top-20 left-0 z-20 flex flex-col items-center gap-6 w-20 h-[calc(100vh-5rem)] bg-gradient-to-b from-[#380036] via-[#380036]/70 to-[#0CBABA]/45 shadow-xl py-6 backdrop-blur-lg overflow-visible"
    aria-label="Sidebar navigation">
    <nav class="flex-1 flex flex-col gap-6 items-center justify-start mt-4">
        <a href="{{ route('projects.create') }}" title="Créer un nouveau projet"
           class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-[#0CBABA]/80 hover:text-white text-[#380036] transition">
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

<!-- Drawer Profil global -->
        <div x-data="{ open: false }" class="relative flex flex-col items-center">
            <!-- Bouton profil (placement en bas de la sidebar) -->
            <button 
                @click="open = !open"
                class="w-12 h-12 flex items-center justify-center rounded-full mb-2 bg-white/60 shadow-md hover:bg-[#0CBABA]/80 hover:text-white text-[#380036] transition cursor-pointer"
                title="Profil"
                id="btn-profile"
            >
                <i class="bi bi-person-circle text-2xl"></i>
            </button>

            <!-- Drawer positionné selon la zone rouge -->
            <div 
                x-show="open" 
                @click.outside="open = false"
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="-translate-x-8 opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition transform ease-in duration-200"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-8 opacity-0"
                class="fixed left-[6.5rem] bottom-12 min-w-[22rem] w-[22rem] bg-white/95 backdrop-blur-lg shadow-2xl rounded-xl border border-[#0CBABA]/30 z-50"
                style="min-height: 22rem;">
                <div class="flex flex-col h-full px-6 py-8 relative">
                    <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-[#0CBABA] text-lg">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    @auth
                        <div class="flex flex-col items-center mb-6">
                            <i class="bi bi-person-circle text-6xl text-[#0CBABA] mb-2"></i>
                            <div class="font-semibold text-xl mb-1">{{ Auth::user()->name }}</div>
                            <div class="text-gray-600 text-sm">{{ Auth::user()->email }}</div>
                        </div>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-[#0CBABA]/10 hover:text-[#0CBABA] transition font-medium">Mon compte</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-[#0CBABA]/10 hover:text-[#0CBABA] transition font-medium">
                                        Se déconnecter
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @endauth
                    @guest
                        <div class="text-center mt-16">
                            <a href="{{ route('login') }}" class="block px-4 py-2 mb-2 rounded bg-[#0CBABA] text-white font-medium hover:bg-[#380036] transition">Se connecter</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 rounded bg-gray-200 hover:bg-[#0CBABA]/20 text-[#380036] font-medium transition">S'inscrire</a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</aside>
@endif

</body>
</html>

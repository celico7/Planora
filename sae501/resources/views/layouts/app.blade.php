<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Planora</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @yield('styles')
</head>

<body class="font-sans antialiased @yield('body-class')">
    <div class="min-h-screen {{ !auth()->check() ? '' : 'bg-gray-100' }}">
        @auth
            @if(!auth()->user()->hasVerifiedEmail())
                <!-- En-tête simplifié pour utilisateurs non vérifiés -->
                <nav class="bg-white border-b border-gray-100">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <span class="font-semibold text-xl text-gray-800">{{ config('app.name', 'Planora') }}</span>
                            </div>
                            <div class="flex items-center">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>
            @else
                <!-- Navigation normale pour utilisateurs vérifiés -->
                <header class="fixed top-0 left-0 z-40 w-full">
                    <div class="bg-secondary backdrop-blur-sm border-b border-[#0cbaba57] shadow-[0_2px_16px_0_#38003657]">
                        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between h-[5rem]">
                            <a href="/" class="text-3xl text-primary font-extrabold tracking-wide select-none">
                                Planora
                            </a>
                            <nav aria-label="Main navigation">
                                <ul class="flex items-center gap-6 text-white">
                                    <li><a href="#" class="hover:text-primary transition">À propos</a></li>
                                    <li><a href="#" class="hover:text-primary transition">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </header>

                @if (!request()->routeIs('login') && !request()->routeIs('register'))
                <!-- Sidebar + Drawer Alpine -->
                <aside
                    class="rounded-tr-lg fixed top-20 left-0 z-20 flex flex-col items-center gap-6 w-20 h-[calc(100vh-5rem)] bg-gradient-to-b from-[#380036] via-[#380036]/70 to-[#0CBABA]/45 shadow-xl py-6 backdrop-blur-lg overflow-visible"
                    aria-label="Sidebar navigation">
                    <nav class="flex-1 flex flex-col gap-6 items-center justify-start mt-4">
                        <a href="{{ route('projects.create') }}" title="Créer un nouveau projet"
                           class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-primary/80 hover:text-white text-secondary transition">
                            <i class="bi bi-plus-circle-fill text-2xl"></i>
                        </a>
                        <a href="{{ route('tasks.search') }}" title="Rechercher des tâches"
                       class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-primary/80 hover:text-white text-secondary transition">
                        <i class="bi bi-search text-2xl"></i>
                        </a>
                        <a href="{{ route('notifications.index') }}"
                           class="relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-primary/80 hover:text-white transition"
                           title="Notifications">
                            <i class="bi bi-bell-fill text-2xl"></i>
                            @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-600 text-white text-[11px] font-bold rounded-full flex items-center justify-center animate-pulse">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        <a href="" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/60 shadow-md hover:bg-primary/80 hover:text-white text-secondary transition">
                            <i class="bi bi-gear-fill text-2xl"></i>
                        </a>

                        <!-- Drawer Profil global -->
                        <div x-data="{ open: false }" class="relative flex flex-col items-center">
                            <button
                                @click="open = !open"
                                class="w-12 h-12 flex items-center justify-center rounded-full mb-2 bg-white/60 shadow-md hover:bg-primary/80 hover:text-white text-secondary transition cursor-pointer"
                                title="Profil"
                                id="btn-profile"
                            >
                                <i class="bi bi-person-circle text-2xl"></i>
                            </button>

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
                                    <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-primary text-lg">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @php $assignee = Auth::user(); @endphp
                                    <div class="flex flex-col items-center mb-6">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee->name) }}&background={{ $assignee->avatar_color ?? '0cbaba' }}&color=fff"
                                        alt="{{ $assignee->name }}" class="w-20 h-20 rounded-full mb-4 object-cover shadow-md">
                                        <div class="font-semibold text-xl mb-1">{{ Auth::user()->name }}</div>
                                        <div class="text-gray-600 text-sm">{{ Auth::user()->email }}</div>
                                    </div>
                                    <ul class="space-y-2">
                                        <li>
                                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-primary/10 hover:text-primary transition font-medium">Mon compte</a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-primary/10 hover:text-primary transition font-medium">
                                                    Se déconnecter
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                </aside>
                @endif
            @endif
        @endauth

        <!-- Page Content -->
        <main class="{{ auth()->check() && auth()->user()->hasVerifiedEmail() && !request()->routeIs('login') && !request()->routeIs('register') ? 'pt-24 ml-20 shadow-lg p-6' : '' }} relative flex-1 mb-6">
            @if(auth()->check() && auth()->user()->hasVerifiedEmail() && !request()->routeIs('login') && !request()->routeIs('register'))
                <div class="max-w-6xl mx-auto">
                    @yield('content')
                </div>
            @else
                @yield('content')
            @endif
        </main>
    </div>

    <!-- Charger Frappe Gantt AVANT Livewire -->
    <script>
        window.ganttLoadedPromise = new Promise((resolve) => {
            const cdnUrls = [
                'https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js',
                'https://unpkg.com/frappe-gantt@0.6.1/dist/frappe-gantt.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.min.js'
            ];

            let currentCdnIndex = 0;

            function tryLoadScript() {
                if (currentCdnIndex >= cdnUrls.length) {
                    console.error('❌ Tous les CDN ont échoué');
                    resolve(false);
                    return;
                }

                const script = document.createElement('script');
                const url = cdnUrls[currentCdnIndex];
                console.log(`⏳ Tentative de chargement depuis: ${url}`);

                script.src = url;

                script.onload = () => {
                    console.log(`✅ Script chargé depuis: ${url}`);
                    setTimeout(() => {
                        if (window.Gantt) {
                            console.log('✅ Frappe Gantt constructeur disponible');
                            window.ganttLoaded = true;
                            resolve(true);
                        } else {
                            console.warn(`⚠️ Script chargé mais Gantt indisponible depuis ${url}`);
                            currentCdnIndex++;
                            tryLoadScript();
                        }
                    }, 150);
                };

                script.onerror = (error) => {
                    console.warn(`⚠️ Échec chargement depuis ${url}:`, error);
                    currentCdnIndex++;
                    tryLoadScript();
                };

                document.body.appendChild(script);
            }

            tryLoadScript();
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>


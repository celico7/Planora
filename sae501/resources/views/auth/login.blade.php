@extends('layouts.app')

@section('body-class', 'login-landing-bg')

@section('styles')
<style>
    .login-landing-bg {
        background: linear-gradient(120deg, #380036 0%, #0CBABA 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }
    .blob, .blob2 {
        position: absolute;
        z-index: 0;
        filter: blur(36px);
        opacity: 0.48;
    }
    .blob {
        top: -100px;
        left: -120px;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle at 35% 40%, #0CBABA 0%, #38003699 60%, #fff0 95%);
        border-radius: 55% 45% 40% 60%/49% 51% 64% 36%;
        animation: pulse 6s infinite alternate;
    }
    .blob2 {
        bottom: -120px;
        right: -120px;
        width: 340px;
        height: 340px;
        background: radial-gradient(circle at 75% 50%, #380036aa 25%, #0CBABA88 65%, #fff0 90%);
        border-radius: 55% 45% 40% 60%/49% 51% 64% 36%;
        animation: pulse2 10s infinite alternate;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: .45; }
        100% { transform: scale(1.10); opacity: .63; }
    }
    @keyframes pulse2 {
        0% { transform: scale(1) rotate(0deg); }
        100% { transform: scale(1.15) rotate(8deg); }
    }
</style>
@endsection

@section('content')
<div class="relative min-h-[85vh] flex flex-col">
    <span class="blob"></span>
    <span class="blob2"></span>
    <!-- Main -->
    <main class="flex-1 flex items-center justify-center pb-8 z-10 w-full">
        <div class="w-full max-w-8xl flex flex-col md:flex-row bg-transparent shadow-none">
            <!-- Bloc visuel Planora à gauche -->
            <div class="flex-1 flex flex-col justify-center items-center text-center py-10 md:py-0 md:px-8 select-none">
                <h1 class="text-5xl md:text-6xl font-extrabold" style="color:#0CBABA;letter-spacing:2px;text-shadow:0 4px 32px #38003699;">
                    Planora
                </h1>
                <p class="text-xl md:text-2xl text-white/85 mb-6 italic font-medium">
                    Organise tes projets grâce à </br> l'application Planora!
                </p>
                <div class="flex gap-2 justify-center mb-3">
                    <span class="inline-block w-2 h-2 rounded-full" style="background:#380036;animation:bounce 1s infinite alternate;"></span>
                    <span class="inline-block w-2 h-2 rounded-full" style="background:#380036;animation:bounce 1.2s infinite alternate;"></span>
                    <span class="inline-block w-2 h-2 rounded-full" style="background:#380036;animation:bounce 1.4s infinite alternate;"></span>
                </div>
            </div>
            <!-- Formulaire à droite -->
            <div class="flex-1 flex items-center justify-center">
                <form method="POST" action="{{ route('login') }}" class="space-y-4 max-w-md w-full mx-auto bg-white/85 p-8 rounded-2xl shadow-2xl z-20 backdrop-blur border-[2px] border-[#0CBABA99]">
                    @csrf
                    <h3 class="text-2xl font-bold mb-4 text-center" style="color:#380036;">Connexion</h3>
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium" style="color:#380036">Adresse email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#0CBABA] focus:ring-[#0CBABA] p-2 bg-white/95" />
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium" style="color:#380036">Mot de passe</label>
                        <input id="password" type="password" name="password" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#0CBABA] focus:ring-[#0CBABA] p-2 bg-white/95" />
                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Remember me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                        class="h-4 w-4" style="accent-color:#0CBABA">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
                    </div>
                    <!-- Actions -->
                    <div class="flex justify-between items-center mt-2">
                        <a href="{{ route('register') }}" class="text-sm" style="color:#0CBABA">Créer un compte</a>
                        <button type="submit"
                            class="bg-[#0CBABA] hover:bg-[#380036] text-white font-semibold px-4 py-2 rounded shadow transition duration-200">
                            Connexion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
    <!-- Footer -->
<footer class="footer-landing w-full py-4 px-0 text-white text-center mt-auto absolute bottom-0 left-0 z-30">
        &copy; {{ date('Y') }} Célia Hoffmann. Tous droits réservés.
</footer>
@endsection

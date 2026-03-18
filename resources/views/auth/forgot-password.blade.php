<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'eCabinet') }} - Mot de passe oublié</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body min-h-screen antialiased">
    <main class="flex min-h-screen w-full">
        <!-- Left Side: Visual Anchor -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center bg-primary">
            <div class="absolute inset-0 z-0">
                <img alt="Medical precision" class="w-full h-full object-cover opacity-60 mix-blend-overlay" src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?q=80&w=2960&auto=format&fit=crop">
            </div>
            <div class="relative z-10 px-16 text-on-primary max-w-xl text-center lg:text-left">
                <div class="mb-8">
                    <span class="text-3xl font-headline font-black tracking-tight text-primary-fixed uppercase">eCabinet</span>
                </div>
                <h2 class="text-4xl font-headline font-bold leading-tight mb-4">La Sérénité pour vos Essentiels.</h2>
                <p class="text-lg text-primary-fixed/80 font-body leading-relaxed">Vivez une interface de gestion haut de gamme—tranquille, organisée et sous un contrôle total de votre inventaire médical.</p>
            </div>
            <div class="absolute bottom-12 left-12 right-12 flex justify-between items-center text-primary-fixed/40 text-xs tracking-widest uppercase font-medium">
                <span>EST. 2024</span>
                <span>PRÉCISION D'INVENTAIRE</span>
            </div>
        </div>

        <!-- Right Side: Reset Form -->
        <div class="w-full lg:w-1/2 flex flex-col bg-surface-container-lowest relative">
            <nav class="fixed top-0 w-full lg:w-1/2 z-50 flex justify-between items-center px-8 py-8 lg:px-12">
                <div class="lg:hidden text-2xl font-headline font-black text-primary tracking-tight uppercase">eCabinet</div>
                <div class="ml-auto">
                    <button class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">help_outline</span>
                    </button>
                </div>
            </nav>

            <div class="flex-grow flex items-center justify-center px-8 lg:px-24">
                <div class="w-full max-w-md">
                    <div class="mb-10 lg:text-left text-center">
                        <h1 class="text-3xl lg:text-4xl font-headline font-bold text-on-surface mb-3 tracking-tight">Mot de passe oublié ?</h1>
                        <p class="text-on-surface-variant font-body leading-relaxed">Aucun problème. Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation.</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div class="space-y-2">
                            <label class="block text-xs font-label font-semibold text-on-surface-variant uppercase tracking-wider ml-1" for="email">Adresse Email</label>
                            <div class="relative group">
                                <input class="w-full px-5 py-4 bg-surface-container-high border-none rounded-lg text-on-surface placeholder:text-outline-variant focus:ring-0 focus:bg-surface-container-lowest focus:shadow-[0_0_0_1px_rgba(0,104,95,0.2)] transition-all duration-200" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nom@etablissement.fr" type="email"/>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-outline-variant group-focus-within:text-primary">
                                    <span class="material-symbols-outlined">mail</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="pt-2">
                            <button class="w-full py-4 px-6 bg-gradient-to-r from-primary to-primary-container text-on-primary font-headline font-bold rounded-lg shadow-[0_10px_30px_-5px_rgba(0,106,97,0.15)] hover:shadow-[0_15px_35px_-5px_rgba(0,106,97,0.25)] hover:scale-[1.01] active:scale-95 transition-all duration-200" type="submit">
                                Envoyer le lien
                            </button>
                        </div>
                    </form>

                    <div class="mt-12 text-center text-sm">
                        <a class="inline-flex items-center gap-2 font-label font-bold text-secondary hover:text-primary transition-colors group" href="{{ route('login') }}">
                            <span class="material-symbols-outlined text-base transition-transform group-hover:-translate-x-1">arrow_back</span>
                            Retour à la connexion
                        </a>
                    </div>
                </div>
            </div>

            <footer class="w-full flex justify-center gap-8 px-12 py-8 bg-transparent">
                <span class="text-[10px] font-label font-medium uppercase tracking-[0.2em] text-slate-400">© {{ date('Y') }} eCabinet. Précision. Contrôle. Sérénité.</span>
            </footer>
        </div>
    </main>

    <!-- Decorative Blurs -->
    <div class="fixed inset-0 pointer-events-none z-[-1] opacity-30">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-primary-fixed/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[30%] h-[30%] rounded-full bg-secondary-fixed/20 blur-[100px]"></div>
    </div>
</body>
</html>

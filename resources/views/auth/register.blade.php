<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'eCabinet') }} - Inscription</title>

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
        .clinical-gradient {
            background: linear-gradient(135deg, #006a61 0%, #008378 100%);
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface antialiased min-h-screen">
    <main class="flex min-h-screen">
        <!-- Left Side: Visual Anchor -->
        <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary">
            <img alt="Medical precision" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-60" src="https://images.unsplash.com/photo-1576091160550-217359f42f8c?q=80&w=2940&auto=format&fit=crop">
            <div class="relative z-10 flex flex-col justify-center px-16 text-on-primary">
                <div class="mb-8">
                    <span class="text-4xl font-headline font-black tracking-tighter uppercase">eCabinet</span>
                </div>
                <h2 class="text-5xl font-headline font-extrabold leading-tight mb-6">
                    Votre santé mérite <br/> une clarté absolue.
                </h2>
                <p class="text-xl font-body opacity-90 max-w-md">
                    Rejoignez des milliers de patients qui gèrent leur inventaire médical avec la sérénité d'un apothicaire moderne.
                </p>
                <div class="mt-12 flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=User+1&background=0F766E&color=fff">
                        <img class="w-10 h-10 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=User+2&background=042F2E&color=fff">
                        <img class="w-10 h-10 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=User+3&background=115E59&color=fff">
                    </div>
                    <span class="text-sm font-medium">Approuvé par des experts de santé</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-64 bg-gradient-to-t from-primary/40 to-transparent"></div>
        </section>

        <!-- Right Side: Registration Form -->
        <section class="w-full lg:w-1/2 flex items-center justify-center bg-surface-container-lowest p-8 md:p-16 lg:p-24 overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="mb-10 text-center lg:text-left">
                    <h1 class="text-3xl font-headline font-bold text-on-surface mb-2">Rejoindre eCabinet</h1>
                    <p class="text-on-surface-variant">Créez votre sanctuaire médical personnel.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant px-1" for="name">Nom Complet</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">person</span>
                            <input class="w-full pl-12 pr-4 py-3.5 bg-surface-container-high rounded-lg border-none focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-slate-400" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Jean Dupont" type="text"/>
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant px-1" for="email">Email</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">mail</span>
                            <input class="w-full pl-12 pr-4 py-3.5 bg-surface-container-high rounded-lg border-none focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-slate-400" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="jean@exemple.com" type="email"/>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant px-1" for="telephone">Téléphone</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">call</span>
                            <input class="w-full pl-12 pr-4 py-3.5 bg-surface-container-high rounded-lg border-none focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-slate-400" id="telephone" name="telephone" value="{{ old('telephone') }}" placeholder="+33 6 12 34 56 78" type="tel"/>
                        </div>
                        <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                    </div>

                    <!-- Passwords Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant px-1" for="password">Mot de passe</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">lock</span>
                                <input class="w-full pl-12 pr-4 py-3.5 bg-surface-container-high rounded-lg border-none focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-slate-400" id="password" name="password" required autocomplete="new-password" placeholder="••••••••" type="password"/>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant px-1" for="password_confirmation">Confirmation</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">verified_user</span>
                                <input class="w-full pl-12 pr-4 py-3.5 bg-surface-container-high rounded-lg border-none focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-slate-400" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" type="password"/>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1">
                         <x-input-error :messages="$errors->get('password')" class="mt-2" />
                         <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <button class="w-full py-4 clinical-gradient text-on-primary font-headline font-bold rounded-lg shadow-lg shadow-primary/10 hover:shadow-primary/20 transition-all active:scale-95 mt-4" type="submit">
                        Créer mon compte
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-on-surface-variant">
                        Déjà un compte ? 
                        <a class="text-secondary font-semibold hover:underline decoration-2 underline-offset-4 ml-1" href="{{ route('login') }}">
                            Se connecter
                        </a>
                    </p>
                </div>

                <!-- Trust Badges -->
                <div class="mt-12 pt-8 flex flex-wrap justify-center gap-6 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">shield</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Sécurisé SSL</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">health_and_safety</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest">HDS Compliant</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Logo Floating (Mobile Only) -->
    <div class="lg:hidden fixed top-6 left-8 z-50">
        <span class="text-primary font-headline font-black text-2xl tracking-tight uppercase">eCabinet</span>
    </div>
</body>
</html>

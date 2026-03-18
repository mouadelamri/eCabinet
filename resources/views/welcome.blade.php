<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'eCabinet') }} - Login</title>

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
            vertical-align: middle;
        }
        .teal-overlay {
            background: linear-gradient(135deg, rgba(0, 106, 97, 0.7) 0%, rgba(0, 101, 145, 0.4) 100%);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body min-h-screen antialiased">
    <main class="flex min-h-screen">
        <!-- Left Side: Visual Sanctuary -->
        <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary">
            <div class="absolute inset-0 z-0">
                <img alt="Modern medical office with wooden accents and soft lighting" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCj7N5WmuttgacaCBQroaZJIRFOIsb-c16XLIOjQbWNR2W-rKdEvW8yjEhl5rBjMQRAiAU7q6t1vg4hwOCOPMGWtMU0qlUHpP4lz0py5K4is288A8avVL_zhLvvbjz5vrz3rgtodSJAq2WFrPQNowIDhWnTUPg3n6Uiz4IPeHYC-Ww9FhyVzxTjO-FZYgCNUE5DXgVUV_LPxbtRurgpBArvlfGtmMAc4OYv2Byk-hVCT_uSbVkz-NPyrXUBi6E-LDXDVfPwHvu4P3ac"/>
                <div class="absolute inset-0 teal-overlay mix-blend-multiply"></div>
            </div>
            <div class="relative z-10 flex flex-col justify-between p-16 w-full">
                <div>
                    <span class="text-on-primary-container font-headline font-black text-3xl tracking-tight uppercase">eCabinet</span>
                </div>
                <div class="glass-panel p-10 rounded-xl max-w-lg">
                    <h2 class="font-headline text-on-primary-container text-4xl font-bold mb-4 leading-tight">La gestion clinique, réinventée.</h2>
                    <p class="text-primary-fixed text-lg leading-relaxed opacity-90">
                        Entrez dans un espace de travail serein conçu pour la précision et le contrôle de votre inventaire médical.
                    </p>
                </div>
                <div class="flex gap-4 items-center">
                    <div class="flex -space-x-2">
                        <img alt="Doctor profile" class="w-10 h-10 rounded-full border-2 border-on-primary" src="https://ui-avatars.com/api/?name=Dr+Ahmed&background=0F766E&color=fff&bold=true">
                        <img alt="Doctor profile" class="w-10 h-10 rounded-full border-2 border-on-primary" src="https://ui-avatars.com/api/?name=Dr+Sarah&background=042F2E&color=fff&bold=true">
                    </div>
                    <p class="text-on-primary-container text-sm font-medium tracking-wide">Rejoignez 2,000+ professionnels de santé</p>
                </div>
            </div>
        </section>

        <!-- Right Side: Clean Logic -->
        <section class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-16 bg-surface-container-lowest">
            <div class="w-full max-w-md space-y-10">
                <div class="space-y-3">
                    <h1 class="font-headline text-3xl font-extrabold text-on-surface tracking-tight">C'est un plaisir de vous revoir</h1>
                    <p class="text-on-surface-variant font-medium">Veuillez renseigner vos identifiants pour accéder à votre espace eCabinet.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-on-surface-variant ml-1" for="email">Email</label>
                        <div class="relative">
                            <input class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3.5 focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all duration-200 outline-none placeholder:text-slate-400" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nom@etablissement.fr" type="email"/>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="block text-sm font-semibold text-on-surface-variant" for="password">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-bold text-secondary hover:text-primary transition-colors" href="{{ route('password.request') }}">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <input class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3.5 focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all duration-200 outline-none placeholder:text-slate-400" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" type="password"/>
                            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center space-x-3 px-1">
                        <input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" id="remember_me" name="remember" type="checkbox"/>
                        <label class="text-sm font-medium text-on-surface-variant cursor-pointer select-none" for="remember_me">
                            Se souvenir de moi
                        </label>
                    </div>

                    <!-- Primary Action -->
                    <div class="pt-4">
                        <button class="w-full bg-gradient-to-r from-primary to-primary-container text-on-primary py-4 px-6 rounded-lg font-headline font-bold text-lg shadow-lg shadow-primary/10 hover:shadow-primary/20 hover:scale-[1.01] active:scale-[0.98] transition-all duration-200" type="submit">
                            Se connecter
                        </button>
                    </div>
                </form>

                <!-- Alternative Action -->
                @if (Route::has('register'))
                    <div class="text-center pt-8 border-t border-surface-container-high">
                        <p class="text-on-surface-variant text-sm font-medium">
                            Pas encore de compte ? 
                            <a class="text-primary font-bold hover:underline ml-1 underline-offset-4" href="{{ route('register') }}">
                                S'inscrire
                            </a>
                        </p>
                    </div>
                @endif

                <!-- Footer Metadata -->
                <div class="pt-12 flex justify-center gap-6">
                    <a class="text-xs font-medium text-slate-400 uppercase tracking-widest hover:text-primary transition-colors" href="#">Vie Privée</a>
                    <a class="text-xs font-medium text-slate-400 uppercase tracking-widest hover:text-primary transition-colors" href="#">Support</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Logo Floating (Mobile Only) -->
    <div class="lg:hidden fixed top-6 left-8 z-50">
        <span class="text-primary font-headline font-black text-2xl tracking-tight">eCabinet</span>
    </div>
</body>
</html>

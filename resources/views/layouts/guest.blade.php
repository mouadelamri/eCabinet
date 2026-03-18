<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'eCabinet') }} - Auth</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased selection:bg-teal-500 selection:text-white">
        <div class="min-h-screen flex bg-white">
            <!-- Left Side - Visual -->
            <div class="hidden lg:flex lg:w-[45%] xl:w-1/2 relative bg-teal-900 overflow-hidden">
                <!-- Background Image -->
                <img src="https://images.unsplash.com/photo-1551076805-e18690c5e451?q=80&w=3000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay" alt="Clinic Room">
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-teal-900/80 to-teal-800/80"></div>

                <!-- Top Logo -->
                <div class="absolute top-10 left-12">
                    <span class="text-white text-3xl font-bold tracking-tight">eCabinet</span>
                </div>
                
                <!-- Centered Glassmorphism Card -->
                <div class="absolute inset-0 flex flex-col items-center justify-center p-12">
                    <div class="w-full max-w-lg bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-10 shadow-2xl transition-transform hover:scale-[1.02] duration-300">
                        <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight tracking-tight">
                            La gestion clinique,<br>réinventée.
                        </h1>
                        <p class="text-teal-50/90 text-lg leading-relaxed font-medium">
                            Entrez dans un espace de travail serein conçu pour la précision et le contrôle de votre inventaire médical.
                        </p>
                    </div>
                </div>
                
                <!-- Bottom Social Proof -->
                <div class="absolute bottom-10 left-12 flex items-center space-x-4">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-teal-800 shadow-md" src="https://ui-avatars.com/api/?name=Dr+Ahmed&background=0F766E&color=fff&bold=true" alt="Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-teal-800 shadow-md" src="https://ui-avatars.com/api/?name=Dr+Sarah&background=042F2E&color=fff&bold=true" alt="Avatar">
                    </div>
                    <span class="text-white/90 text-sm font-medium tracking-wide">Rejoignez 2,000+ professionnels de santé</span>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-[55%] xl:w-1/2 flex flex-col items-center bg-white p-8 sm:p-12 lg:p-24 relative">
                <!-- Mobile Logo -->
                <div class="lg:hidden absolute top-8 left-8">
                    <span class="text-teal-800 text-2xl font-bold tracking-tight">eCabinet</span>
                </div>

                <div class="w-full max-w-[28rem] mt-16 md:mt-24 lg:mt-0 lg:my-auto">
                    {{ $slot }}
                </div>
                
                <!-- Footer Links -->
                <div class="mt-auto pt-16 flex justify-center space-x-8 text-xs font-semibold text-gray-400 uppercase tracking-widest w-full max-w-[28rem]">
                    <a href="#" class="hover:text-teal-600 transition-colors duration-200">Privacy Policy</a>
                    <a href="#" class="hover:text-teal-600 transition-colors duration-200">Contact Support</a>
                </div>
            </div>
        </div>
    </body>
</html>

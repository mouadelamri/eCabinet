<!DOCTYPE html>
<html class="{{ auth()->user()->appearance_mode ?? 'light' }}" lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-secondary-fixed-variant": "#004c6e",
                    "primary": "#00685f",
                    "outline-variant": "#bcc9c6",
                    "on-primary-fixed": "#00201d",
                    "secondary-fixed": "#c9e6ff",
                    "background": "#f7f9fb",
                    "inverse-surface": "#2d3133",
                    "surface-dim": "#d8dadc",
                    "on-secondary-container": "#004666",
                    "surface-variant": "#e0e3e5",
                    "on-tertiary": "#ffffff",
                    "surface": "#f7f9fb",
                    "primary-container": "#008378",
                    "inverse-primary": "#6bd8cb",
                    "tertiary-fixed": "#ffdbce",
                    "surface-container": "#eceef0",
                    "on-tertiary-fixed": "#370e00",
                    "tertiary-fixed-dim": "#ffb59a",
                    "surface-bright": "#f7f9fb",
                    "on-tertiary-container": "#fffbff",
                    "secondary-fixed-dim": "#89ceff",
                    "surface-container-highest": "#e0e3e5",
                    "primary-fixed": "#89f5e7",
                    "surface-container-lowest": "#ffffff",
                    "on-error-container": "#93000a",
                    "tertiary-container": "#b05e3d",
                    "on-primary-container": "#f4fffc",
                    "on-background": "#191c1e",
                    "outline": "#6d7a77",
                    "error": "#ba1a1a",
                    "tertiary": "#924628",
                    "surface-container-low": "#f2f4f6",
                    "on-primary": "#ffffff",
                    "on-tertiary-fixed-variant": "#773215",
                    "on-surface-variant": "#3d4947",
                    "on-secondary-fixed": "#001e2f",
                    "surface-tint": "#006a61",
                    "on-secondary": "#ffffff",
                    "error-container": "#ffdad6",
                    "inverse-on-surface": "#eff1f3",
                    "secondary-container": "#39b8fd",
                    "secondary": "#006591",
                    "on-surface": "#191c1e",
                    "surface-container-high": "#e6e8ea",
                    "primary-fixed-dim": "#6bd8cb",
                    "on-primary-fixed-variant": "#005049",
                    "on-error": "#ffffff"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "fontFamily": {
                    "headline": ["Manrope"],
                    "body": ["Inter"],
                    "label": ["Inter"]
            }
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        /* Dark mode overrides for background and text if needed beyond Tailwind class */
        .dark body { background-color: #191c1e; color: #f7f9fb; }
        .dark header, .dark aside { background-color: #1c2022 !important; border-color: rgba(255,255,255,0.1) !important; }
        .dark .bg-surface-container-lowest { background-color: #232729 !important; }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen flex overflow-hidden dark:bg-slate-950 dark:text-slate-100">

<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-full w-64 bg-slate-50 dark:bg-slate-900 flex flex-col py-8 border-r border-slate-200 dark:border-slate-800 font-manrope tracking-normal text-[14px]">
<div class="px-6 py-6 mb-4">
    <x-logo subtitle="Espace Médecin" />
</div>
<nav class="flex-1 space-y-1">
<a class="flex items-center {{ request()->routeIs('doctor.dashboard') ? 'bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-400 font-bold rounded-l-full ml-4 pl-4 shadow-sm' : 'text-slate-600 dark:text-slate-400 px-8 hover:text-teal-600 hover:pl-10' }} py-3 transition-all duration-300 ease-in-out" href="{{ route('doctor.dashboard') }}">
<span class="material-symbols-outlined mr-4">dashboard</span>
<span>Tableau de bord</span>
</a>
<a class="flex items-center {{ request()->routeIs('doctor.schedule') ? 'bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-400 font-bold rounded-l-full ml-4 pl-4 shadow-sm' : 'text-slate-600 dark:text-slate-400 px-8 hover:text-teal-600 hover:pl-10' }} py-3 transition-all duration-300 ease-in-out" href="{{ route('doctor.schedule') }}">
<span class="material-symbols-outlined mr-4">calendar_today</span>
<span>Planning</span>
</a>
<a class="flex items-center {{ request()->routeIs('doctor.patients.*') ? 'bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-400 font-bold rounded-l-full ml-4 pl-4 shadow-sm' : 'text-slate-600 dark:text-slate-400 px-8 hover:text-teal-600 hover:pl-10' }} py-3 transition-all duration-300 ease-in-out" href="{{ route('doctor.patients.index') }}">
<span class="material-symbols-outlined mr-4">group</span>
<span>Patients</span>
</a>

<a class="flex items-center {{ request()->routeIs('doctor.settings') ? 'bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-400 font-bold rounded-l-full ml-4 pl-4 shadow-sm' : 'text-slate-600 dark:text-slate-400 px-8 hover:text-teal-600 hover:pl-10' }} py-3 transition-all duration-300 ease-in-out" href="{{ route('doctor.settings') }}">
<span class="material-symbols-outlined mr-4">settings</span>
<span>Paramètres</span>
</a>
<a class="flex items-center {{ request()->routeIs('doctor.profile') ? 'bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-400 font-bold rounded-l-full ml-4 pl-4 shadow-sm' : 'text-slate-600 dark:text-slate-400 px-8 hover:text-teal-600 hover:pl-10' }} py-3 transition-all duration-300 ease-in-out" href="{{ route('doctor.profile') }}">
<span class="material-symbols-outlined mr-4">account_circle</span>
<span>Profil</span>
</a>
</nav>

<div class="px-6 mt-auto">
    <div class="p-4 bg-teal-50 dark:bg-teal-900/20 rounded-2xl mb-4">
        <div class="flex items-center gap-3">
            <img src="{{ auth()->user()->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover border-2 border-primary/20">
            <div class="min-w-0">
                <p class="text-xs font-bold text-teal-900 dark:text-teal-100 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-teal-700/70 dark:text-teal-400 truncate">{{ auth()->user()->specialiste ?? 'Médecin' }}</p>
            </div>
        </div>
    </div>
</div>
</aside>

<!-- Main Content Area -->
<main class="ml-64 flex-1 flex flex-col h-screen overflow-hidden">
    <header class="w-full sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl flex justify-between items-center px-8 py-3 flex-shrink-0 border-b border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">medical_services</span>
            <span class="text-sm font-semibold text-on-surface-variant font-manrope dark:text-slate-300">@yield('page-title', 'Espace Médecin')</span>
        </div>
        <div class="flex items-center gap-4">
            @php
                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('est_lu', false)->count();
            @endphp
            <div class="relative group">
                @if($unreadCount > 0)
                    <span class="absolute top-2 right-2 w-4 h-4 bg-error text-white text-[10px] flex items-center justify-center rounded-full animate-pulse">{{ $unreadCount }}</span>
                @endif
            </div>
            <div class="flex items-center gap-3 pl-4 border-l border-slate-200 dark:border-slate-800">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-on-surface dark:text-slate-100">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-on-surface-variant dark:text-slate-400">{{ Auth::user()->specialiste ?? 'Spécialiste' }}</p>
                </div>
                <img src="{{ auth()->user()->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover border-2 border-primary-fixed">
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Déconnexion">
                        <span class="material-symbols-outlined mt-1">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto">
        @yield('content')
    </div>
</main>

</body>
</html>

<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'eCabinet - Espace Patient')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-high": "#e6e8ea",
                        "error": "#ba1a1a",
                        "inverse-primary": "#6bd8cb",
                        "surface-bright": "#f7f9fb",
                        "on-primary-fixed": "#00201d",
                        "on-surface-variant": "#3d4947",
                        "on-tertiary-fixed-variant": "#773215",
                        "outline": "#6d7a77",
                        "tertiary-fixed": "#ffdbce",
                        "surface-container-highest": "#e0e3e5",
                        "on-tertiary": "#ffffff",
                        "on-primary-fixed-variant": "#005049",
                        "on-tertiary-container": "#fffbff",
                        "inverse-on-surface": "#eff1f3",
                        "tertiary-container": "#b05e3d",
                        "on-tertiary-fixed": "#370e00",
                        "surface-container": "#eceef0",
                        "surface": "#f7f9fb",
                        "on-error-container": "#93000a",
                        "on-surface": "#191c1e",
                        "on-primary-container": "#f4fffc",
                        "secondary-fixed-dim": "#89ceff",
                        "primary-container": "#008378",
                        "on-background": "#191c1e",
                        "on-error": "#ffffff",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#006a61",
                        "on-secondary-container": "#004666",
                        "inverse-surface": "#2d3133",
                        "primary-fixed": "#89f5e7",
                        "background": "#f7f9fb",
                        "tertiary": "#924628",
                        "tertiary-fixed-dim": "#ffb59a",
                        "surface-variant": "#e0e3e5",
                        "on-secondary-fixed-variant": "#004c6e",
                        "on-secondary-fixed": "#001e2f",
                        "surface-dim": "#d8dadc",
                        "primary": "#00685f",
                        "outline-variant": "#bcc9c6",
                        "secondary": "#006591",
                        "surface-container-lowest": "#ffffff",
                        "on-primary": "#ffffff",
                        "secondary-fixed": "#c9e6ff",
                        "primary-fixed-dim": "#6bd8cb",
                        "error-container": "#ffdad6",
                        "surface-container-low": "#f2f4f6",
                        "secondary-container": "#39b8fd"
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
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Manrope', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }
        .nav-active {
            background-color: white !important;
            color: #0d9488 !important; /* teal-600 */
        }
        .dark .nav-active {
            background-color: #0f172a !important; /* slate-900 */
            color: #2dd4bf !important; /* teal-400 */
        }
    </style>
    @yield('head')
</head>
<body class="bg-background text-on-surface">

<div class="flex min-h-screen">

    <!-- SideNavBar -->
    <aside class="hidden md:flex flex-col h-screen w-64 bg-slate-50 dark:bg-slate-950 p-4 space-y-2 sticky top-0 border-r-0">
        <div class="mb-8 px-2">
            <h1 class="text-2xl font-black text-teal-700 dark:text-teal-500 font-headline">eCabinet</h1>
            <p class="text-xs font-medium text-slate-500 font-headline uppercase tracking-widest">Portail Secretary </p>
        </div>

        <nav class="space-y-1">
            <a href="{{ route('secretary.dashboard') }}"
               class="flex items-center space-x-3 px-4 py-3 shadow-sm rounded-lg font-headline font-medium transition-transform duration-200 {{ request()->routeIs('patient.dashboard') ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400' : 'text-slate-600 dark:text-slate-400 hover:text-teal-500 hover:translate-x-1' }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('secretary.patients') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg font-headline font-medium transition-transform duration-200 {{ request()->routeIs('patient.appointments*') ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-teal-500 hover:translate-x-1' }}">
                <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
                <span>Patients</span>
            </a>

            <a href="{{ route('secretary.PendingrendezVous') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg font-headline font-medium transition-transform duration-200 {{ request()->routeIs('patient.dossier') ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-teal-500 hover:translate-x-1' }}">
                <span class="material-symbols-outlined" data-icon="folder_shared">folder_shared</span>
                <span>Pending rendez-vous</span>
            </a>

            <a href="{{ route('secretary.ConfirmedrendezVous') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg font-headline font-medium transition-transform duration-200 {{ request()->routeIs('patient.dossier') ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-teal-500 hover:translate-x-1' }}">
                <span class="material-symbols-outlined" data-icon="folder_shared">folder_shared</span>
                <span>Confirmed rendez-vous</span>
            </a>

            <a href="{{ route('secretary.CancelledrendezVous') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg font-headline font-medium transition-transform duration-200 {{ request()->routeIs('patient.dossier') ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-teal-500 hover:translate-x-1' }}">
                <span class="material-symbols-outlined" data-icon="folder_shared">folder_shared</span>
                <span>Cancelled rendez-vous</span>
            </a>

            <a href="{{ route('secretary.parametres') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg font-headline font-medium transition-transform duration-200 {{ request()->routeIs('patient.settings') ? 'bg-white dark:bg-slate-900 text-teal-600 dark:text-teal-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-teal-500 hover:translate-x-1' }}">
                <span class="material-symbols-outlined" data-icon="settings">settings</span>
                <span>settings</span>
            </a>


        </nav>

        <div class="mt-auto p-4 bg-surface-container rounded-xl">
            <div class="flex items-center space-x-3">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? 'secretaire' }}</p>
                    <p class="text-xs text-on-surface-variant truncate">secretary</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 text-xs font-bold text-error hover:underline">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
<main class="flex-1 min-w-0">

    <!-- TopNavBar -->
    <header class="flex items-center justify-between px-6 py-3 w-full sticky top-0 z-50 glass-header shadow-sm shadow-teal-900/5">

        <!-- Left -->
        <div class="flex items-center md:hidden">
            <h1 class="text-xl font-bold text-teal-700 font-headline">
                eCabinet
            </h1>
        </div>

        <!-- Right -->
        <div class="ml-auto flex items-center space-x-4">

            <img
                src="{{ auth()->user()->profile_photo_url }}"
                alt="{{ auth()->user()->name }}"
                class="w-8 h-8 rounded-full object-cover shadow-sm"
            >

        </div>

    </header>
        <div class="p-6 lg:p-10 space-y-8 max-w-7xl mx-auto">
            @if(session('success'))
            <div class="bg-surface-container p-4 rounded-xl shadow-sm border-l-4 border-primary flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">check_circle</span>
                <span class="text-sm font-bold text-on-surface">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-surface-container p-4 rounded-xl shadow-sm border-l-4 border-error flex items-center gap-3">
                <span class="material-symbols-outlined text-error">error</span>
                <span class="text-sm font-bold text-on-surface">{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </div>
    </main>


</div>

@yield('scripts')
</body>
</html>

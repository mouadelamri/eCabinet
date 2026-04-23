<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'eCabinet - Global Overview')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
          tailwind.config = {
            darkMode: "class",
            theme: {
              extend: {
                colors: {
                  "surface-bright": "#f7f9fb",
                  "tertiary-fixed-dim": "#ffb59a",
                  "surface-dim": "#d8dadc",
                  "surface-variant": "#e0e3e5",
                  "outline": "#6d7a77",
                  "on-tertiary-fixed": "#370e00",
                  "on-surface-variant": "#3d4947",
                  "on-primary-fixed": "#00201d",
                  "on-background": "#191c1e",
                  "surface-container-low": "#f2f4f6",
                  "primary-container": "#008378",
                  "on-secondary-fixed": "#001e2f",
                  "on-error-container": "#93000a",
                  "primary-fixed-dim": "#6bd8cb",
                  "on-secondary-fixed-variant": "#004c6e",
                  "inverse-on-surface": "#eff1f3",
                  "on-tertiary": "#ffffff",
                  "on-surface": "#191c1e",
                  "on-tertiary-fixed-variant": "#773215",
                  "inverse-surface": "#2d3133",
                  "tertiary": "#924628",
                  "secondary-fixed-dim": "#89ceff",
                  "secondary": "#006591",
                  "outline-variant": "#bcc9c6",
                  "error": "#ba1a1a",
                  "surface-container-highest": "#e0e3e5",
                  "surface-container-high": "#e6e8ea",
                  "secondary-fixed": "#c9e6ff",
                  "error-container": "#ffdad6",
                  "surface-container": "#eceef0",
                  "surface": "#f7f9fb",
                  "surface-tint": "#006a61",
                  "on-error": "#ffffff",
                  "on-primary-fixed-variant": "#005049",
                  "on-primary-container": "#f4fffc",
                  "secondary-container": "#39b8fd",
                  "on-secondary-container": "#004666",
                  "primary": "#00685f",
                  "tertiary-fixed": "#ffdbce",
                  "on-primary": "#ffffff",
                  "background": "#f7f9fb",
                  "on-tertiary-container": "#fffbff",
                  "primary-fixed": "#89f5e7",
                  "tertiary-container": "#b05e3d",
                  "on-secondary": "#ffffff",
                  "inverse-primary": "#6bd8cb",
                  "surface-container-lowest": "#ffffff"
                },
                fontFamily: {
                  "headline": ["Manrope"],
                  "body": ["Inter"],
                  "label": ["Inter"]
                },
              },
            },
          }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .chart-gradient {
            background: linear-gradient(180deg, rgba(0, 106, 97, 0.1) 0%, rgba(0, 106, 97, 0) 100%);
        }
    </style>
</head>
<body class="bg-background font-body text-on-background selection:bg-primary-fixed selection:text-on-primary-fixed">

<!-- Sidebar Navigation -->
<nav class="h-screen w-64 fixed left-0 top-0 border-r-0 bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-[10px_0_30px_-5px_rgba(13,148,136,0.05)] flex flex-col p-4 gap-2 z-50">
    <div class="flex items-center gap-3 px-3 py-6 mb-4">
        <x-logo />
    </div>

    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300' : 'text-slate-500 hover:text-teal-600 hover:bg-slate-200/50' }} font-semibold rounded-lg transition-all duration-300 scale-98 active:scale-95">
        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
        <span class="font-manrope text-sm font-medium tracking-tight">Overview</span>
    </a>

    <a href="{{ route('admin.doctors') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.doctors') ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300' : 'text-slate-500 hover:text-teal-600 hover:bg-slate-200/50' }} font-semibold rounded-lg transition-all duration-300 scale-98 active:scale-95">
        <span class="material-symbols-outlined" data-icon="medical_services">medical_services</span>
        <span class="font-manrope text-sm font-medium tracking-tight">Doctors</span>
    </a>

    <a href="{{ route('admin.secretaries') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.secretaries') ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300' : 'text-slate-500 hover:text-teal-600 hover:bg-slate-200/50' }} font-semibold rounded-lg transition-all duration-300 scale-98 active:scale-95">
        <span class="material-symbols-outlined" data-icon="support_agent">support_agent</span>
        <span class="font-manrope text-sm font-medium tracking-tight">Secretaries</span>
    </a>

    <a href="{{ route('admin.patients') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.patients') ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300' : 'text-slate-500 hover:text-teal-600 hover:bg-slate-200/50' }} font-semibold rounded-lg transition-all duration-300 scale-98 active:scale-95">
        <span class="material-symbols-outlined" data-icon="group">group</span>
        <span class="font-manrope text-sm font-medium tracking-tight">Patients</span>
    </a>

    <div class="mt-auto">
        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.settings') ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300' : 'text-slate-500 hover:text-teal-600 hover:bg-slate-200/50' }} font-semibold rounded-lg transition-all duration-300 scale-98 active:scale-95">
            <span class="material-symbols-outlined" data-icon="settings">settings</span>
            <span class="font-manrope text-sm font-medium tracking-tight">Settings</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:text-red-600 hover:bg-red-50 transition-all duration-300 w-full rounded-lg">
                <span class="material-symbols-outlined" data-icon="logout">logout</span>
                <span class="font-manrope text-sm font-medium tracking-tight">Logout</span>
            </button>
        </form>
    </div>
</nav>

<!-- Top Navigation Bar -->
<header class="fixed top-0 right-0 w-[calc(100%-16rem)] h-16 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md flex items-center px-8 z-40">
    <div class="ml-auto flex items-center gap-6">

        <!-- Divider -->
        <div class="h-8 w-[1px] bg-outline-variant/30"></div>

        <!-- User -->
        <div class="flex items-center gap-3">
            
            <div class="text-right">
                <p class="text-xs font-bold text-on-surface dark:text-white">
                    {{ auth()->user()->name ?? 'Dr. Admin' }}
                </p>
                <p class="text-[10px] text-on-surface-variant dark:text-slate-400 font-medium">
                    Administrator
                </p>
            </div>

            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold border-2 border-primary/10">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>

        </div>

    </div>
</header>

<!-- Main Content -->
<main class="ml-64 pt-24 px-8 pb-12 min-h-screen">
    @yield('content')
</main>

@yield('scripts')

</body>
</html>

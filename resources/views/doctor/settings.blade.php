@extends('layouts.doctor')

@section('page-title', 'Paramètres Système')

@section('content')
<div class="p-8 max-w-4xl mx-auto space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-on-surface dark:text-white">Préférences du portail</h2>
        <p class="text-sm text-on-surface-variant dark:text-slate-400">Personnalisez votre interface de travail et vos notifications.</p>
    </div>

    <div class="space-y-6">
        <!-- Appearance Section -->
        <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-bold text-on-surface dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">palette</span>
                Apparence & Thème
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Light Mode Card -->
                <button onclick="updateTheme('light')" class="appearance-card p-4 rounded-2xl border-2 transition-all text-left flex items-start gap-4 {{ $user->appearance_mode == 'light' ? 'border-primary bg-primary/5' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300' }}">
                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-orange-500">
                        <span class="material-symbols-outlined">light_mode</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-on-surface dark:text-white">Mode Clair</p>
                        <p class="text-xs text-on-surface-variant dark:text-slate-400">Thème par défaut optimisé pour le jour.</p>
                    </div>
                    @if($user->appearance_mode == 'light')
                    <span class="material-symbols-outlined text-primary">check_circle</span>
                    @endif
                </button>

                <!-- Dark Mode Card -->
                <button onclick="updateTheme('dark')" class="appearance-card p-4 rounded-2xl border-2 transition-all text-left flex items-start gap-4 {{ $user->appearance_mode == 'dark' ? 'border-primary bg-primary/5' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300' }}">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 shadow-sm flex items-center justify-center text-teal-400">
                        <span class="material-symbols-outlined">dark_mode</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-on-surface dark:text-white">Mode Sombre (Black Appearance)</p>
                        <p class="text-xs text-on-surface-variant dark:text-slate-400">Optimisé pour réduire la fatigue oculaire.</p>
                    </div>
                    @if($user->appearance_mode == 'dark')
                    <span class="material-symbols-outlined text-primary">check_circle</span>
                    @endif
                </button>
            </div>
        </div>

        <!-- Export & Security -->
        <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-bold text-on-surface dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">security</span>
                Données & Sécurité
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-surface-container-low dark:bg-slate-800 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <span class="material-symbols-outlined">download</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface dark:text-white">Sauvegarde Locale</p>
                            <p class="text-[11px] text-on-surface-variant dark:text-slate-400">Gérez vos préférences d'exportation de données.</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 text-xs font-bold text-primary hover:bg-primary/5 rounded-lg transition-colors">Gérer</button>
                </div>

                <div class="flex items-center justify-between p-4 bg-surface-container-low dark:bg-slate-800 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                            <span class="material-symbols-outlined">lock</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface dark:text-white">Changer le mot de passe</p>
                            <p class="text-[11px] text-on-surface-variant dark:text-slate-400">Dernière modification il y a 3 mois.</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-xs font-bold text-primary hover:bg-primary/5 rounded-lg transition-colors">Modifier</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function updateTheme(mode) {
    try {
        const response = await fetch("{{ route('doctor.settings.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ appearance_mode: mode })
        });
        
        if (response.ok) {
            // Update document class immediately
            document.documentElement.className = mode;
            // Reload to apply backend state to UI components
            window.location.reload();
        }
    } catch (error) {
        console.error('Error updating theme:', error);
    }
}
</script>
@endsection

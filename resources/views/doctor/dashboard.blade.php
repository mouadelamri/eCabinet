@extends('layouts.doctor')

@section('page-title', 'Tableau de bord')

@section('content')
<div class="p-8 space-y-8">
    <!-- Urgent Notifications Bar (Dynamic placeholder for now) -->
    <div class="flex items-center gap-4 p-4 bg-tertiary-fixed rounded-2xl border border-tertiary/10 shadow-sm">
        <div class="w-10 h-10 rounded-full bg-tertiary/10 flex items-center justify-center text-tertiary">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">warning</span>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-on-tertiary-fixed">Rappel de Sécurité</p>
            <p class="text-xs text-on-tertiary-fixed-variant">Vérifiez vos protocoles d'alerte. 3 dossiers en attente de signature.</p>
        </div>
        <button class="px-4 py-2 bg-tertiary text-on-tertiary text-xs font-bold rounded-lg hover:opacity-90 transition-opacity">
            Consulter
        </button>
    </div>

    <!-- Header Section -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-extrabold font-headline text-on-surface dark:text-white tracking-tight">Bonjour, Dr. {{ explode(' ', auth()->user()->name)[0] }}</h2>
            <p class="text-on-surface-variant dark:text-slate-400 font-medium mt-1">Voici le résumé de votre activité pour ce {{ now()->translatedFormat('l d F') }}.</p>
        </div>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-surface-container-low dark:bg-slate-800 text-on-surface-variant dark:text-slate-300 text-xs font-semibold rounded-full flex items-center gap-2">
                <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                En ligne
            </span>
        </div>
    </div>

    <!-- Bento Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div class="md:col-span-1 bg-surface-container-lowest dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-500"></div>
            <p class="text-xs font-bold text-on-surface-variant dark:text-slate-400 uppercase tracking-widest mb-4">RDV du jour</p>
            <h3 class="text-5xl font-extrabold font-headline text-primary dark:text-teal-400">{{ count($todayAppointments) }}</h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-primary dark:text-teal-500">
                <span class="material-symbols-outlined text-xs">calendar_today</span>
                Consulter l'agenda
            </div>
        </div>
        <div class="md:col-span-1 bg-surface-container-lowest dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-500"></div>
            <p class="text-xs font-bold text-on-surface-variant dark:text-slate-400 uppercase tracking-widest mb-4">Patients vus (Mois)</p>
            <h3 class="text-5xl font-extrabold font-headline text-secondary dark:text-blue-400">{{ $monthlyPatientsCount }}</h3>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-on-surface-variant dark:text-slate-500">
                <span class="material-symbols-outlined text-xs">check_circle</span>
                Activité mensuelle
            </div>
        </div>
        <div class="md:col-span-2 bg-gradient-to-br from-primary to-primary-container p-6 rounded-2xl shadow-xl shadow-primary/20 text-on-primary flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-white/70 uppercase tracking-widest mb-2">Taux d'occupation</p>
                <h3 class="text-4xl font-extrabold font-headline">{{ $occupancyRate }}%</h3>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-xs font-medium">
                    <span>Capacité de consultation</span>
                    <span>{{ $occupancyRate > 80 ? 'Optimal' : 'Disponible' }}</span>
                </div>
                <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white w-[{{ $occupancyRate }}%] rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)] transition-all duration-1000"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area: Appointments & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Appointments Section -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h4 class="text-xl font-bold font-headline text-on-surface dark:text-white">Prochains Rendez-vous</h4>
                <a href="{{ route('doctor.schedule') }}" class="text-sm font-semibold text-primary dark:text-teal-400 hover:underline">Voir tout le planning</a>
            </div>
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="divide-y divide-surface-container dark:divide-slate-800">
                    @forelse($todayAppointments as $appointment)
                    <div class="p-5 flex items-center gap-6 hover:bg-surface-container-low dark:hover:bg-slate-800 transition-colors group">
                        <div class="text-center min-w-[60px]">
                            <p class="text-lg font-extrabold font-headline text-primary dark:text-teal-400">{{ $appointment->date_heure->format('H:i') }}</p>
                            <p class="text-[10px] font-bold text-on-surface-variant dark:text-slate-500 uppercase">{{ $appointment->date_heure->format('A') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 overflow-hidden flex items-center justify-center">
                            @if($appointment->patient->profile_photo_path)
                                <img src="{{ asset('storage/'.$appointment->patient->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-primary">{{ substr($appointment->patient->name, 0, 2) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-on-surface dark:text-slate-200">{{ $appointment->patient->name }}</p>
                            <p class="text-xs text-on-surface-variant dark:text-slate-400">{{ $appointment->motif ?? 'Consultation' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($appointment->statut === 'PENDING' || $appointment->statut === 'EN_ATTENTE')
                            <form action="{{ route('doctor.rendezvous.confirm', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-primary text-on-primary text-[10px] font-bold rounded-lg hover:opacity-90 transition-opacity">
                                    Confirmer
                                </button>
                            </form>
                            @endif
                            <span class="px-3 py-1 {{ $appointment->statut === 'CONFIRMED' ? 'bg-teal-100 text-teal-700' : 'bg-secondary-fixed text-on-secondary-fixed' }} text-[10px] font-bold rounded-full uppercase tracking-tighter">{{ $appointment->statut }}</span>
                            <a href="{{ route('doctor.patients.show', $appointment->patient_id) }}" class="p-2 text-slate-300 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-20">event_busy</span>
                        <p>Aucun rendez-vous pour aujourd'hui.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Activity Feed Section -->
        <div class="space-y-6">

            
            <!-- Small Inventory Card -->
            <div class="bg-surface-container-highest dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant dark:text-slate-400">Stock Critique</p>
                    <span class="material-symbols-outlined text-tertiary text-sm">inventory_2</span>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-medium dark:text-slate-200">Fournitures Médicales</span>
                        <span class="text-tertiary font-bold">A vérifier</span>
                    </div>
                    <div class="h-1 bg-white/50 dark:bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-tertiary w-1/5 rounded-full"></div>
                    </div>
                    <a href="{{ route('doctor.inventory') }}" class="block w-full text-center mt-2 py-2 text-[10px] font-bold text-primary dark:text-teal-400 border border-primary/20 rounded-lg hover:bg-primary/5 transition-colors">
                        Gérer le stock
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

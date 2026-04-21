@extends('patient.layout')

@section('title', 'Tableau de bord — eCabinet')

@section('content')
<!-- Welcome Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-4xl font-extrabold text-on-surface font-headline tracking-tight">Bonjour, {{ strtok($user->name, ' ') }}</h2>
        <p class="text-on-surface-variant mt-2 font-body">Voici un aperçu de votre santé aujourd'hui.</p>
    </div>
    <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-primary-container text-on-primary rounded-xl font-semibold shadow-lg shadow-primary/10 hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined mr-2" data-icon="add">add</span>
        Prendre rendez-vous
    </a>
</div>

<!-- Bento Grid Layout -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-6">

    <!-- Prochain Rendez-vous - High Priority -->
    <div class="md:col-span-7 bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-teal-600/5 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-sm font-bold text-teal-700 uppercase tracking-widest font-headline">Prochain Rendez-vous</h3>
                @php $nextRdv = $upcomingAppointments->first(); @endphp
                @if($nextRdv)
                    <p class="text-2xl font-bold mt-1">
                        {{ $nextRdv->medecin ? 'Dr. ' . $nextRdv->medecin->name : 'Consultation' }}
                    </p>
                    <p class="text-on-surface-variant font-body">{{ $nextRdv->motif ?? 'Médecine Générale' }}</p>
                @else
                    <p class="text-2xl font-bold mt-1">Aucun RDV</p>
                    <p class="text-on-surface-variant font-body">Vous n'avez pas de rendez-vous à venir</p>
                @endif
            </div>

            @if($nextRdv && $nextRdv->statut === 'CONFIRMED')
                <span class="px-3 py-1 bg-secondary-fixed text-on-secondary-fixed text-xs font-bold rounded-full uppercase tracking-tighter">CONFIRMED</span>
            @elseif($nextRdv && $nextRdv->statut === 'PENDING')
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full uppercase tracking-tighter">EN ATTENTE</span>
            @endif
        </div>

        @if($nextRdv)
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 p-4 bg-surface-container-low rounded-xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined" data-icon="event">event</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase font-bold">Date</p>
                        <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($nextRdv->date_heure)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined" data-icon="schedule">schedule</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase font-bold">Heure</p>
                        <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($nextRdv->date_heure)->format('H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 col-span-2 lg:col-span-1">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined" data-icon="task_alt">task_alt</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase font-bold">État</p>
                        <p class="text-sm font-bold">{{ $nextRdv->statut == 'PENDING' ? 'En validation' : 'Confirmé' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex gap-3">
                <button class="flex-1 py-2 text-sm font-semibold text-error hover:bg-error-container/20 transition-colors rounded-lg border border-error/20">Annuler</button>
            </div>
        @else
            <div class="p-4 bg-surface-container-low rounded-xl text-center py-8">
                <span class="material-symbols-outlined text-4xl text-outline mb-2 object-center" data-icon="event_busy">event_busy</span>
                <p class="font-bold text-on-surface">Planifiez votre santé</p>
                <p class="text-sm text-on-surface-variant mb-4">La prévention est au cœur de la médecine.</p>
                <a href="{{ route('patient.appointments.create') }}" class="px-4 py-2 bg-primary text-on-primary rounded-lg text-sm font-bold shadow-sm">Nouveau RDV</a>
            </div>
        @endif
    </div>

    <!-- Notifications Area -->
    <div class="md:col-span-5 bg-surface-container-low rounded-xl p-6 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-headline font-bold text-on-surface">Notifications</h3>
            <button class="text-xs font-bold text-primary">Tout marquer comme lu</button>
        </div>
        <div class="space-y-3 flex-1 overflow-y-auto">
            @forelse($recentNotifications as $notif)
                @php
                    $icon = 'info';
                    $color = 'tertiary';
                    if($notif->type === 'CONFIRMATION') { $icon = 'check_circle'; $color = 'primary'; }
                    if($notif->type === 'REMINDER') { $icon = 'notification_important'; $color = 'secondary'; }
                    if($notif->type === 'ALERTE') { $icon = 'warning'; $color = 'error'; }
                @endphp
                <div class="p-3 bg-surface-container-lowest rounded-lg border-l-4 border-{{ $color }} flex gap-3 {{ $notif->est_lu ? 'opacity-60' : '' }}">
                    <span class="material-symbols-outlined text-{{ $color }} text-xl" data-icon="{{ $icon }}">{{ $icon }}</span>
                    <div>
                        <p class="text-sm font-bold">{{ $notif->type }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $notif->message }}</p>
                        <p class="text-[10px] text-outline mt-1 italic">{{ \Carbon\Carbon::parse($notif->sent_at)->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-10 opacity-40">
                    <span class="material-symbols-outlined text-4xl mb-2" data-icon="notifications_off">notifications_off</span>
                    <p class="text-xs font-bold uppercase tracking-widest">Aucune notification</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Constantes Vitales - Metrics Section -->
    <div class="md:col-span-12">
        <h3 class="font-headline font-extrabold text-xl mb-4">Constantes Vitales</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            
            <!-- Blood Pressure -->
            <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="p-2 bg-secondary-fixed/30 rounded-lg text-secondary">
                        <span class="material-symbols-outlined" data-icon="monitor_heart">monitor_heart</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest">Pression Artérielle</p>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-2xl font-black font-headline">12/8</span>
                        <span class="text-xs text-outline">mmHg</span>
                    </div>
                </div>
                <div class="mt-4 h-8 w-full flex items-end space-x-1">
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-1/2"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-3/4"></div>
                    <div class="flex-1 bg-primary rounded-t-sm h-2/3"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-5/6"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-1/2"></div>
                </div>
            </div>

            <!-- Heart Rate -->
            <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="p-2 bg-tertiary-fixed/30 rounded-lg text-tertiary">
                        <span class="material-symbols-outlined" data-icon="favorite">favorite</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest">Fréquence Cardiaque</p>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-2xl font-black font-headline">72</span>
                        <span class="text-xs text-outline">bpm</span>
                    </div>
                </div>
                <div class="mt-4 h-8 w-full flex items-end space-x-1">
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-2/3"></div>
                    <div class="flex-1 bg-tertiary rounded-t-sm h-5/6"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-3/4"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-2/3"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-1/2"></div>
                </div>
            </div>

            <!-- Weight -->
            <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="p-2 bg-primary-fixed/30 rounded-lg text-primary">
                        <span class="material-symbols-outlined" data-icon="scale">scale</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest">Poids</p>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-2xl font-black font-headline">75</span>
                        <span class="text-xs text-outline">kg</span>
                    </div>
                </div>
                <div class="mt-4 h-8 w-full flex items-end space-x-1">
                    <div class="flex-1 bg-primary rounded-t-sm h-5/6"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-4/5"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-3/4"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-2/3"></div>
                    <div class="flex-1 bg-surface-container-high rounded-t-sm h-2/3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique Rapide -->
    <div class="md:col-span-12 bg-surface-container-lowest rounded-xl p-8 border border-outline-variant/10">
        <div class="flex justify-between items-center mb-8">
            <h3 class="font-headline font-extrabold text-xl">Historique Rapide</h3>
            <a href="{{ route('patient.appointments') }}" class="text-primary font-bold text-sm flex items-center hover:underline">
                Voir tout l'historique
                <span class="material-symbols-outlined text-sm ml-1" data-icon="arrow_forward">arrow_forward</span>
            </a>
        </div>
        
        <div class="space-y-0">
            @forelse($recentAppointments->where('statut', 'COMPLETED') as $rdv)
                <div class="flex items-center justify-between p-4 hover:bg-surface-container-low rounded-xl transition-colors cursor-pointer group">
                    <div class="flex items-center space-x-4">
                        <div class="text-center w-12 flex flex-col">
                            <span class="text-xs font-black text-outline uppercase">{{ \Carbon\Carbon::parse($rdv->date_heure)->translatedFormat('M') }}</span>
                            <span class="text-lg font-bold">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('d') }}</span>
                        </div>
                        <div class="h-10 w-px bg-outline-variant/30"></div>
                        <div>
                            <p class="font-bold group-hover:text-primary transition-colors">Dr. {{ $rdv->medecin ? $rdv->medecin->name : 'N/A' }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $rdv->motif }}</p>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center space-x-2">
                        <span class="px-3 py-1 bg-surface-container text-on-surface-variant text-[10px] font-bold rounded-full uppercase">Terminé</span>
                        <a href="{{ route('patient.dossier') }}" class="p-2 text-outline hover:text-primary"><span class="material-symbols-outlined" data-icon="open_in_new">open_in_new</span></a>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-on-surface-variant">
                    Aucun historique de consultation pour le moment.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@extends('patient.layout')

@section('title', 'Mes Rendez-vous — eCabinet')

@section('content')
<!-- Page Header & Filters -->
<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
    <div>
        <h2 class="text-4xl font-extrabold text-on-surface font-headline tracking-tight mb-2">Mes Rendez-vous</h2>
        <p class="text-on-surface-variant max-w-lg">Gérez vos consultations médicales et suivez l'historique de vos visites au cabinet.</p>
    </div>
    
    <div class="flex p-1 bg-surface-container-high rounded-xl w-fit">
        <a href="{{ route('patient.appointments') }}" class="px-6 py-2 text-sm {{ request('filter') == '' ? 'font-semibold bg-white shadow-sm text-primary' : 'font-medium text-on-surface-variant hover:text-on-surface' }} rounded-lg transition-all">Tous</a>
        <a href="{{ route('patient.appointments', ['filter' => 'upcoming']) }}" class="px-6 py-2 text-sm {{ request('filter') == 'upcoming' ? 'font-semibold bg-white shadow-sm text-primary' : 'font-medium text-on-surface-variant hover:text-on-surface' }} rounded-lg transition-all">À venir</a>
        <a href="{{ route('patient.appointments', ['filter' => 'past']) }}" class="px-6 py-2 text-sm {{ request('filter') == 'past' ? 'font-semibold bg-white shadow-sm text-primary' : 'font-medium text-on-surface-variant hover:text-on-surface' }} rounded-lg transition-all">Passés</a>
    </div>
</div>

<!-- Dashboard Bento Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
            <span class="material-symbols-outlined" data-icon="event_available">event_available</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Prochain rendez-vous</p>
            @php $nextRdv = $appointments->whereIn('statut', ['PENDING', 'CONFIRMED'])->first(); @endphp
            <p class="text-lg font-bold text-on-surface leading-tight">
                {{ $nextRdv ? \Carbon\Carbon::parse($nextRdv->date_heure)->translatedFormat('D d M, H:i') : 'Aucun rendez-vous' }}
            </p>
        </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined" data-icon="medical_services">medical_services</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Médecin principal</p>
            <p class="text-lg font-bold text-on-surface leading-tight">
                {{ $nextRdv && $nextRdv->medecin ? 'Dr. ' . $nextRdv->medecin->name : 'Non défini' }}
            </p>
        </div>
    </div>
    <a href="{{ route('patient.appointments.create') }}" class="bg-primary bg-gradient-to-br from-primary to-primary-container p-6 rounded-xl shadow-lg shadow-primary/20 flex items-center justify-between group hover:opacity-90 transition-opacity cursor-pointer">
        <div class="text-white">
            <p class="text-xs font-medium opacity-80 uppercase tracking-wider">Prendre rendez-vous</p>
            <p class="text-lg font-bold leading-tight">Nouvelle consultation</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined" data-icon="add">add</span>
        </div>
    </a>
</div>

<!-- Appointments List Table -->
<div class="bg-surface-container-low rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-surface-container-high/50">
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest">Date & Heure</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest">Médecin</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest">Motif</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest">Statut</th>
                    <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/5">
                @forelse($appointments as $rdv)
                <tr class="hover:bg-white transition-colors group">
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex flex-col items-center justify-center text-primary font-bold shadow-sm">
                                <span class="text-[10px] leading-none opacity-60 uppercase">{{ \Carbon\Carbon::parse($rdv->date_heure)->translatedFormat('M') }}</span>
                                <span class="text-lg leading-none">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('d') }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface leading-tight">{{ \Carbon\Carbon::parse($rdv->date_heure)->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-on-surface-variant">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('H:i') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-xs font-bold">
                                {{ substr($rdv->medecin ? $rdv->medecin->name : 'N', 0, 1) }}
                            </div>
                            <p class="font-semibold text-on-surface">Dr. {{ $rdv->medecin ? $rdv->medecin->name : 'N/A' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <span class="px-3 py-1 bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold rounded-full uppercase tracking-tighter">{{ $rdv->motif ?? 'Généraliste' }}</span>
                    </td>
                    <td class="px-6 py-6">
                        @if($rdv->statut === 'CONFIRMED')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary text-on-primary text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                CONFIRMÉ
                            </span>
                        @elseif($rdv->statut === 'PENDING')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-teal-600/30 text-teal-600 text-xs font-bold">
                                EN ATTENTE
                            </span>
                        @elseif($rdv->statut === 'COMPLETED')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-on-surface text-xs font-bold">
                                TERMINÉ
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error-container text-error text-xs font-bold">
                                ANNULÉ
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="px-4 py-2 text-sm font-bold text-primary hover:bg-primary/5 rounded-lg transition-colors">Détails</button>
                            @if(in_array($rdv->statut, ['PENDING', 'CONFIRMED']))
                                <button class="px-4 py-2 text-sm font-bold text-error border border-error/20 hover:bg-error/5 rounded-lg transition-colors">Annuler</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-on-surface-variant">
                        Vous n'avez aucun rendez-vous pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer / Pagination -->
    @if($appointments->hasPages())
    <div class="px-6 py-4 bg-surface-container-high/30">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection

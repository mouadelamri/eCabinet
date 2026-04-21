@extends('patient.layout')

@section('title', 'Dossier Médical — eCabinet')

@section('content')
<!-- Header Section -->
<div class="max-w-7xl mx-auto w-full">
    <h2 class="text-3xl font-headline font-extrabold text-on-surface tracking-tight">Dossier Médical</h2>
    <p class="text-on-surface-variant font-body mt-1">Consultez vos constantes, historique et documents officiels.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-8">
    
    <!-- Visualisation du Progrès Section & Consultations -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Profil Santé Section -->
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-error/10 flex items-center justify-center rounded-lg">
                        <span class="material-symbols-outlined text-error">health_and_safety</span>
                    </div>
                    <h3 class="font-headline text-lg font-bold">Mon Profil Santé</h3>
                </div>
                <a href="{{ route('patient.settings') }}" class="text-xs font-bold text-primary hover:underline">Modifier</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mb-1">Âge</p>
                    <p class="font-medium text-sm text-on-surface">{{ $user->date_naissance ? \Carbon\Carbon::parse($user->date_naissance)->age . ' ans' : 'Non précisé' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mb-1">Groupe Sanguin</p>
                    <p class="font-bold text-error text-sm">{{ $user->groupe_sanguin ?? 'Inconnu' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mb-1">N° Sécurité Sociale</p>
                    <p class="font-medium text-sm text-on-surface">{{ $user->numero_securite_sociale ?? 'Non renseigné' }}</p>
                </div>
                <div class="md:col-span-3">
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider mb-2 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">history_edu</span> Antécédents Médicaux</p>
                    <p class="text-sm bg-surface-container-high/30 p-3 rounded-lg border border-outline-variant/10 text-on-surface whitespace-pre-line">{{ $user->antecedents_medicaux ?? 'Aucun antécédent signalé.' }}</p>
                </div>
                @if($user->allergies)
                <div class="md:col-span-3">
                    <p class="text-[10px] text-error uppercase font-bold tracking-wider mb-2 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">warning</span> Allergies</p>
                    <p class="text-sm bg-error-container/10 p-3 rounded-lg border border-error/20 text-red-700 dark:text-red-400 font-medium whitespace-pre-line">{{ $user->allergies }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-fixed flex items-center justify-center rounded-lg">
                        <span class="material-symbols-outlined text-primary">show_chart</span>
                    </div>
                    <h3 class="font-headline text-lg font-bold">Visualisation du Progrès</h3>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-surface-container-high rounded-full text-xs font-medium text-on-surface-variant">6 derniers mois</span>
                </div>
            </div>

            <!-- Charts Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Tension Chart Card -->
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tension Artérielle</p>
                            @php
                                $latestConsultation = $consultations->whereNotNull('tension')->first();
                            @endphp
                            <p class="text-2xl font-bold text-primary">{{ $latestConsultation ? current(explode(' ', $latestConsultation->tension)) : '--/--' }} <span class="text-sm font-normal text-on-surface-variant">mmHg</span></p>
                        </div>
                        <span class="text-[10px] text-teal-600 bg-teal-50 px-2 py-0.5 rounded font-bold">-2% vs fév.</span>
                    </div>
                    <div class="h-32 w-full flex items-end gap-2 px-1">
                        @php
                            $tensionData = $consultations->whereNotNull('tension')->take(6)->reverse();
                            $hasTension = $tensionData->isNotEmpty();
                        @endphp
                        
                        @if($hasTension)
                            @foreach($tensionData as $item)
                                @php
                                    $values = explode('/', $item->tension);
                                    $systolic = (int)($values[0] ?? 120);
                                    $height = min(90, max(20, ($systolic / 200) * 100)); // Scaled height
                                @endphp
                                <div class="bg-primary flex-1 rounded-t-sm relative group transition-all hover:bg-primary-container" style="height: {{ $height }}%">
                                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity z-20">{{ $item->tension }}</div>
                                </div>
                            @endforeach
                            @for($i = count($tensionData); $i < 6; $i++)
                                <div class="bg-surface-container-high flex-1 rounded-t-sm h-[20%] opacity-30"></div>
                            @endfor
                        @else
                            @for($i = 0; $i < 6; $i++)
                                <div class="bg-surface-container-high flex-1 rounded-t-sm h-[30%] opacity-20"></div>
                            @endfor
                        @endif
                    </div>
                </div>

                <!-- Poids Chart Card -->
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Poids Corporel</p>
                            @php
                                $weightConsultation = $consultations->whereNotNull('poids')->first();
                            @endphp
                            <p class="text-2xl font-bold text-secondary">{{ $weightConsultation ? rtrim($weightConsultation->poids, 'kg') : '--' }} <span class="text-sm font-normal text-on-surface-variant">kg</span></p>
                        </div>
                        <span class="text-[10px] text-secondary bg-secondary-fixed/30 px-2 py-0.5 rounded font-bold">Stable</span>
                    </div>
                    <div class="h-32 w-full relative flex items-center bg-surface-container-high/10 rounded-lg p-2">
                        @php
                            $poidsData = $consultations->whereNotNull('poids')->take(5)->reverse();
                            $hasPoids = $poidsData->count() >= 2;
                        @endphp
                        
                        @if($hasPoids)
                            <svg class="w-full h-full overflow-visible" viewBox="0 0 400 100">
                                @php
                                    $points = [];
                                    $maxWeight = $poidsData->max(fn($i) => (float)$i->poids) ?: 100;
                                    $minWeight = $poidsData->min(fn($i) => (float)$i->poids) ?: 0;
                                    $range = max(10, $maxWeight - $minWeight);
                                    $i = 0;
                                    foreach($poidsData as $item) {
                                        $w = (float)$item->poids;
                                        $x = ($i / 4) * 400;
                                        $y = 90 - (($w - $minWeight) / $range) * 80;
                                        $points[] = "$x,$y";
                                        $i++;
                                    }
                                @endphp
                                <path d="M {{ implode(' L ', $points) }}" fill="none" stroke="#006591" stroke-linecap="round" stroke-width="3"></path>
                                @foreach($points as $p)
                                    @php list($x, $y) = explode(',', $p); @endphp
                                    <circle cx="{{ $x }}" cy="{{ $y }}" fill="#006591" r="4" stroke="white" stroke-width="1"></circle>
                                @endforeach
                            </svg>
                        @else
                            <div class="flex flex-col items-center justify-center w-full h-full opacity-40">
                                <span class="material-symbols-outlined text-outline">query_stats</span>
                                <p class="text-[10px] uppercase font-bold text-outline">Données insuffisantes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des Consultations Section -->
        <div class="bg-surface-container-low rounded-xl p-6">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-secondary-fixed/50 flex items-center justify-center rounded-lg">
                    <span class="material-symbols-outlined text-secondary">history</span>
                </div>
                <h3 class="font-headline text-lg font-bold">Historique des Consultations</h3>
            </div>
            
            <div class="relative space-y-8 before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-outline-variant/30">
                @forelse($consultations as $consultation)
                <div class="relative pl-10">
                    <div class="absolute left-0 top-1.5 w-6 h-6 rounded-full bg-white border-4 border-primary z-10"></div>
                    <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-3">
                            <div>
                                <h4 class="font-bold text-on-surface">Consultation Générale</h4>
                                <p class="text-xs text-on-surface-variant font-medium">Dr. {{ $consultation->rendezVous->medecin->name ?? 'N/A' }} • {{ \Carbon\Carbon::parse($consultation->rendezVous->date_heure)->translatedFormat('d M Y') }}</p>
                            </div>
                            <span class="px-3 py-1 bg-primary-fixed text-on-primary-fixed text-[10px] font-bold rounded-full uppercase">Terminé</span>
                        </div>
                        <p class="text-sm text-on-surface-variant leading-relaxed mb-4">
                            "{!! nl2br(e($consultation->compte_rendu)) !!}"
                        </p>
                        
                        @if($consultation->ordonnance)
                            <div class="bg-surface-container-low/60 p-5 rounded-xl border border-dashed border-outline-variant/30 mt-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h5 class="font-bold text-on-surface text-sm">Ordonnance délivrée</h5>
                                        <ul class="text-xs text-on-surface-variant/80 font-medium space-y-1 list-disc ml-4 mt-2">
                                            @foreach(explode("\n", $consultation->ordonnance->contenu_medicaments) as $medicament)
                                                @if(trim($medicament))
                                                    <li>{{ $medicament }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    <span class="material-symbols-outlined text-outline-variant">prescriptions</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-on-surface-variant relative pl-10">
                    Aucune consultation passée trouvée dans votre dossier.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Documents & Ordonnances Section -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10 flex flex-col h-fit">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-tertiary-fixed flex items-center justify-center rounded-lg">
                    <span class="material-symbols-outlined text-tertiary">description</span>
                </div>
                <h3 class="font-headline text-lg font-bold">Documents & Ordonnances</h3>
            </div>
            
            <div class="space-y-3">
                @php $docCount = 0; @endphp
                @foreach($consultations as $consultation)
                    @if($consultation->ordonnance)
                        @php $docCount++; @endphp
                        <div class="group p-4 bg-surface rounded-xl hover:bg-surface-container transition-colors flex items-center justify-between border border-outline-variant/10">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center bg-red-50 rounded text-red-600">
                                    <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold truncate max-w-[140px]">Ordonnance_{{ \Carbon\Carbon::parse($consultation->rendezVous->date_heure)->format('Ymd') }}.pdf</p>
                                    <p class="text-[10px] text-on-surface-variant">Ajouté le {{ \Carbon\Carbon::parse($consultation->created_at)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('patient.ordonnance.download', $consultation->ordonnance->id) }}" target="_blank" class="p-2 rounded-lg bg-surface-container-lowest group-hover:bg-primary group-hover:text-white transition-all shadow-sm">
                                <span class="material-symbols-outlined text-lg">download</span>
                            </a>
                        </div>
                    @endif
                @endforeach
                
                @if($docCount == 0)
                    <div class="text-center py-4 text-sm text-on-surface-variant">
                        Aucun document disponible.
                    </div>
                @endif
            </div>

            <button class="mt-6 w-full py-3 bg-secondary-container/10 text-secondary font-bold text-sm rounded-xl hover:bg-secondary-container/20 transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">cloud_upload</span>
                Ajouter un document
            </button>
        </div>

        <!-- Health Tips / Bento Card -->
        <div class="relative overflow-hidden rounded-xl p-6 text-white min-h-[200px] flex flex-col justify-end">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-t from-teal-900/90 via-teal-900/40 to-transparent"></div>
                <div class="w-full h-full bg-teal-800"></div>
            </div>
            <div class="relative z-10">
                <h4 class="font-headline font-bold text-lg mb-1">Besoin d'aide ?</h4>
                <p class="text-xs text-primary-fixed mb-4">Contactez votre équipe médicale directement via la messagerie sécurisée.</p>
                <button class="bg-white text-teal-900 px-4 py-2 rounded-lg text-xs font-bold shadow-lg hover:scale-105 active:scale-95 transition-transform">
                    Envoyer un message
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

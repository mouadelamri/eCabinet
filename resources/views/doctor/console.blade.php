@extends('layouts.doctor')

@section('page-title', 'Console Clinique - ' . ($patient->name ?? 'Patient'))

@section('content')
<div class="p-8 space-y-8">
    <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row gap-8 items-start md:items-center">
        <div class="relative">
            <div class="w-32 h-32 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden ring-4 ring-primary/10 shadow-md">
                @if($patient->profile_photo_path)
                    <img src="{{ asset('storage/'.$patient->profile_photo_path) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-4xl font-black text-primary">{{ substr($patient->name, 0, 2) }}</span>
                @endif
            </div>
            @if($patient->allergies)
            <span class="absolute -bottom-2 -right-2 bg-error text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase shadow-lg shadow-error/20">Allergies</span>
            @endif
        </div>
        <div class="flex-1 space-y-4">
            <div class="flex flex-wrap items-end gap-4 text-on-surface dark:text-white">
                <h2 class="text-3xl font-headline font-extrabold">{{ $patient->name }}</h2>
                <span class="bg-secondary-fixed text-on-secondary-fixed text-xs font-bold px-3 py-1 rounded-full mb-1">ID: #PT-{{ $patient->id }}</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-on-surface dark:text-slate-200">
                <div>
                    <p class="text-label-sm text-on-surface-variant dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider">Âge / Sexe</p>
                    <p class="font-medium">
                        {{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans, 
                        {{ $patient->genre ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-label-sm text-on-surface-variant dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider">Groupe Sanguin</p>
                    <p class="text-error font-bold">{{ $patient->groupe_sanguin ?? 'Inconnu' }}</p>
                </div>
                <div>
                    <p class="text-label-sm text-on-surface-variant dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider">Contact</p>
                    <p class="font-medium">{{ $patient->telephone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-label-sm text-on-surface-variant dark:text-slate-400 text-[10px] uppercase font-semibold tracking-wider">N° Sécurité Sociale</p>
                    <p class="font-medium underline decoration-primary/30">{{ $patient->numero_securite_sociale ?? 'N/A' }}</p>
                </div>
            </div>
            @if($patient->allergies)
            <div class="bg-error-container/20 border border-error/10 p-3 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">warning</span>
                <p class="text-sm text-red-700 dark:text-red-400 font-semibold">Allergies connues : {{ $patient->allergies }}</p>
            </div>
            @endif
        </div>
        <div class="flex flex-col gap-3 w-full md:w-auto shrink-0">
           <a href="{{ route('doctor.consultation.create', $patient->id) }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-full text-sm font-bold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all">
    <span class="material-symbols-outlined text-[18px]">add_circle</span>
    Nouvelle Consultation
</a>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <section>
                <h3 class="text-xl font-headline font-bold text-on-surface dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">timeline</span>
                    Historique Médical Dynamique
                </h3>
                <div class="space-y-6 relative before:absolute before:left-[11px] before:top-2 before:bottom-0 before:w-0.5 before:bg-outline-variant/30">
                  @forelse($consultations as $consultation)
                    <div class="relative pl-10">
                        <div class="absolute left-0 top-1.5 w-6 h-6 rounded-full bg-primary-fixed dark:bg-teal-900 flex items-center justify-center z-10">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary dark:bg-teal-400"></div>
                        </div>
                        <div class="bg-surface-container-low dark:bg-slate-900 rounded-xl p-5 border border-slate-200 dark:border-slate-800 hover:border-primary/30 transition-all cursor-pointer group shadow-sm">
                            <div class="flex justify-between mb-2">
                                <span class="text-xs font-bold text-primary dark:text-teal-400">{{ $consultation->created_at->translatedFormat('d F Y') }}</span>
                                <span class="text-xs text-on-surface-variant dark:text-slate-400 italic">ID: #CS-{{ $consultation->id }}</span>
                            </div>
                            <h4 class="font-bold text-on-surface dark:text-slate-200 group-hover:text-primary transition-colors">{{ $consultation->motif ?? 'Examen général' }}</h4>
                            
                            <p class="text-sm text-on-surface-variant dark:text-slate-400 mt-2 line-clamp-3 leading-relaxed">
                                {{ $consultation->compte_rendu ?? $consultation->observations ?? 'Aucun compte rendu.' }}
                            </p>

                            @php
                                $ordonnance = \App\Models\Ordonnance::where('consultation_id', $consultation->id)->first();
                            @endphp

                            @if($ordonnance)
                            <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700">
                               <a href="{{ route('doctor.ordonnance.export', $ordonnance->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg text-xs font-bold hover:bg-red-600 transition-all shadow-sm">
    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
    Télécharger Ordonnance (PDF)
                               </a>
                            </div>
                            @endif

                            @if($consultation->diagnostic)
                            <div class="mt-4 p-3 bg-white/50 dark:bg-slate-800 rounded-lg border-l-2 border-secondary">
                                <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Diagnostic</p>
                                <p class="text-sm dark:text-slate-300 font-medium">{{ $consultation->diagnostic }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="pl-10 text-on-surface-variant italic">Aucune consultation enregistrée pour ce patient.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="space-y-8">
            <!-- Facteurs de Risque / Antécédents -->
            <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-800">
                <h3 class="text-lg font-headline font-bold text-on-surface dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-500">warning</span>
                    Alertes & Antécédents
                </h3>
                <div class="space-y-4">
                    <div class="p-4 bg-error-container/10 border-l-4 border-error rounded-r-xl">
                        <p class="text-[10px] font-bold text-error uppercase tracking-widest mb-1">Médicaux</p>
                        <p class="text-sm font-semibold dark:text-slate-300">{{ $patient->antecedents_medicaux ?? 'Aucun antécédent majeur signalé.' }}</p>
                    </div>
                </div>
            </section>

            <!-- Quick Stats -->
            <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-800">
                <h3 class="text-lg font-headline font-bold text-on-surface dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">monitoring</span>
                    Dernières Constantes
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl text-center border border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] uppercase font-bold text-on-surface-variant dark:text-slate-400 mb-1">Tension (Dernière)</p>
                        <p class="text-2xl font-black text-primary dark:text-teal-400">-- <span class="text-xs font-normal">mmHg</span></p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl text-center border border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] uppercase font-bold text-on-surface-variant dark:text-slate-400 mb-1">Poids</p>
                        <p class="text-2xl font-black text-secondary dark:text-blue-400">-- <span class="text-xs font-normal">kg</span></p>
                    </div>
                </div>
                <button class="w-full mt-4 py-2 text-[10px] font-bold text-primary dark:text-teal-400 border border-primary/20 rounded-lg hover:bg-primary/5 transition-colors">
                    VOIR GRAPHIQUES
                </button>
            </section>
        </div>
    </div>
</div>
@endsection
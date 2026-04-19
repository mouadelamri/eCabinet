@extends('layouts.doctor')

@section('title', 'Nouvelle Consultation')

@section('content')
<div class="flex-1 px-12 py-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-on-surface">Nouvelle Consultation</h2>
        <p class="text-on-surface-variant mt-1">Patient(e) : <span class="font-bold text-primary">{{ $patient->name }}</span></p>
    </div>

    <form action="{{ route('doctor.consultation.store', $patient->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <h3 class="text-lg font-bold text-on-surface mb-4">Constantes Vitales</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-1">Poids (kg)</label>
                    <input type="text" name="poids" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-1">Température (°C)</label>
                    <input type="text" name="temperature" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-1">Tension (mmHg)</label>
                    <input type="text" name="tension" placeholder="ex: 120/80" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-1">Rythme Cardiaque</label>
                    <input type="text" name="rythme_cardiaque" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
            <h3 class="text-lg font-bold text-on-surface mb-4">Détails de la Consultation</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Compte Rendu (Diagnostic)</label>
                    <textarea name="compte_rendu" rows="4" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Notes Privées (Invisibles pour le patient)</label>
                    <textarea name="notes_privees" rows="2" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10 border-l-4 border-l-primary">
            <h3 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">prescriptions</span>
                Prescription (Ordonnance)
            </h3>
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-2">Liste des médicaments et posologie (Laissez vide si aucune ordonnance)</label>
                <textarea name="medicaments" rows="5" placeholder="1. Paracétamol 1g - 1 comp 3x/jour&#10;2. Amoxicilline 500mg - 1 gélule matin et soir" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary"></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('doctor.patients.show', $patient->id) }}" class="px-6 py-2 rounded-full font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Annuler</a>
            <button type="submit" class="px-6 py-2 rounded-full font-bold bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-md">
                Enregistrer & Terminer
            </button>
        </div>
    </form>
</div>
@endsection
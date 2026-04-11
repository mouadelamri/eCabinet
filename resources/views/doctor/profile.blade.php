@extends('layouts.doctor')

@section('page-title', 'Mon Profil Professionnel')

@section('content')
<div class="p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-on-surface dark:text-white">Paramètres du profil</h2>
            <p class="text-sm text-on-surface-variant dark:text-slate-400">Gérez vos informations professionnelles et votre identité publique.</p>
        </div>
        @if(session('success'))
        <div class="bg-teal-100 border border-teal-200 text-teal-700 px-4 py-2 rounded-lg text-sm animate-pulse">
            {{ session('success') }}
        </div>
        @endif
    </div>

    <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Profile Header Card -->
        <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="relative group">
                    <img id="preview-image" src="{{ $user->profile_photo_url }}" class="w-32 h-32 rounded-3xl object-cover border-4 border-white dark:border-slate-800 shadow-xl group-hover:opacity-75 transition-all">
                    <label for="photo-upload" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-all">
                        <span class="bg-black/50 text-white rounded-full p-2">
                            <span class="material-symbols-outlined">photo_camera</span>
                        </span>
                    </label>
                    <input id="photo-upload" name="photo" type="file" class="hidden" onchange="previewFile()">
                </div>
                <div class="flex-1 text-center md:text-left space-y-2">
                    <h3 class="text-xl font-bold text-on-surface dark:text-white">{{ $user->name }}</h3>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <span class="px-3 py-1 bg-primary/10 text-primary dark:text-teal-400 text-[10px] font-bold rounded-full uppercase">{{ $user->specialiste ?? 'Spécialité non définie' }}</span>
                        <span class="px-3 py-1 bg-secondary/10 text-secondary dark:text-blue-400 text-[10px] font-bold rounded-full uppercase">Membre depuis {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-widest text-on-surface-variant dark:text-slate-500 mb-4">Informations de base</h4>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant dark:text-slate-400">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-surface-container-low dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary dark:text-white" required>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant dark:text-slate-400">Email professionnel</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-surface-container-low dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary dark:text-white" required>
                </div>
            </div>

            <!-- Professional Info -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-widest text-on-surface-variant dark:text-slate-500 mb-4">Expertise & Contact</h4>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant dark:text-slate-400">Spécialité</label>
                    <input type="text" name="specialiste" value="{{ old('specialiste', $user->specialiste) }}" placeholder="Ex: Cardiologie, Pédiatrie..." class="w-full bg-surface-container-low dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary dark:text-white">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant dark:text-slate-400">Diplômes / Titres</label>
                    <input type="text" name="diplome" value="{{ old('diplome', $user->diplome) }}" placeholder="Ex: Docteur en Médecine, PhD..." class="w-full bg-surface-container-low dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary dark:text-white">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant dark:text-slate-400">Téléphone professionnel</label>
                    <input type="text" name="telephone_pro" value="{{ old('telephone_pro', $user->telephone_pro) }}" placeholder="+33 6..." class="w-full bg-surface-container-low dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary dark:text-white">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 hover:bg-primary-container transition-all active:scale-95">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
function previewFile() {
    const preview = document.getElementById('preview-image');
    const file = document.getElementById('photo-upload').files[0];
    const reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}
</script>
@endsection

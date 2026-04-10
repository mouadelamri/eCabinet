@extends('patient.layout')

@section('title', 'Paramètres — eCabinet')

@section('content')
<!-- Hero Header -->
<div class="mb-12">
    <h2 class="text-4xl font-extrabold tracking-tight text-on-surface mb-2">Gestion du Profil</h2>
    <p class="text-on-surface-variant max-w-2xl leading-relaxed">Mettez à jour vos informations personnelles et médicales pour assurer un suivi précis par vos professionnels de santé.</p>
</div>

<form method="POST" action="{{ route('patient.settings.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Personal Info -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Profile Card -->
            <div class="bg-surface-container-low rounded-xl p-8">
                <div class="flex items-center gap-6 mb-8">
                    <div class="relative group">
                        <label for="profile_photo" class="cursor-pointer block relative">
                            <div class="w-32 h-32 rounded-2xl shadow-lg bg-primary-fixed flex items-center justify-center text-primary text-5xl font-bold overflow-hidden border-4 border-surface-container-low transition-all group-hover:border-primary/30">
                                <img id="avatar_preview" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-3xl">photo_camera</span>
                                </div>
                            </div>
                            <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </label>
                        @error('profile_photo') <span class="text-error text-xs absolute -bottom-6 left-0 w-max">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-on-surface">{{ auth()->user()->name }}</h3>
                        <p class="text-on-surface-variant font-medium">Inscrit depuis le {{ optional(auth()->user()->created_at)->translatedFormat('d F Y') ?? 'N/A' }}</p>
                        <p class="text-xs text-primary font-bold mt-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">info</span>
                            Cliquez sur l'image pour la modifier
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider ml-1">Nom Complet</label>
                        <input name="name" class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none text-on-surface font-medium" type="text" value="{{ old('name', auth()->user()->name) }}"/>
                        @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider ml-1">Email</label>
                        <input name="email" class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none text-on-surface font-medium" type="email" value="{{ old('email', auth()->user()->email) }}"/>
                        @error('email') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider ml-1">Téléphone</label>
                        <input name="telephone" class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none text-on-surface font-medium" type="tel" value="{{ old('telephone', auth()->user()->telephone) }}"/>
                        @error('telephone') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider ml-1">Date de Naissance (Optionnel)</label>
                        <input class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none text-on-surface font-medium" type="date"/>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider ml-1">Nouveau mot de passe (Optionnel)</label>
                        <input name="password" class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none text-on-surface font-medium" type="password" placeholder="Laisser vide pour ne pas modifier"/>
                        @error('password') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider ml-1">Confirmer nouveau mot de passe</label>
                        <input name="password_confirmation" class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none text-on-surface font-medium" type="password" placeholder="Répéter le mot de passe"/>
                    </div>
                </div>
            </div>

            <!-- Medical Details Asymmetric Grid -->
            <div class="bg-surface-container-lowest rounded-xl p-8 border border-outline-variant/10">
                <div class="flex items-center gap-3 mb-8">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">medical_information</span>
                    <h3 class="text-xl font-bold text-on-surface">Informations Médicales (Non Modifiable)</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <div class="md:col-span-4 space-y-4">
                        <div class="p-6 rounded-2xl bg-secondary-fixed/30 border border-secondary-fixed/50">
                            <label class="text-xs font-bold text-on-secondary-fixed-variant uppercase mb-2 block">Groupe Sanguin</label>
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-black text-on-secondary-fixed">N/A</span>
                                <span class="material-symbols-outlined text-secondary text-4xl">bloodtype</span>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-8 space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider ml-1">Antécédents Médicaux</label>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3 p-4 bg-surface-container rounded-lg group hover:bg-surface-container-high transition-colors">
                                    <div class="mt-1 w-2 h-2 rounded-full bg-primary shrink-0"></div>
                                    <p class="text-sm text-on-surface font-medium leading-tight">Géré par votre médecin.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Right Column: Quick Actions & Privacy -->
        <div class="space-y-8">
            
            <!-- Security Card -->
            <div class="bg-primary-container text-on-primary-container rounded-xl p-8 relative overflow-hidden">
                <div class="relative z-10">
                    <span class="material-symbols-outlined text-3xl mb-4">shield_with_heart</span>
                    <h3 class="text-xl font-bold mb-2">Sécurité & Confidentialité</h3>
                    <p class="text-sm text-on-primary-container/80 mb-6 leading-relaxed">Vos données sont cryptées et protégées conformément aux normes de santé en vigueur.</p>
                </div>
                <!-- Background Decoration -->
                <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
            </div>

            <!-- Sticky Action Box -->
            <div class="sticky top-24 space-y-4">
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">save</span>
                    Enregistrer les modifications
                </button>
            </div>
            
        </div>
    </div>
</form>
@section('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar_preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
@endsection

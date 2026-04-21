@extends('admin.layout')

@section('title', 'Admin - Médecins')

@section('content')
<div class="max-w-7xl mx-auto space-y-12">
    <!-- Page Header Section -->
    <div class="flex items-end justify-between">
        <div>
            <h2 class="text-4xl font-extrabold font-headline tracking-tight text-on-surface mb-2">Gestion des Médecins</h2>
            <p class="text-on-surface-variant font-body">Gérez l'annuaire de votre équipe clinique et ajoutez de nouveaux confrères.</p>
        </div>
        <button onclick="document.getElementById('addDoctorForm').classList.toggle('hidden')" class="flex items-center gap-2 bg-gradient-to-r from-primary to-primary-container text-on-primary px-6 py-3 rounded-xl font-semibold shadow-[0_10px_30px_-5px_rgba(0,106,97,0.2)] hover:scale-[1.02] active:scale-[0.98] transition-all">
            <span class="material-symbols-outlined" data-icon="add">add</span>
            <span>Ajouter un Médecin</span>
        </button>
    </div>

    @if(session('success'))
    <div class="px-6 py-4 bg-green-100 text-green-800 rounded-xl font-medium">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="px-6 py-4 bg-red-100 text-red-800 rounded-xl font-medium">
        <ul>
            @foreach($errors->all() as $err)
            <li>• {{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Add Form -->
    <div id="addDoctorForm" class="hidden bg-surface-container-lowest p-8 rounded-2xl shadow-sm border border-outline-variant/10">
        <div class="mb-6">
            <h3 class="text-xl font-bold font-headline mb-1">Nouveau Praticien</h3>
            <p class="text-xs text-on-surface-variant">Veuillez renseigner les coordonnées du médecin.</p>
        </div>
        <form action="{{ route('admin.doctors.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Nom Complet</label>
                    <input name="name" type="text" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="ex: Dr. Julien Morel" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Email</label>
                    <input name="email" type="email" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="doctor@ecabinet.com" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Spécialité</label>
                    <input name="specialiste" type="text" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="Généraliste, Cardiologue..." />
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Téléphone</label>
                    <input name="telephone_pro" type="text" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="+33 6..." />
                </div>
                <div class="space-y-1.5 col-span-2">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Mot de passe</label>
                    <input name="password" type="password" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="••••••••" />
                </div>
            </div>
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-lg hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-md">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <!-- Dashboard Stats Bento -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-surface-container-lowest p-8 rounded-[2rem] shadow-[0_10px_30px_-5px_rgba(0,106,97,0.08)] relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-primary mb-4 uppercase tracking-widest">Aperçu de l'équipe</p>
                <h3 class="text-3xl font-bold font-headline mb-6">{{ $doctors->total() }} Praticiens Actifs</h3>
                <div class="flex -space-x-3 mb-8">
                    @foreach($doctors->take(4) as $doc)
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-primary text-on-primary flex flex-col items-center justify-center font-bold text-sm shadow-sm">{{ substr($doc->name, 0, 2) }}</div>
                    @endforeach
                    @if($doctors->count() > 4)
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed font-bold text-sm shadow-sm">+{{ $doctors->count() - 4 }}</div>
                    @endif
                </div>
            </div>
            <div class="absolute right-0 bottom-0 p-8">
                <span class="material-symbols-outlined text-[8rem] opacity-[0.03] text-primary" data-icon="medical_information">medical_information</span>
            </div>
        </div>
        <div class="bg-primary text-on-primary p-8 rounded-[2rem] shadow-[0_10px_30px_-5px_rgba(0,106,97,0.3)] flex flex-col justify-between">
            <span class="material-symbols-outlined text-4xl" data-icon="query_stats">query_stats</span>
            <div>
                <p class="text-4xl font-extrabold font-headline mb-1">100%</p>
                <p class="text-sm opacity-80 font-medium">Equipe médicale active sur le réseau</p>
            </div>
        </div>
    </div>

    <!-- Table View -->
    <div class="bg-surface-container-low rounded-[2rem] overflow-hidden shadow-[0_10px_30px_-5px_rgba(0,106,97,0.08)]">
        @if($doctors->count() === 0)
            <div class="p-10 text-center text-on-surface-variant font-medium">Aucun médecin trouvé. Veuillez en ajouter un.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-high/50">
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Médecin</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Spécialité</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Contact</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($doctors as $doctor)
                    <tr class="hover:bg-surface-container-lowest/80 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-lg bg-teal-100 text-teal-800 border-[3px] border-white shadow-sm">
                                    {{ substr($doctor->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-on-surface font-headline">{{ $doctor->name }}</p>
                                    <p class="text-xs text-on-surface-variant font-medium">{{ $doctor->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-secondary-fixed text-on-secondary-fixed text-[10px] font-extrabold rounded-full uppercase tracking-widest">{{ $doctor->specialiste ?? 'Généraliste' }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-on-surface">{{ $doctor->telephone_pro ?? '-' }}</p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <form action="{{ route('admin.users.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Supprimer ce médecin ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-surface-container-highest hover:bg-red-100 hover:text-red-700 rounded-xl transition-colors text-outline">
                                    <span class="material-symbols-outlined text-[18px]" data-icon="delete">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
                <!-- Pagination -->
                <div class="mt-4 flex justify-center">
                    {{ $doctors->links() }}
                </div>
        @endif
    </div>
</div>
@endsection

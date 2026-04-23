@extends('admin.layout')

@section('title', 'Admin - Patients')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Page Header -->
    <div class="flex items-end justify-between gap-4 mb-4">
        <div>
            <h2 class="text-3xl font-extrabold font-headline tracking-tight text-on-surface mb-1">Gestion des Patients</h2>
            <p class="text-on-surface-variant font-body">Base de données des patients enregistrés au cabinet.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-semibold text-primary">{{ $patients->total() }} Patients actifs</span>
        </div>
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

    <!-- Dashboard Layout (Bento Grid Style) -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Registration Form Section (Manual Entry) -->
        <section class="col-span-12 xl:col-span-4 space-y-6">
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_10px_30px_-5px_rgba(0,106,97,0.05)] border border-outline-variant/10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-secondary-fixed/30 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined" data-icon="person_add">person_add</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline">Inscrire un Patient</h3>
                </div>
                <form action="{{ route('admin.patients.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Nom Complet</label>
                        <input name="name" required class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm" placeholder="ex: Jean Dupont" type="text"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Email</label>
                        <input name="email" required type="email" class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm" placeholder="jean.dupont@email.com"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Adresse</label>
                        <input name="adresse" required type="text" class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm" placeholder="123 rue de la Paix"/>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Groupe Sanguin</label>
                            <select name="groupe_sanguin" required class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Date Naissance</label>
                            <input name="date_naissance" required class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm" type="date"/>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Téléphone</label>
                        <input name="telephone" required class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm" placeholder="06 00 00 00 00" type="tel"/>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Mot De Passe</label>
                            <input name="password" required type="password" class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm" placeholder="••••••••"/>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Confirmation</label>
                            <input name="password_confirmation" required type="password" class="w-full px-4 py-3 bg-surface-container-high rounded-lg border-none focus:ring-2 focus:ring-primary/20 transition-all font-body text-sm" placeholder="••••••••"/>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-br from-primary to-primary-container text-white font-bold rounded-lg shadow-lg hover:bg-black/10 hover:shadow-primary/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm" data-icon="save">save</span>
                            Enregistrer le Dossier
                        </button>
                    </div>
                </form>
            </div>

            
        </section>

        <!-- Patient List Section -->
        <section class="col-span-12 xl:col-span-8 space-y-6">
            <div class="bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/5">
                @if($patients->count() == 0 )
                    <div class="p-10 text-center text-on-surface-variant font-medium">Aucun patient trouvé.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant bg-surface-container-low">
                                <th class="px-6 py-4">Patient</th>
                                <th class="px-6 py-4 text-center">Gr. Sanguin</th>
                                <th class="px-6 py-4">Contact</th>
                                <th class="px-6 py-4">Inscription</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-surface-container">
                            @foreach($patients as $patient)
                            <tr class="hover:bg-surface-bright transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant font-bold text-xs">{{ substr($patient->name, 0, 2) }}</div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ $patient->name }}</p>
                                            <p class="text-[10px] text-on-surface-variant">ID: #{{ $patient->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed rounded-full text-[10px] font-extrabold tracking-tighter">{{ $patient->groupe_sanguin ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-on-surface">{{ $patient->telephone }}</p>
                                    <p class="text-[10px] text-on-surface-variant">{{ $patient->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        <p class="text-xs font-semibold text-on-surface">{{ $patient->created_at->format('d M Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end">
                                    <form action="{{ route('admin.users.destroy', $patient) }}" method="POST" onsubmit="return confirm('Supprimer ce patient ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-outline-variant hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-sm" data-icon="delete">delete</span>
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
                    {{ $patients->links() }}
                </div>
                @endif
            </div>


        </section>
    </div>
</div>
@endsection

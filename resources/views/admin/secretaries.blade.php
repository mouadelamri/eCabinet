@extends('admin.layout')

@section('title', 'Admin - Secretaries')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-4xl font-extrabold font-headline tracking-tight text-on-surface mb-2">Gestion du Secrétariat</h2>
            <p class="text-on-surface-variant max-w-lg font-body">Supervisez l'équipe administrative, gérez les accès et assurez la fluidité opérationnelle de votre cabinet médical.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="px-6 py-4 mb-8 bg-green-100 text-green-800 rounded-xl font-medium">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="px-6 py-4 mb-8 bg-red-100 text-red-800 rounded-xl font-medium">
        <ul>
            @foreach($errors->all() as $err)
            <li>• {{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Dashboard Style Content -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Registration Form (The "Prescription Card") -->
        <section class="col-span-12 lg:col-span-4 space-y-6">
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] border border-outline-variant/10">
                <div class="mb-8">
                    <h3 class="text-xl font-bold font-headline mb-1">Nouveau Profil</h3>
                    <p class="text-xs text-on-surface-variant">Enregistrez un nouveau collaborateur administratif.</p>
                </div>
                <form action="{{ route('admin.secretaries.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Nom Complet</label>
                        <input name="name" type="text" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="ex: Sophie Martin" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Email Professionnel</label>
                        <input name="email" type="email" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="s.martin@ecabinet.com" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest pl-1">Mot De Passe</label>
                        <input name="password" type="password" required class="w-full px-4 py-3 bg-surface-container-high border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" placeholder="••••••••" />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-secondary-container/10 text-secondary font-bold rounded-lg hover:bg-secondary-container/20 transition-colors">
                            Valider l'inscription
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistics / Inventory -->
            <div class="bg-surface-container-low p-6 rounded-xl border border-outline-variant/10">
                <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-4">Charge de travail actuelle</h4>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs mb-1.5">
                            <span class="font-medium">Rendez-vous planifiés</span>
                            <span class="text-primary font-bold">88%</span>
                        </div>
                        <div class="h-1.5 w-full bg-primary-fixed rounded-full overflow-hidden">
                            <div class="h-full bg-primary" style="width: 88%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1.5">
                            <span class="font-medium">Dossiers à traiter</span>
                            <span class="text-tertiary font-bold">42%</span>
                        </div>
                        <div class="h-1.5 w-full bg-tertiary-fixed rounded-full overflow-hidden">
                            <div class="h-full bg-tertiary" style="width: 42%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- List View -->
        <section class="col-span-12 lg:col-span-8">
            <div class="bg-surface-container-low rounded-xl overflow-hidden shadow-sm">
                @if($secretaries->count() == 0)
                    <div class="p-10 text-center text-on-surface-variant font-medium">Aucun secrétaire trouvé.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-high/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Membre</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Contact</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($secretaries as $secretary)
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-primary-fixed-dim flex items-center justify-center font-bold text-on-primary-fixed">
                                            {{ substr($secretary->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ $secretary->name }}</p>
                                            <p class="text-[10px] text-on-surface-variant">Secrétaire</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm">
                                        <p class="font-medium">{{ $secretary->email }}</p>
                                        <p class="text-[10px] text-on-surface-variant">Membre depuis {{ $secretary->created_at->format('M Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest rounded-full">Actif</span>
                                </td>
                                <td class="px-6 py-5 text-right flex justify-end">
                                    <form action="{{ route('admin.users.destroy', $secretary) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression de ce secrétaire ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-outline-variant hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-xl">delete</span>
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
                    {{ $secretaries->links() }}
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

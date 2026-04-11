@extends('layouts.doctor')

@section('page-title', 'Répertoire des Patients')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-4">
        <div>
            <h2 class="text-3xl font-extrabold text-on-surface dark:text-white tracking-tight mb-2">Répertoire des Patients</h2>
            <p class="text-on-surface-variant dark:text-slate-400 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary dark:text-teal-400">analytics</span>
                {{ $patients->total() }} patients actifs sous votre supervision
            </p>
        </div>
        <div class="flex gap-3">
            <button class="px-5 py-2.5 rounded-xl bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-slate-300 font-bold text-sm hover:bg-surface-container transition-all border border-slate-200 dark:border-slate-700">
                Exporter Global (.CSV)
            </button>
            <button class="px-5 py-2.5 rounded-xl bg-primary text-white font-bold text-sm flex items-center gap-2 shadow-lg shadow-primary/20 hover:bg-primary-container transition-all">
                <span class="material-symbols-outlined text-sm">person_add</span>
                Nouvelle Fiche
            </button>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-1 bg-surface-container-lowest dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-xs font-bold text-on-surface-variant dark:text-slate-500 uppercase tracking-widest mb-4">Nouveaux (Mois)</p>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary dark:text-teal-400">{{ $patients->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary dark:text-teal-400">
                    <span class="material-symbols-outlined">person_add</span>
                </div>
            </div>
        </div>
        <div class="md:col-span-3 bg-gradient-to-br from-primary/5 to-secondary/5 dark:from-slate-800 dark:to-slate-900 p-6 rounded-2xl border border-primary/10 dark:border-slate-800 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-secondary dark:text-blue-400 uppercase tracking-widest mb-2">Progression de l'activité</p>
                <p class="text-sm text-on-surface-variant dark:text-slate-400">Vous avez pris en charge {{ $patients->count() }} patients aujourd'hui.</p>
            </div>
            <div class="hidden md:block">
                <div class="flex -space-x-3">
                    @foreach($patients->take(5) as $p)
                        <div class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-900 bg-slate-200 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                            @if($p->profile_photo_path)
                                <img src="{{ asset('storage/'.$p->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[10px] font-bold text-primary">{{ substr($p->name, 0, 2) }}</span>
                            @endif
                        </div>
                    @endforeach
                    <div class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-900 bg-primary-fixed dark:bg-teal-900 flex items-center justify-center text-[10px] font-bold text-primary dark:text-teal-400">
                        +{{ max(0, $patients->total() - 5) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Table Section -->
    <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50 dark:bg-slate-800/50">
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-slate-500 uppercase tracking-widest">Identité du Patient</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-slate-500 uppercase tracking-widest">Naissance</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-slate-500 uppercase tracking-widest text-center">Gr. Sanguin</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-slate-500 uppercase tracking-widest">Genre</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-slate-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-slate-700">
                                    @if($patient->profile_photo_path)
                                        <img src="{{ asset('storage/'.$patient->profile_photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="font-black text-primary dark:text-teal-400">{{ substr($patient->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface dark:text-slate-200 group-hover:text-primary transition-colors">{{ $patient->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">#PT-{{ $patient->id }} • {{ $patient->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm text-on-surface-variant dark:text-slate-400 font-medium">
                            {{ \Carbon\Carbon::parse($patient->date_naissance)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black {{ str_contains($patient->groupe_sanguin, '-') ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $patient->groupe_sanguin ?? '??' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                {{ $patient->genre ?? 'Non spécifié' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('doctor.patients.show', $patient->id) }}" class="inline-block bg-primary text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-md shadow-primary/10 hover:shadow-lg transition-all active:scale-95">
                                Voir Dossier
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-on-surface-variant dark:text-slate-500 italic">
                            Aucun patient trouvé dans votre répertoire.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection

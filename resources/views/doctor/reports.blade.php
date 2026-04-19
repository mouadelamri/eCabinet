@extends('layouts.doctor')

@section('content')
<div class="flex-1 px-12 py-8 max-w-5xl">
<!-- TopAppBar Internal -->
<header class="flex justify-between items-center mb-10">
<div class="flex items-center gap-4">
<div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-md">
<img alt="Mme. Claire Marchand" class="w-full h-full object-cover" data-alt="Portrait of a middle-aged woman with a gentle smile and grey hair, professional medical profile lighting, soft background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTOisMuA_uM7hui8fpBX7ddb0srp5Q-oadiRDn1VPYou3zyEUFwO0YtK5KzSxwla2F630MQBh8CEMj30digNSOA67GQoQkR1Gqicbp9Hbax2_A43AMBkSA7945rIh-kku3XGyKU_jcUQH4YFjYDDAeyVH3hMXnKLJE-u8yYDlPjr5w9rczj9mPoDVuKbVigYgltMwcOqHsaih4oTEjxQ1XPZLQWunWHcs5sgXM8NVdkJxVlqRJsptWWcXItXGNLEJa5Pb49OqfiGj0">
</div>
<div>
<h2 class="text-3xl font-extrabold tracking-tight text-on-surface">Mme. Claire Marchand</h2>
<div class="flex items-center gap-2 text-on-surface-variant font-medium text-sm">
<span>ID: #994821</span>
<span class="w-1 h-1 rounded-full bg-outline"></span>
<span>54 ans</span>
</div>
</div>
</div>
<div class="flex items-center gap-3">
<button class="p-3 rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
</button>
<button class="p-3 rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined" data-icon="share">share</span>
</button>
</div>
</header>
<!-- Tabs Selection -->
<div class="flex gap-8 mb-8 border-b-0">
<button class="pb-4 text-on-surface-variant font-medium hover:text-primary transition-colors">Chronologie</button>
<button class="pb-4 text-on-surface-variant font-medium hover:text-primary transition-colors">Analyses</button>
<button class="pb-4 text-primary font-bold border-b-2 border-primary">Rapports</button>
</div>
<!-- Reports Content: Bento-ish Grid -->
<div class="grid grid-cols-1 gap-4">
<div class="flex items-center justify-between mb-4">
<h3 class="text-xl font-bold text-on-surface">Documents d'Imagerie &amp; Rapports</h3>
<div class="flex gap-2">
<button class="flex items-center gap-2 px-4 py-2 bg-surface-container-high rounded-full text-sm font-medium text-on-surface-variant hover:bg-surface-variant transition-all">
<span class="material-symbols-outlined text-[18px]" data-icon="filter_list">filter_list</span>
                            Filtrer
                        </button>
</div>
</div>
<!-- Report Card 1 -->
<div class="group bg-surface-container-lowest rounded-xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition-all border border-transparent hover:border-primary/10">
<div class="w-14 h-14 bg-secondary-fixed/50 text-on-secondary-fixed-variant rounded-xl flex items-center justify-center">
<span class="material-symbols-outlined text-3xl" data-icon="mri" style="font-variation-settings: 'FILL' 1;">radiology</span>
</div>
<div class="flex-1">
<h4 class="font-bold text-on-surface text-lg">IRM Cérébrale (T1/T2/FLAIR)</h4>
<div class="flex gap-4 text-sm text-on-surface-variant mt-1">
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm" data-icon="calendar_month">calendar_month</span>
                                12 Octobre 2023
                            </span>
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm" data-icon="description">description</span>
                                4.2 MB
                            </span>
</div>
</div>
<div class="flex items-center gap-3">
<button class="px-4 py-2 bg-surface-container text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-high transition-colors">Aperçu</button>
<button class="w-10 h-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all">
<span class="material-symbols-outlined" data-icon="download">download</span>
</button>
</div>
</div>
<!-- Report Card 2 -->
<div class="group bg-surface-container-lowest rounded-xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition-all border border-transparent hover:border-primary/10">
<div class="w-14 h-14 bg-tertiary-fixed/50 text-on-tertiary-fixed-variant rounded-xl flex items-center justify-center">
<span class="material-symbols-outlined text-3xl" data-icon="surgical_power_tools" style="font-variation-settings: 'FILL' 1;">tools_power_drill</span>
</div>
<div class="flex-1">
<h4 class="font-bold text-on-surface text-lg">Compte-rendu Opératoire (Chirurgie Ambulatoire)</h4>
<div class="flex gap-4 text-sm text-on-surface-variant mt-1">
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm" data-icon="calendar_month">calendar_month</span>
                                05 Septembre 2023
                            </span>
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm" data-icon="person">person</span>
                                Dr. Lehalleur
                            </span>
</div>
</div>
<div class="flex items-center gap-3">
<button class="px-4 py-2 bg-surface-container text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-high transition-colors">Aperçu</button>
<button class="w-10 h-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all">
<span class="material-symbols-outlined" data-icon="download">download</span>
</button>
</div>
</div>
<!-- Report Card 3 -->
<div class="group bg-surface-container-lowest rounded-xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition-all border border-transparent hover:border-primary/10">
<div class="w-14 h-14 bg-secondary-fixed/50 text-on-secondary-fixed-variant rounded-xl flex items-center justify-center">
<span class="material-symbols-outlined text-3xl" data-icon="radiology" style="font-variation-settings: 'FILL' 1;">radiology</span>
</div>
<div class="flex-1">
<h4 class="font-bold text-on-surface text-lg">Scanner Abdominal à Contraste</h4>
<div class="flex gap-4 text-sm text-on-surface-variant mt-1">
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm" data-icon="calendar_month">calendar_month</span>
                                20 Août 2023
                            </span>
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-sm" data-icon="description">description</span>
                                8.1 MB
                            </span>
</div>
</div>
<div class="flex items-center gap-3">
<button class="px-4 py-2 bg-surface-container text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-high transition-colors">Aperçu</button>
<button class="w-10 h-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all">
<span class="material-symbols-outlined" data-icon="download">download</span>
</button>
</div>
</div>
<!-- Graphic Element: Analysis Trend Preview -->
<div class="mt-8 bg-surface-container-low rounded-2xl p-8 border border-outline-variant/10">
<div class="flex justify-between items-start mb-6">
<div>
<h4 class="text-lg font-bold text-on-surface">Évolution des constantes (6 derniers mois)</h4>
<p class="text-sm text-on-surface-variant">Données consolidées des rapports d'analyses</p>
</div>
<span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full uppercase tracking-wider">Synchronisé</span>
</div>
<div class="h-32 w-full flex items-end gap-1">
<div class="flex-1 bg-primary/10 hover:bg-primary/20 rounded-t-lg transition-all h-[40%]"></div>
<div class="flex-1 bg-primary/10 hover:bg-primary/20 rounded-t-lg transition-all h-[55%]"></div>
<div class="flex-1 bg-primary/20 hover:bg-primary/30 rounded-t-lg transition-all h-[75%]"></div>
<div class="flex-1 bg-primary/30 hover:bg-primary/40 rounded-t-lg transition-all h-[60%]"></div>
<div class="flex-1 bg-primary/40 hover:bg-primary/50 rounded-t-lg transition-all h-[85%]"></div>
<div class="flex-1 bg-primary rounded-t-lg transition-all h-[70%]"></div>
</div>
</div>
</div>
</div>
<aside class="w-80 bg-surface-container-low border-l-0 p-8 flex flex-col gap-8">
<!-- Vitals Section -->
<section>
<h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-widest mb-6">Constantes Vitales</h3>
<div class="space-y-4">
<div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm">
<div class="flex justify-between items-center mb-1">
<span class="text-xs font-medium text-on-surface-variant">Tension Artérielle</span>
<span class="material-symbols-outlined text-primary text-lg" data-icon="monitor_heart">monitor_heart</span>
</div>
<div class="flex items-baseline gap-1">
<span class="text-2xl font-black text-on-surface">120/80</span>
<span class="text-xs text-on-surface-variant">mmHg</span>
</div>
</div>
<div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm">
<div class="flex justify-between items-center mb-1">
<span class="text-xs font-medium text-on-surface-variant">Rythme Cardiaque</span>
<span class="material-symbols-outlined text-tertiary text-lg" data-icon="favorite">favorite</span>
</div>
<div class="flex items-baseline gap-1">
<span class="text-2xl font-black text-on-surface">72</span>
<span class="text-xs text-on-surface-variant">bpm</span>
</div>
</div>
<div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm">
<div class="flex justify-between items-center mb-1">
<span class="text-xs font-medium text-on-surface-variant">Saturation O₂</span>
<span class="material-symbols-outlined text-secondary text-lg" data-icon="air">air</span>
</div>
<div class="flex items-baseline gap-1">
<span class="text-2xl font-black text-on-surface">98</span>
<span class="text-xs text-on-surface-variant">%</span>
</div>
</div>
</div>
</section>
<!-- Prescriptions Section -->
<section class="flex-1">
<div class="flex justify-between items-center mb-6">
<h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Prescriptions Actives</h3>
<span class="text-[10px] bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full font-bold">3 EN COURS</span>
</div>
<a href="{{ route('doctor.ordonnance.export', 1) }}" class="text-primary hover:underline text-sm font-bold flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">download</span> PDF
</a>
<div class="space-y-3">
<div class="p-4 rounded-xl bg-white border border-outline-variant/10">
<h5 class="font-bold text-on-surface text-sm">Amoxicilline 500mg</h5>
<p class="text-xs text-on-surface-variant mt-1">1 gélule • 3x par jour</p>
<div class="mt-3 h-1.5 w-full bg-primary-fixed rounded-full overflow-hidden">
<div class="h-full bg-primary" style="width: 65%;"></div>
</div>
<p class="text-[10px] text-right mt-1 text-on-surface-variant font-medium">J-4 / 10</p>
</div>
<div class="p-4 rounded-xl bg-white border border-outline-variant/10">
<h5 class="font-bold text-on-surface text-sm">Paracétamol 1g</h5>
<p class="text-xs text-on-surface-variant mt-1">Si douleur • Max 4g/j</p>
</div>
<div class="p-4 rounded-xl bg-white border border-outline-variant/10">
<h5 class="font-bold text-on-surface text-sm">Lisinopril 10mg</h5>
<p class="text-xs text-on-surface-variant mt-1">1 comprimé • Matin</p>
</div>
</div>
</section>
<!-- CTA Footer Sidebar -->
<button class="w-full py-4 border-2 border-dashed border-outline-variant rounded-xl text-on-surface-variant font-semibold text-sm hover:border-primary hover:text-primary transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined" data-icon="add_notes">add_notes</span>
                Nouvelle Ordonnance
            </button>
</aside>

@endsection

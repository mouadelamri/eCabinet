@extends('layouts.doctor')

@section('page-title', 'Planning & Agenda')

@section('content')
<div class="p-8 space-y-6">

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 flex items-center gap-2 border border-green-200">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-headline font-extrabold text-on-surface">Planning Hebdomadaire</h2>
            <p class="text-sm text-on-surface-variant mt-1">Semaine du {{ $weekStart->translatedFormat('d M') }} au {{ $weekEnd->translatedFormat('d M Y') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('doctor.schedule', ['week' => $weekOffset - 1]) }}" class="flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface-variant text-sm rounded-lg hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-sm">chevron_left</span>
                Semaine précédente
            </a>
            <a href="{{ route('doctor.schedule') }}" class="flex items-center gap-2 px-4 py-2 {{ $weekOffset === 0 ? 'bg-primary text-on-primary' : 'border border-outline-variant text-on-surface-variant hover:bg-surface-container' }} text-sm font-semibold rounded-lg transition-colors">
                Aujourd'hui
            </a>
            <a href="{{ route('doctor.schedule', ['week' => $weekOffset + 1]) }}" class="flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface-variant text-sm rounded-lg hover:bg-surface-container transition-colors">
                Semaine suivante
                <span class="material-symbols-outlined text-sm">chevron_right</span>
            </a>
        </div>
    </div>

    {{-- Week Stats Strip --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/10">
            <p class="text-[10px] uppercase font-bold text-on-surface-variant tracking-wider">RDV cette semaine</p>
            <p class="text-3xl font-black text-primary mt-1">{{ \App\Models\RendezVous::where('medecin_id', auth()->id())->whereBetween('date_heure', [$weekStart, $weekEnd])->count() }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/10">
            <p class="text-[10px] uppercase font-bold text-on-surface-variant tracking-wider">Confirmés</p>
            <p class="text-3xl font-black text-green-600 mt-1">{{ \App\Models\RendezVous::where('medecin_id', auth()->id())->where('statut', 'CONFIRMED')->whereBetween('date_heure', [$weekStart, $weekEnd])->count() }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/10">
            <p class="text-[10px] uppercase font-bold text-on-surface-variant tracking-wider">En attente</p>
            <p class="text-3xl font-black text-amber-500 mt-1">{{ \App\Models\RendezVous::where('medecin_id', auth()->id())->where('statut', 'PENDING')->whereBetween('date_heure', [$weekStart, $weekEnd])->count() }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/10">
            <p class="text-[10px] uppercase font-bold text-on-surface-variant tracking-wider">Aujourd'hui</p>
            <p class="text-3xl font-black text-secondary mt-1">{{ \App\Models\RendezVous::where('medecin_id', auth()->id())->whereDate('date_heure', today())->count() }}</p>
        </div>
    </div>

    {{-- Configurer Disponibilités --}}
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
        <h3 class="text-lg font-headline font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">schedule</span>
            Configurer mes disponibilités
        </h3>
        
        <form action="{{ route('doctor.availability.save') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @php
                    $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                @endphp
                @foreach($jours as $index => $jour)
                <div class="p-4 rounded-xl border {{ $availabilities[$index]->is_working ? 'bg-primary/5 border-primary/30' : 'bg-surface-container-low border-outline-variant/20' }}">
                    <div class="flex items-center justify-between mb-3 border-b border-outline-variant/10 pb-2">
                        <label class="font-bold text-on-surface flex items-center gap-2 cursor-pointer w-full">
                            <input type="hidden" name="availabilities[{{ $index }}][day_of_week]" value="{{ $index }}">
                            <input type="checkbox" name="availabilities[{{ $index }}][is_working]" value="1" {{ $availabilities[$index]->is_working ? 'checked' : '' }} class="w-4 h-4 text-primary bg-surface border-outline rounded focus:ring-primary focus:ring-2" onchange="this.closest('.p-4').classList.toggle('opacity-50', !this.checked)">
                            {{ $jour }}
                        </label>
                    </div>
                    <div class="flex flex-col gap-2 {{ $availabilities[$index]->is_working ? '' : 'opacity-40' }}">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-on-surface-variant w-6">De</span>
                            <input type="time" name="availabilities[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($availabilities[$index]->start_time ?? '09:00')->format('H:i') }}" class="w-full bg-surface text-on-surface text-sm p-1.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-on-surface-variant w-6">À</span>
                            <input type="time" name="availabilities[{{ $index }}][end_time]" value="{{ \Carbon\Carbon::parse($availabilities[$index]->end_time ?? '17:00')->format('H:i') }}" class="w-full bg-surface text-on-surface text-sm p-1.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-on-primary font-bold rounded-lg hover:bg-primary-container transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Enregistrer mes horaires
                </button>
            </div>
        </form>
    </div>

    {{-- Weekly Calendar Grid --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant/10">
        {{-- Day Headers --}}
        @php
            $dayDates = [];
            for ($i = 0; $i < 7; $i++) {
                $dayDates[] = $weekStart->copy()->addDays($i);
            }
        @endphp
        <div class="grid grid-cols-8 border-b border-outline-variant/20">
            <div class="py-3 px-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider bg-surface-container text-center flex items-center justify-center">Heure</div>
            @foreach($dayDates as $day)
            <div class="py-3 px-2 text-center bg-surface-container {{ $day->isToday() ? 'border-b-2 border-primary' : '' }}">
                <p class="text-[10px] font-bold uppercase tracking-wider {{ $day->isToday() ? 'text-primary' : 'text-on-surface-variant' }}">{{ $day->format('D') }}</p>
                <p class="text-lg font-black {{ $day->isToday() ? 'text-primary' : 'text-on-surface' }}">{{ $day->format('d') }}</p>
            </div>
            @endforeach
        </div>

        {{-- Dynamic Time Slots --}}
        @php
            // Calculate dynamic min and max hour based on working hours
            $workingAvails = $availabilities->filter(fn($a) => $a->is_working);
            $minHour = 8;
            $maxHour = 17;
            
            if ($workingAvails->isNotEmpty()) {
                $minHourStr = $workingAvails->min('start_time');
                $maxHourStr = $workingAvails->max('end_time');
                if ($minHourStr) $minHour = (int) \Carbon\Carbon::parse($minHourStr)->format('H');
                if ($maxHourStr) {
                    $maxH = (int) \Carbon\Carbon::parse($maxHourStr)->format('H');
                    $maxM = (int) \Carbon\Carbon::parse($maxHourStr)->format('i');
                    $maxHour = $maxM > 0 ? $maxH : $maxH - 1; // if 18:30, show 18 slot. if 18:00, show up to 17 slot.
                }
            }
            
            $rdvs = \App\Models\RendezVous::where('medecin_id', auth()->id())
                ->whereBetween('date_heure', [$weekStart, $weekEnd->copy()->endOfDay()])
                ->with('patient')
                ->get();
        @endphp
        @for($h = $minHour; $h <= $maxHour; $h++)
        @php $hourStr = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
        <div class="grid grid-cols-8 border-b border-outline-variant/10 min-h-[60px]">
            <div class="py-3 px-4 text-xs font-semibold text-on-surface-variant border-r border-outline-variant/20 flex items-start justify-center">
                {{ $hourStr }}
            </div>
            @foreach($dayDates as $day)
            @php
                $dayOfWeekDbIndex = $day->dayOfWeek; // 0 (Sun) - 6 (Sat)
                $dayAvail = $availabilities[$dayOfWeekDbIndex] ?? null;
                
                $isWorkingHour = false;
                if ($dayAvail && $dayAvail->is_working) {
                    $slotTime = \Carbon\Carbon::parse($hourStr);
                    $startTime = \Carbon\Carbon::parse($dayAvail->start_time);
                    $endTime = \Carbon\Carbon::parse($dayAvail->end_time);
                    if ($slotTime->betweenIncluded($startTime, $endTime->subSeconds(1))) {
                        $isWorkingHour = true;
                    }
                }

                $rdv = $rdvs->first(function($r) use ($day, $h) {
                    return $r->date_heure->format('Y-m-d') === $day->format('Y-m-d')
                        && (int)$r->date_heure->format('H') === $h;
                });
            @endphp
            <div class="border-r border-outline-variant/10 p-1 min-h-[4rem] relative {{ $day->isToday() ? 'bg-primary/5' : '' }} {{ !$isWorkingHour ? 'bg-red-50/50 dark:bg-red-900/10 overflow-hidden' : '' }}">
                @if(!$isWorkingHour)
                    <div class="absolute inset-0 flex flex-col items-center justify-center opacity-60">
                        <span class="material-symbols-outlined text-red-500 text-lg">block</span>
                    </div>
                @endif

                @if($rdv)
                <div class="rounded-lg p-2 text-[11px] font-semibold leading-snug cursor-pointer hover:opacity-90 transition-opacity relative z-10
                    {{ $rdv->statut === 'CONFIRMED' ? 'bg-teal-100 text-teal-800 border-l-2 border-teal-500' : '' }}
                    {{ $rdv->statut === 'PENDING' ? 'bg-amber-100 text-amber-800 border-l-2 border-amber-500' : '' }}
                    {{ $rdv->statut === 'CANCELLED' ? 'bg-red-100 text-red-800 border-l-2 border-red-400 opacity-60' : '' }}
                    {{ !in_array($rdv->statut, ['CONFIRMED','PENDING','CANCELLED']) ? 'bg-secondary-fixed text-on-secondary-fixed border-l-2 border-secondary' : '' }}
                ">
                    <p class="truncate">{{ $rdv->patient->name ?? 'Patient' }} <span class="opacity-75 text-[10px]">({{ $rdv->date_heure->format('H:i') }})</span></p>
                    <p class="font-normal opacity-70 truncate">{{ $rdv->motif ?? 'Consultation' }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endfor
    </div>

    {{-- Upcoming Appointments List --}}
    @php
        $upcoming = \App\Models\RendezVous::where('medecin_id', auth()->id())
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure')
            ->with('patient')
            ->limit(5)
            ->get();
    @endphp

    @if($upcoming->isNotEmpty())
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
        <h3 class="text-lg font-headline font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">upcoming</span>
            Prochains rendez-vous
        </h3>
        <div class="space-y-3">
            @foreach($upcoming as $rdv)
            <div class="flex items-center gap-4 p-4 rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors">
                <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center font-black text-primary text-lg shrink-0">
                    {{ substr($rdv->patient->name ?? 'P', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-on-surface truncate">{{ $rdv->patient->name ?? 'Patient inconnu' }}</p>
                    <p class="text-sm text-on-surface-variant truncate">{{ $rdv->motif ?? 'Consultation générale' }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-sm font-bold text-primary">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('H:i') }}</p>
                    <p class="text-xs text-on-surface-variant">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('d M') }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @if($rdv->statut === 'PENDING' || $rdv->statut === 'EN_ATTENTE')
                    <form action="{{ route('doctor.rendezvous.confirm', $rdv->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-primary text-on-primary text-[10px] font-bold rounded-lg hover:opacity-90 transition-opacity">
                            Confirmer
                        </button>
                    </form>
                    @endif
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $rdv->statut === 'CONFIRMED' ? 'bg-teal-100 text-teal-700' : '' }}
                        {{ $rdv->statut === 'PENDING' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $rdv->statut === 'CANCELLED' ? 'bg-red-100 text-red-700' : '' }}
                        {{ !in_array($rdv->statut ?? '', ['CONFIRMED','PENDING','CANCELLED']) ? 'bg-surface-container text-on-surface-variant' : '' }}
                    ">
                        {{ $rdv->statut ?? 'N/A' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-surface-container-lowest rounded-xl p-12 text-center shadow-sm border border-outline-variant/10">
        <span class="material-symbols-outlined text-5xl text-on-surface-variant/40">event_available</span>
        <p class="mt-4 text-on-surface-variant font-medium">Aucun rendez-vous à venir cette semaine.</p>
        <p class="text-sm text-on-surface-variant/60 mt-1">Votre agenda est libre pour cette période.</p>
    </div>
    @endif

</div>
@endsection

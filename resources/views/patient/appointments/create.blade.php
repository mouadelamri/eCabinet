@extends('patient.layout')

@section('title', 'Nouveau Rendez-vous — eCabinet')

@section('content')
<!-- Workflow Header -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="font-headline text-3xl font-extrabold text-on-surface">Nouveau Rendez-vous</h2>
            <p class="text-on-surface-variant mt-1">Réservez votre consultation en quelques clics.</p>
        </div>
        <a href="{{ route('patient.appointments') }}" class="px-4 py-2 text-sm font-semibold text-outline hover:bg-surface-container rounded-lg transition-colors">Retour</a>
    </div>
</div>

<form id="appointmentForm" action="{{ route('patient.appointments.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8">
    @csrf
    
    <!-- Hidden fields for actual submission -->
    <input type="hidden" name="medecin_id" id="medecin_id" required>
    <input type="hidden" name="date" id="date" required>
    <input type="hidden" name="time" id="time" required>
    <input type="hidden" name="type" value="CONSULTATION">

    <!-- Step 1: Doctor Selection (Bento Grid Style) -->
    <div class="lg:col-span-8 space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-headline text-xl font-bold">1. Médecins disponibles</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($doctors as $medecin)
            <!-- Doctor Card -->
            <label class="group bg-surface-container-lowest p-5 rounded-xl transition-all duration-300 hover:shadow-lg cursor-pointer ring-1 ring-outline-variant/15 doctor-card" data-id="{{ $medecin->id }}" data-name="{{ $medecin->name }}">
                <input type="radio" name="medecin_radio" value="{{ $medecin->id }}" class="hidden" onchange="selectDoctor({{ $medecin->id }}, '{{ addslashes($medecin->name) }}')">
                <div class="flex gap-4">
                    <div class="relative h-16 w-16 shrink-0 rounded-lg overflow-hidden bg-primary-fixed flex items-center justify-center text-primary font-bold text-xl">
                        {{ strtoupper(substr($medecin->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-headline font-bold text-lg leading-tight">Dr. {{ $medecin->name }}</h4>
                        <p class="text-sm text-primary font-medium">{{ $medecin->specialiste ?? 'Généraliste' }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-surface-container flex justify-between items-center">
                    <span class="text-xs text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">location_on</span> Cabinet E-Santé
                    </span>
                    <span class="text-xs font-bold text-primary group-hover:underline doctor-select-lbl">Sélectionner</span>
                </div>
            </label>
            @endforeach
        </div>

        <div class="mt-6">
            <label class="font-headline text-lg font-bold block mb-2">2. Motif de consultation</label>
            <textarea name="motif" required rows="2" class="w-full bg-surface-container-lowest border border-outline-variant/30 text-sm rounded-xl p-4 focus:ring-primary focus:border-primary" placeholder="Décrivez brièvement la raison de votre visite (ex: Douleurs au dos, Renouvellement d'ordonnance...)"></textarea>
        </div>
    </div>

    <!-- Step 2: Time Slot Selection (Pills & Calendar) -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-outline-variant/10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline font-bold">3. Disponibilités</h3>
                <input type="date" id="datePicker" class="text-xs border border-outline-variant/30 rounded-lg px-2 py-1 bg-surface-container" onchange="selectDate(this.value)" min="{{ date('Y-m-d') }}">
            </div>
            
            <div class="space-y-4 pt-4">
                <div>
                    <p class="text-xs font-bold text-on-surface-variant mb-3 uppercase tracking-wider">Créneaux</p>
                    <div class="grid grid-cols-3 gap-2" id="timeSlotsContainer">
                        @foreach(['09:00', '09:30', '10:00', '11:00', '14:00', '14:30', '15:00', '16:00', '17:30'] as $time)
                        <button type="button" class="py-2 text-xs font-semibold rounded-full bg-surface-container-high hover:bg-primary-fixed hover:text-primary transition-all time-slot" data-time="{{ $time }}" onclick="selectTime('{{ $time }}')">
                            {{ $time }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" id="submitBtn" disabled class="w-full mt-8 py-4 bg-primary text-on-primary font-bold rounded-xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100 disabled:cursor-not-allowed">
                Confirmer le rendez-vous
            </button>
        </div>

        <!-- Summary Card (Editorial Style) -->
        <div class="bg-secondary-container/5 rounded-2xl p-6 border-l-4 border-secondary hidden" id="summaryCard">
            <p class="text-secondary font-bold text-sm">Récapitulatif provisoire</p>
            <div class="mt-4 flex items-start gap-4">
                <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-secondary" data-icon="person">person</span>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant">Praticien</p>
                    <p class="font-bold" id="summaryDoctor">-</p>
                </div>
            </div>
            <div class="mt-3 flex items-start gap-4">
                <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-secondary" data-icon="schedule">schedule</span>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant">Date & Heure</p>
                    <p class="font-bold" id="summaryDateTime">-</p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let selectedDoctorId = null;
    let selectedDoctorName = null;
    let selectedDate = null;
    let selectedTime = null;

    function selectDoctor(id, name) {
        selectedDoctorId = id;
        selectedDoctorName = name;
        document.getElementById('medecin_id').value = id;
        
        // Update UI
        document.querySelectorAll('.doctor-card').forEach(card => {
            card.classList.remove('ring-primary', 'ring-2', 'bg-primary-container/10');
            card.classList.add('ring-outline-variant/15', 'bg-surface-container-lowest');
            card.querySelector('.doctor-select-lbl').textContent = 'Sélectionner';
        });
        
        const selectedCard = document.querySelector(`.doctor-card[data-id="${id}"]`);
        selectedCard.classList.remove('ring-outline-variant/15', 'bg-surface-container-lowest');
        selectedCard.classList.add('ring-primary', 'ring-2', 'bg-primary-container/10');
        selectedCard.querySelector('.doctor-select-lbl').textContent = 'Sélectionné';

        updateSummary();
        checkValidation();
    }

    function selectDate(date) {
        selectedDate = date;
        document.getElementById('date').value = date;
        updateSummary();
        checkValidation();
    }

    function selectTime(time) {
        selectedTime = time;
        document.getElementById('time').value = time;
        
        // Update UI
        document.querySelectorAll('.time-slot').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-white', 'ring-2', 'ring-primary-fixed');
            btn.classList.add('bg-surface-container-high');
        });
        
        const selectedBtn = document.querySelector(`.time-slot[data-time="${time}"]`);
        selectedBtn.classList.remove('bg-surface-container-high');
        selectedBtn.classList.add('bg-primary', 'text-white', 'ring-2', 'ring-primary-fixed');

        updateSummary();
        checkValidation();
    }

    function updateSummary() {
        if(selectedDoctorName || selectedDate || selectedTime) {
            document.getElementById('summaryCard').classList.remove('hidden');
        }
        
        document.getElementById('summaryDoctor').textContent = selectedDoctorName ? "Dr. " + selectedDoctorName : "-";
        
        let dateStr = "-";
        if(selectedDate && selectedTime) {
            dateStr = selectedDate + " à " + selectedTime;
        } else if (selectedDate) {
            dateStr = selectedDate;
        } else if (selectedTime) {
            dateStr = selectedTime;
        }
        document.getElementById('summaryDateTime').textContent = dateStr;
    }

    function checkValidation() {
        const btn = document.getElementById('submitBtn');
        if(selectedDoctorId && selectedDate && selectedTime) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }
</script>
@endsection

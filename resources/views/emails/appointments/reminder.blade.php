@component('mail::message')
# Rappel : Votre rendez-vous approche

Bonjour {{ $patient->name }},

Ceci est un rappel pour votre rendez-vous prévu demain avec **Dr. {{ $doctor->name }}**.

**Rappel des détails :**
- **Date :** {{ \Carbon\Carbon::parse($appointment->date_heure)->translatedFormat('l d F') }}
- **Heure :** {{ \Carbon\Carbon::parse($appointment->date_heure)->format('H:i') }}

En cas d'empêchement, merci de nous prévenir ou d'annuler votre rendez-vous via votre espace patient.

@component('mail::button', ['url' => route('patient.appointments')])
Accéder à mon espace
@endcomponent

À demain,<br>
L'équipe {{ config('app.name') }}
@endcomponent

@component('mail::message')
# Votre rendez-vous est confirmé !

Bonjour {{ $patient->name }},

Bonne nouvelle ! Votre rendez-vous avec **Dr. {{ $doctor->name }}** a été confirmé.

**Détails de la consultation :**
- **Date :** {{ \Carbon\Carbon::parse($appointment->date_heure)->translatedFormat('d F Y') }}
- **Heure :** {{ \Carbon\Carbon::parse($appointment->date_heure)->format('H:i') }}
- **Lieu :** Cabinet eCabinet

@component('mail::button', ['url' => route('patient.appointments')])
Voir mes rendez-vous
@endcomponent

Merci de votre confiance,<br>
L'équipe {{ config('app.name') }}
@endcomponent

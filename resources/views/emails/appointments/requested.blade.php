@component('mail::message')
# Demande de rendez-vous reçue

Bonjour {{ $patient->name }},

Nous avons bien reçu votre demande de rendez-vous pour une consultation avec **Dr. {{ $doctor->name }}**.

**Détails du rendez-vous :**
- **Date :** {{ \Carbon\Carbon::parse($appointment->date_heure)->translatedFormat('d F Y') }}
- **Heure :** {{ \Carbon\Carbon::parse($appointment->date_heure)->format('H:i') }}
- **Motif :** {{ $appointment->motif }}

Votre rendez-vous est actuellement **en attente de confirmation** par le médecin. Vous recevrez une notification dès qu'il sera validé.

@component('mail::button', ['url' => route('patient.appointments')])
Voir mes rendez-vous
@endcomponent

Merci de votre confiance,<br>
L'équipe {{ config('app.name') }}
@endcomponent

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance Médicale</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #00685f; padding-bottom: 20px; margin-bottom: 30px; }
        .doctor-name { font-size: 24px; font-weight: bold; color: #00685f; margin: 0; }
        .specialty { font-size: 16px; color: #666; margin: 5px 0; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { vertical-align: top; }
        .date { text-align: right; font-style: italic; margin-bottom: 20px; }
        .rx-title { font-size: 22px; font-weight: bold; margin-bottom: 20px; color: #00685f; text-align: center; text-decoration: underline; }
        .medicaments { min-height: 300px; font-size: 16px; padding: 20px; background-color: #f9f9f9; border-radius: 5px; }
        .signature { text-align: right; margin-top: 50px; font-weight: bold; }
        .footer { position: absolute; bottom: 0; width: 100%; text-align: center; font-size: 12px; color: #aaa; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="doctor-name">Dr. {{ $doctor->name }}</h1>
        <p class="specialty">{{ $doctor->specialiste ?? 'Médecin' }}</p>
        <p>Cabinet Médical eCabinet | Tél: {{ $doctor->telephone_pro ?? '+212 500 000 000' }}</p>
    </div>

    <div class="date">
        Fait à Marrakech, le {{ date('d/m/Y') }}
    </div>

    <table class="info-table">
        <tr>
            <td>
                <strong>Patient(e) :</strong> {{ $ordonnance->patient->name ?? 'Nom du Patient' }}<br>
                <strong>Date de naissance :</strong> {{ $ordonnance->patient->date_naissance ?? '--/--/----' }}
            </td>
        </tr>
    </table>

    <div class="rx-title">ORDONNANCE</div>

    <div class="medicaments">
        {!! nl2br(e($ordonnance->medicaments ?? "1. Paracétamol 1g - 1 comp 3x/jour \n 2. Amoxicilline 500mg - 1 gélule 2x/jour")) !!}
    </div>

    <div class="signature">
        <p>Signature et Cachet :</p>
    </div>

    <div class="footer">
        eCabinet - Système de gestion de cabinet médical
    </div>

</body>
</html>
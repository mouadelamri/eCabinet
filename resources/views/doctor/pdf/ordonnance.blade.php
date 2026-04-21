<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance - {{ $ordonnance->consultation->rendezVous->patient->name ?? 'Patient' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #0d9488; /* Teal color */
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .doctor-info {
            float: left;
            width: 50%;
        }
        .clinic-info {
            float: right;
            width: 50%;
            text-align: right;
        }
        .clear {
            clear: both;
        }
        h1 {
            color: #0d9488;
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .patient-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 40px;
        }
        .title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 40px;
            color: #1e293b;
            text-transform: uppercase;
        }
        .medications {
            min-height: 300px;
            font-size: 16px;
            line-height: 2;
            padding: 20px;
        }
        .signature-area {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 50px;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="doctor-info">
            <h1>Dr. {{ auth()->user()->name ?? 'Médecin' }}</h1>
            <strong>{{ auth()->user()->specialiste ?? 'Médecin Généraliste' }}</strong><br>
            Tél : {{ auth()->user()->telephone_pro ?? 'Non renseigné' }}<br>
            Email : {{ auth()->user()->email ?? '' }}
        </div>
        <div class="clinic-info">
            <h2>eCabinet Médical</h2>
            <strong>Date :</strong> {{ $ordonnance->created_at->format('d/m/Y') }}<br>
            <strong>Réf :</strong> #ORD-{{ $ordonnance->id }}
        </div>
        <div class="clear"></div>
    </div>

    <div class="patient-box">
        <strong>Patient(e) :</strong> {{ $ordonnance->consultation->rendezVous->patient->name ?? 'N/A' }} <br>
        <strong>Âge :</strong> 
        @if(isset($ordonnance->consultation->rendezVous->patient->date_naissance))
            {{ \Carbon\Carbon::parse($ordonnance->consultation->rendezVous->patient->date_naissance)->age }} ans
        @else
            Non précisé
        @endif
        <br>
        <strong>Diagnostic :</strong> {{ $ordonnance->consultation->diagnostic ?? 'Examen général' }}
    </div>

    <div class="title">ORDONNANCE</div>

    <div class="medications">
        {!! nl2br(e($ordonnance->contenu_medicaments)) !!}
    </div>

    <div class="signature-area">
        <strong>Signature & Cachet :</strong>
        <br>
        @if(auth()->user()->signature_path)
            <img src="{{ public_path('storage/'.auth()->user()->signature_path) }}" style="max-height: 80px; max-width: 200px; margin-top: 10px;">
        @else
            <br><br><br>
            <hr style="border-top: 1px dashed #ccc;">
        @endif
    </div>

    <div class="clear"></div>

    <div class="footer">
        Document généré électroniquement par eCabinet le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
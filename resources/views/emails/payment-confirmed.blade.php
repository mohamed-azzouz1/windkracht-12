<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betaling Bevestigd</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }
        .reservation-details {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .info-label {
            font-weight: bold;
            color: #10b981;
        }
        .payment-details {
            background-color: #ecfdf5;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #a7f3d0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Betaling Bevestigd</h1>
    </div>
    
    <div class="content">
        <p>Beste {{ $registration->student->user->name ?? 'klant' }},</p>
        
        <p>Hartelijk dank voor je betaling! Je reservering voor de kitesurfles is nu bevestigd.</p>
        
        <div class="reservation-details">
            <p><span class="info-label">Pakket:</span> {{ $registration->package->name }}</p>
            <p><span class="info-label">Datum:</span> {{ $registration->start_date->format('d-m-Y') }}</p>
            <p><span class="info-label">Tijd:</span> {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
            <p><span class="info-label">Locatie:</span> {{ ucfirst($registration->location ?? 'Niet gespecificeerd') }}</p>
            <p><span class="info-label">Instructeur:</span> {{ $registration->instructor->user->name }}</p>
        </div>
        
        <div class="payment-details">
            <h3>Betalingsgegevens</h3>
            <p><span class="info-label">Betaald op:</span> {{ $registration->payment_date ? $registration->payment_date->format('d-m-Y') : now()->format('d-m-Y') }}</p>
            <p><span class="info-label">Bedrag:</span> €{{ number_format($registration->package->price, 2, ',', '.') }}</p>
            @if($registration->payment_reference)
                <p><span class="info-label">Referentie:</span> {{ $registration->payment_reference }}</p>
            @endif
        </div>
        
        <p>Wat je moet weten voor je les:</p>
        <ul>
            <li>Kom ten minste 15 minuten voor aanvang van je les</li>
            <li>Neem zwemkleding en een handdoek mee</li>
            <li>Zorg voor bescherming tegen de zon (zonnebrandcrème, zonnebril)</li>
            <li>Wij zorgen voor alle benodigde kitesurfuitrusting</li>
        </ul>
        
        <p>Bij slecht weer of ongunstige windomstandigheden kan het zijn dat je les wordt verplaatst. In dat geval nemen we tijdig contact met je op.</p>
        
        <p>Heb je vragen? Neem gerust contact met ons op.</p>
        
        <p>We kijken ernaar uit je te verwelkomen!</p>
        
        <p>Met vriendelijke groet,<br>
        Het team van Windkracht 12</p>
    </div>
    
    <div class="footer">
        <p>Windkracht 12 Kitesurfschool</p>
        <p>Strandopgang 12, Noordwijk | 070-1234567 | info@windkracht12.nl</p>
        <p>© {{ date('Y') }} Windkracht 12. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>

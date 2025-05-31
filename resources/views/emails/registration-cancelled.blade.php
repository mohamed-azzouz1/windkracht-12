<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitesurfles geannuleerd</title>
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
            background-color: #ef4444;
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
            color: #ef4444;
        }
        .cancellation-details {
            background-color: #fef2f2;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kitesurfles Geannuleerd</h1>
    </div>
    
    <div class="content">
        <p>Beste {{ $registration->student->user->name ?? 'klant' }},</p>
        
        <p>Helaas moeten we je informeren dat je geplande kitesurfles is geannuleerd.</p>
        
        <div class="reservation-details">
            <p><span class="info-label">Pakket:</span> {{ $registration->package->name }}</p>
            <p><span class="info-label">Datum:</span> {{ $registration->start_date->format('d-m-Y') }}</p>
            <p><span class="info-label">Tijd:</span> {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
            <p><span class="info-label">Locatie:</span> {{ ucfirst($registration->location ?? 'Niet gespecificeerd') }}</p>
        </div>
        
        <div class="cancellation-details">
            <h3>Reden voor annulering</h3>
            <p>{{ $registration->cancellation_reason ?? 'Er is geen specifieke reden opgegeven.' }}</p>
            
            @if($registration->cancellation_type == 'weather')
                <p>Vanwege de weersomstandigheden kunnen we helaas niet veilig les geven. De veiligheid van onze cursisten staat altijd voorop.</p>
            @elseif($registration->cancellation_type == 'instructor_sick')
                <p>Helaas is je instructeur ziek en kunnen we op dit moment geen vervanging regelen.</p>
            @endif
        </div>
        
        @if($registration->is_paid)
        <div class="refund-details">
            <h3>Terugbetaling</h3>
            <p>Omdat je les is geannuleerd, zullen we het betaalde bedrag terugstorten. Dit kan tot 5 werkdagen duren.</p>
            <p>Als je vragen hebt over de terugbetaling, neem dan contact met ons op via de onderstaande contactgegevens.</p>
        </div>
        @endif
        
        <p>We bieden je graag een nieuwe afspraak aan. Je kunt een nieuwe les boeken via onze website of door contact met ons op te nemen.</p>
        
        <p>Onze excuses voor het ongemak.</p>
        
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

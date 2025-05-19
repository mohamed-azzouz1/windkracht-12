<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitesurfles Bevestiging</title>
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
            background-color: #0284c7;
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
        .lesson-details {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .info-label {
            font-weight: bold;
            color: #0284c7;
        }
        .button {
            display: inline-block;
            background-color: #0284c7;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kitesurfles Bevestiging</h1>
    </div>
    
    <div class="content">
        @if($recipientType === 'student')
            <p>Beste {{ $registration->student->user->name }},</p>
            <p>Geweldig nieuws! Je kitesurfles bij Windkracht 12 is bevestigd. Je betaling is ontvangen en alles is klaar voor je les.</p>
        @elseif($recipientType === 'instructor')
            <p>Beste {{ $registration->instructor->user->name }},</p>
            <p>Er is een nieuwe les toegewezen aan jouw rooster. Hier zijn de details:</p>
        @else
            <p>Beste {{ $registration->duo_name }},</p>
            <p>Geweldig nieuws! Je kitesurfles bij Windkracht 12 is bevestigd. De betaling is ontvangen en alles is klaar voor je les.</p>
        @endif
        
        <div class="lesson-details">
            <p><span class="info-label">Pakket:</span> {{ $registration->package->name }}</p>
            <p><span class="info-label">Datum:</span> {{ $registration->start_date->format('d-m-Y') }}</p>
            <p><span class="info-label">Tijd:</span> {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
            <p><span class="info-label">Locatie:</span> Strandopgang 12, Noordwijk</p>
            
            @if($recipientType === 'student' || $recipientType === 'duo')
                <p><span class="info-label">Instructeur:</span> {{ $registration->instructor->user->name }}</p>
            @elseif($recipientType === 'instructor')
                <p><span class="info-label">Student:</span> {{ $registration->student->user->name }}</p>
                @if($registration->duo_name)
                    <p><span class="info-label">Duo-student:</span> {{ $registration->duo_name }}</p>
                @endif
            @endif
        </div>
        
        <p><strong>Belangrijk om mee te nemen:</strong></p>
        <ul>
            <li>Zwemkleding</li>
            <li>Handdoek</li>
            <li>Zonnebrand</li>
            <li>Droge kleding voor na de les</li>
        </ul>
        
        <p>Kom graag 30 minuten voor aanvang van de les, zodat we op tijd kunnen beginnen.</p>
        
        @if($recipientType === 'student' || $recipientType === 'duo')
            <p>Als je vragen hebt of de les moet annuleren, neem dan contact met ons op via onderstaande gegevens. Let op: annuleren kan alleen onder bepaalde voorwaarden die in onze algemene voorwaarden staan.</p>
            <a href="#" class="button">Voeg toe aan mijn agenda</a>
        @else
            <p>Je kunt alle details van deze en andere geplande lessen bekijken in je instructeursdashboard.</p>
            <a href="#" class="button">Bekijk lesrooster</a>
        @endif
    </div>
    
    <div class="footer">
        <p>Windkracht 12 Kitesurfschool</p>
        <p>Strandopgang 12, Noordwijk | 070-1234567 | info@windkracht12.nl</p>
        <p>© {{ date('Y') }} Windkracht 12. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>

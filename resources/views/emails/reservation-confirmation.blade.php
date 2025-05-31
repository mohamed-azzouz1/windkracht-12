<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservering Kitesurfles</title>
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
            background-color: #3b82f6;
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
            color: #3b82f6;
        }
        .payment-details {
            background-color: #eff6ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #dbeafe;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reservering Kitesurfles</h1>
    </div>
    
    <div class="content">
        @if($recipientType === 'student')
            <p>Beste {{ $registration->student->user->name ?? 'klant' }},</p>
            
            <p>Bedankt voor je reservering bij Windkracht 12 Kitesurfschool! We hebben je reservering ontvangen en deze is nu in afwachting van betaling.</p>
        @else
            <p>Beste {{ $registration->instructor->user->name ?? 'instructeur' }},</p>
            
            <p>Er is een nieuwe reservering gemaakt waar jij als instructeur aan bent toegewezen. Hieronder vind je de details.</p>
        @endif
        
        <div class="reservation-details">
            <p><span class="info-label">Reserveringsnummer:</span> #{{ $registration->id }}</p>
            <p><span class="info-label">Pakket:</span> {{ $registration->package->name }}</p>
            <p><span class="info-label">Datum:</span> {{ $registration->start_date->format('d-m-Y') }}</p>
            <p><span class="info-label">Tijd:</span> {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
            <p><span class="info-label">Locatie:</span> {{ ucfirst($registration->location ?? 'Niet gespecificeerd') }}</p>
            
            @if($recipientType === 'instructor')
                <p><span class="info-label">Student:</span> {{ $registration->student->user->name }}</p>
                <p><span class="info-label">Contact:</span> {{ $registration->student->user->email }}</p>
                @if($registration->student->phone)
                    <p><span class="info-label">Telefoon:</span> {{ $registration->student->phone }}</p>
                @endif
            @else
                <p><span class="info-label">Instructeur:</span> {{ $registration->instructor->user->name }}</p>
            @endif
            
            @if($registration->duo_name)
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p><span class="info-label">Duo Partner:</span> {{ $registration->duo_name }}</p>
                    @if($registration->duo_email)
                        <p><span class="info-label">Email:</span> {{ $registration->duo_email }}</p>
                    @endif
                    @if($registration->duo_phone)
                        <p><span class="info-label">Telefoon:</span> {{ $registration->duo_phone }}</p>
                    @endif
                </div>
            @endif
        </div>
        
        @if($recipientType === 'student')
            <div class="payment-details">
                <h3>Betalingsinformatie</h3>
                <p><span class="info-label">Bedrag te betalen:</span> €{{ number_format($registration->package->price, 2, ',', '.') }}</p>
                <p><span class="info-label">Status:</span> {{ $registration->is_paid ? 'Betaald' : 'Niet betaald' }}</p>
                
                @if($registration->package->is_duo)
                    <p><span class="info-label">Type:</span> Duopakket voor 2 personen</p>
                    <p>Let op: Als hoofdboeker ben je verantwoordelijk voor de volledige betaling van dit duopakket.</p>
                @endif
                
                @if(!$registration->is_paid)
                    <p>Om je reservering te bevestigen, vragen we je om binnen 48 uur te betalen. Je kunt betalen via onze website of door het bedrag over te maken naar:</p>
                    <p>
                        <strong>Bank:</strong> ING Bank<br>
                        <strong>Rekening:</strong> NL12 INGB 0123 4567 89<br>
                        <strong>Ten name van:</strong> Windkracht 12 B.V.<br>
                        <strong>Onder vermelding van:</strong> Reservering #{{ $registration->id }}
                    </p>
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
        @endif
        
        <p>Heb je vragen? Neem gerust contact met ons op.</p>
        
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

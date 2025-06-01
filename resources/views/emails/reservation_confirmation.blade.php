<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bevestiging Reservering</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0066cc;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .lesson {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .payment-info {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        h2 {
            color: #0066cc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $isDuoRecipient ? 'Uitnodiging voor Kitesurf Lessen' : 'Bevestiging van je Reservering' }}</h1>
        </div>
        
        <div class="content">
            @if($isDuoRecipient)
                <p>Beste {{ $registrations[0]->duo_name }},</p>
                <p>Je bent uitgenodigd door {{ $student->user->name }} om deel te nemen aan kitesurf lessen bij Windkracht 12.</p>
            @else
                <p>Beste {{ $student->user->name }},</p>
                <p>Bedankt voor je reservering bij Windkracht 12. Hieronder vind je een overzicht van je geboekte lessen.</p>
            @endif
            
            <h2>Pakketinformatie</h2>
            <p><strong>Pakket:</strong> {{ $package->name }}</p>
            <p><strong>Omschrijving:</strong> {{ $package->description }}</p>
            <p><strong>Prijs:</strong> €{{ number_format($package->price, 2, ',', '.') }}</p>
            
            <h2>Geboekte Lessen</h2>
            @foreach($registrations as $registration)
                <div class="lesson">
                    <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($registration->start_date)->format('d-m-Y') }}</p>
                    <p><strong>Tijd:</strong> {{ \Carbon\Carbon::parse($registration->start_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($registration->end_date)->format('H:i') }}</p>
                    <p><strong>Locatie:</strong> {{ ucfirst($registration->location) }}</p>
                    <p><strong>Instructeur:</strong> {{ $registration->instructor->user->name }}</p>
                </div>
            @endforeach
            
            @if($isDuo)
                <h2>Deelnemers</h2>
                <ul>
                    <li>{{ $student->user->name }} (hoofdboeker)</li>
                    <li>{{ $registrations[0]->duo_name }} (tweede deelnemer)</li>
                </ul>
            @endif
            
            @if(!$isDuoRecipient)
                <div class="payment-info">
                    <h2>Betalingsinformatie</h2>
                    <p>Om je reservering definitief te maken, vragen we je het totaalbedrag van <strong>€{{ number_format($invoice->amount, 2, ',', '.') }}</strong> over te maken naar:</p>
                    
                    <table>
                        <tr>
                            <th>Rekeningnummer</th>
                            <td>{{ $paymentInfo['bankAccount'] }}</td>
                        </tr>
                        <tr>
                            <th>Ten name van</th>
                            <td>{{ $paymentInfo['accountName'] }}</td>
                        </tr>
                        <tr>
                            <th>Onder vermelding van</th>
                            <td>{{ $paymentInfo['reference'] }}</td>
                        </tr>
                    </table>
                    
                    <p>Betaal vóór {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }} om je reservering te bevestigen.</p>
                </div>
            @endif
            
            <p>Voor vragen over je reservering kun je contact met ons opnemen via info@windkracht12.nl of bel ons op 070-1234567.</p>
            
            <p>Met vriendelijke groet,<br>
            Team Windkracht 12</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Windkracht 12 | De beste kitesurflessen van Nederland</p>
        </div>
    </div>
</body>
</html>

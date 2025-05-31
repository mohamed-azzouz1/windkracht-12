<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annulering Kitesurfles</title>
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
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }
        .button {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kitesurfles Geannuleerd</h1>
    </div>
    
    <div class="content">
        <p>Beste {{ $registration->student->user->name }},</p>
        
        <p>We moeten helaas je geplande les op <strong>{{ $registration->start_date->format('d-m-Y') }}</strong> om <strong>{{ $registration->start_date->format('H:i') }}</strong> annuleren.</p>
        
        <p><strong>Reden van annulering:</strong><br>
        {{ $reason }}</p>
        
        <div class="lesson-details">
            <h3>Lesgegevens</h3>
            <p><span class="info-label">Datum:</span> {{ $registration->start_date->format('d-m-Y') }}</p>
            <p><span class="info-label">Tijd:</span> {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
            <p><span class="info-label">Locatie:</span> {{ ucfirst($registration->location) }}</p>
            <p><span class="info-label">Pakket:</span> {{ $registration->package->name }}</p>
            <p><span class="info-label">Instructeur:</span> {{ $registration->instructor->user->name }}</p>
        </div>
        
        <p>Om een nieuwe les in te plannen, kun je contact met ons opnemen of een nieuwe reservering maken via onze website.</p>
        
        <p><a href="{{ route('login') }}" class="button">Naar website</a></p>
        
        <p>Onze excuses voor het ongemak.</p>
        
        <p>Met vriendelijke groet,<br>
        Het team van {{ config('app.name') }}</p>
    </div>
    
    <div class="footer">
        <p>Windkracht 12 Kitesurfschool</p>
        <p>Strandopgang 12, Noordwijk | 070-1234567 | info@windkracht12.nl</p>
        <p>© {{ date('Y') }} Windkracht 12. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>

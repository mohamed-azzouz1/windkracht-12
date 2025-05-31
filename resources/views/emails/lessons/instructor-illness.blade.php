@component('mail::message')
# Les Annulering - Ziekte Instructeur

Beste {{ $registration->student->user->name }},

We moeten helaas je geplande les op **{{ $registration->start_date->format('d-m-Y') }}** om **{{ $registration->start_date->format('H:i') }}** annuleren wegens ziekte van je instructeur.

## Lesgegevens
- **Datum:** {{ $registration->start_date->format('d-m-Y') }}
- **Tijd:** {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}
- **Locatie:** {{ ucfirst($registration->location) }}
- **Pakket:** {{ $registration->package->name }}
- **Instructeur:** {{ $registration->instructor->user->name }}

We zullen zo snel mogelijk contact met je opnemen om een nieuwe les in te plannen. Als je voorkeur hebt voor een specifieke datum en tijd, kun je ook direct reageren op deze e-mail of telefonisch contact met ons opnemen.

@component('mail::button', ['url' => route('login')])
Naar website
@endcomponent

Onze excuses voor het ongemak.

Met vriendelijke groet,<br>
Het team van {{ config('app.name') }}
@endcomponent

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Reservering Details</h1>
                    <a href="{{ route('student.reservations.list') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar overzicht
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- Reservation Status -->
                <div class="mb-6">
                    <div class="bg-{{ $registration->status == 'confirmed' ? 'green' : ($registration->status == 'pending' ? 'yellow' : ($registration->status == 'cancelled' ? 'red' : 'gray')) }}-100 
                        border-l-4 border-{{ $registration->status == 'confirmed' ? 'green' : ($registration->status == 'pending' ? 'yellow' : ($registration->status == 'cancelled' ? 'red' : 'gray')) }}-500 p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($registration->status == 'confirmed')
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @elseif($registration->status == 'pending')
                                    <i class="fas fa-clock text-yellow-500"></i>
                                @elseif($registration->status == 'cancelled')
                                    <i class="fas fa-times-circle text-red-500"></i>
                                @else
                                    <i class="fas fa-info-circle text-gray-500"></i>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm leading-5 font-medium text-{{ $registration->status == 'confirmed' ? 'green' : ($registration->status == 'pending' ? 'yellow' : ($registration->status == 'cancelled' ? 'red' : 'gray')) }}-800">
                                    Status: {{ ucfirst($registration->status) }}
                                </p>
                                <p class="text-sm leading-5 text-{{ $registration->status == 'confirmed' ? 'green' : ($registration->status == 'pending' ? 'yellow' : ($registration->status == 'cancelled' ? 'red' : 'gray')) }}-700 mt-1">
                                    @if($registration->status == 'confirmed')
                                        Je reservering is bevestigd. We verwachten je op de geplande datum en tijd.
                                    @elseif($registration->status == 'pending')
                                        Je reservering wordt nog verwerkt. Je ontvangt een bevestiging zodra deze is goedgekeurd.
                                    @elseif($registration->status == 'cancelled')
                                        Deze reservering is geannuleerd. {{ $registration->cancellation_reason ? 'Reden: ' . $registration->cancellation_reason : '' }}
                                    @else
                                        Status informatie niet beschikbaar.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reservation Details -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Reserveringsgegevens</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <tbody>
                                <tr class="border-t">
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Les Datum
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->start_date ? $registration->start_date->format('d-m-Y') : 'Niet ingesteld' }}
                                    </td>
                                </tr>
                                <tr class="border-t">
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tijdstip
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->start_date ? $registration->start_date->format('H:i') . ' - ' . $registration->end_date->format('H:i') : 'Niet ingesteld' }}
                                    </td>
                                </tr>
                                <tr class="border-t">
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Locatie
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ ucfirst($registration->location ?? 'Niet ingesteld') }}
                                    </td>
                                </tr>
                                <tr class="border-t">
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Instructor
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->instructor->user->name ?? 'Nog niet toegewezen' }}
                                    </td>
                                </tr>
                                <tr class="border-t">
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pakket
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->package->name ?? 'Onbekend pakket' }}
                                    </td>
                                </tr>
                                <tr class="border-t">
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Referentie
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->reservation_ref ?? 'Geen referentie' }}
                                    </td>
                                </tr>
                                @if($registration->duo_name)
                                <tr class="border-t">
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duo Partner
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->duo_name }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if(isset($relatedRegistrations) && $relatedRegistrations->count() > 1)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Gerelateerde Lessen</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Datum
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tijd
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($relatedRegistrations as $reg)
                                <tr class="border-t {{ $reg->id == $registration->id ? 'bg-blue-50' : '' }}">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $reg->start_date ? $reg->start_date->format('d-m-Y') : 'Niet ingesteld' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $reg->start_date ? $reg->start_date->format('H:i') . ' - ' . $reg->end_date->format('H:i') : 'Niet ingesteld' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $reg->status == 'confirmed' ? 'bg-green-100 text-green-800' : ($reg->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($reg->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($reg->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                
                <!-- Payment Status -->
                @if(isset($invoice))
                <div class="mt-8 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Betalingsstatus</h2>
                    <div class="bg-{{ $invoice->status == 'paid' ? 'green' : 'yellow' }}-100 border-l-4 border-{{ $invoice->status == 'paid' ? 'green' : 'yellow' }}-500 p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-{{ $invoice->status == 'paid' ? 'check-circle text-green' : 'exclamation-circle text-yellow' }}-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm leading-5 font-medium text-{{ $invoice->status == 'paid' ? 'green' : 'yellow' }}-800">
                                    Status: {{ $invoice->status == 'paid' ? 'Betaald' : 'Niet betaald' }}
                                </p>
                                @if($invoice->status != 'paid')
                                <p class="text-sm leading-5 text-yellow-700 mt-1">
                                    Betaling is vereist om je reservering te bevestigen. Betaal vóór {{ $invoice->due_date->format('d-m-Y') }}.
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="flex mt-8">
                    @if($registration->status != 'cancelled')
                    <a href="{{ route('student.reservations.cancel.form', $registration->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded mr-4">
                        <i class="fas fa-times-circle mr-1"></i>Annuleren
                    </a>
                    @endif
                    
                    @if(isset($invoice) && $invoice->status != 'paid')
                    <a href="{{ route('student.reservations.payment.form', $registration->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-credit-card mr-1"></i>Betaling melden
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Reserveringsdetails</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('student.reservations.list') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i>Terug naar overzicht
                        </a>
                        @if(!$reservation->is_paid)
                        <a href="{{ route('student.reservations.payment.form', $reservation->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-credit-card mr-1"></i>Betaling melden
                        </a>
                        @endif
                    </div>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Reservation Status -->
                <div class="mb-6">
                    <div class="bg-{{ $reservation->status === 'confirmed' ? 'green' : ($reservation->status === 'pending' ? 'yellow' : ($reservation->status === 'cancelled' ? 'red' : 'blue')) }}-50 
                              border-l-4 border-{{ $reservation->status === 'confirmed' ? 'green' : ($reservation->status === 'pending' ? 'yellow' : ($reservation->status === 'cancelled' ? 'red' : 'blue')) }}-500 
                              p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-{{ $reservation->status === 'confirmed' ? 'check-circle' : ($reservation->status === 'pending' ? 'clock' : ($reservation->status === 'cancelled' ? 'times-circle' : 'info-circle')) }} 
                                          text-{{ $reservation->status === 'confirmed' ? 'green' : ($reservation->status === 'pending' ? 'yellow' : ($reservation->status === 'cancelled' ? 'red' : 'blue')) }}-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-{{ $reservation->status === 'confirmed' ? 'green' : ($reservation->status === 'pending' ? 'yellow' : ($reservation->status === 'cancelled' ? 'red' : 'blue')) }}-700">
                                    @if($reservation->status === 'confirmed')
                                        <span class="font-medium">Bevestigd!</span> Je reservering is bevestigd. We verwachten je op de geplande datum en tijd.
                                    @elseif($reservation->status === 'pending')
                                        <span class="font-medium">In afwachting van betaling</span> Je reservering wordt bevestigd zodra de betaling is ontvangen.
                                    @elseif($reservation->status === 'cancelled')
                                        <span class="font-medium">Geannuleerd</span> Deze reservering is geannuleerd.
                                        @if($reservation->cancellation_reason)
                                        <br>Reden: {{ $reservation->cancellation_reason }}
                                        @endif
                                    @else
                                        <span class="font-medium">{{ ucfirst($reservation->status) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reservation Details -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Lesgegevens</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Details van je kitesurfles.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Pakket</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->package->name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Datum</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->start_date->format('d-m-Y') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Tijd</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->start_date->format('H:i') }} - {{ $reservation->end_date->format('H:i') }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Locatie</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @php
                                        $locations = [
                                            'noordwijk' => 'Noordwijk',
                                            'scheveningen' => 'Scheveningen',
                                            'ijmuiden' => 'IJmuiden',
                                        ];
                                    @endphp
                                    {{ $locations[$reservation->location] ?? $reservation->location }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Instructeur</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->instructor->user->name }}</dd>
                            </div>
                            @if($reservation->duo_name)
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Duo Deelnemer</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $reservation->duo_name }}
                                    @if($reservation->duo_email || $reservation->duo_phone)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($reservation->duo_email)
                                        <div>Email: {{ $reservation->duo_email }}</div>
                                        @endif
                                        @if($reservation->duo_phone)
                                        <div>Telefoon: {{ $reservation->duo_phone }}</div>
                                        @endif
                                    </div>
                                    @endif
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Betaalgegevens</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Details van je betaling.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Totaalbedrag</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">€{{ number_format($reservation->package->price, 2, ',', '.') }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Betaalstatus</dt>
                                <dd class="mt-1 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $reservation->is_paid ? 'green' : 'yellow' }}-100 text-{{ $reservation->is_paid ? 'green' : 'yellow' }}-800">
                                        {{ $reservation->is_paid ? 'Betaald' : 'Niet betaald' }}
                                    </span>
                                </dd>
                            </div>
                            
                            @if(!$reservation->is_paid)
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Betaalinstructies</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <p>Maak het bedrag over naar het volgende rekeningnummer:</p>
                                    <p class="mt-2 font-medium">NL12 INGB 0123 4567 89</p>
                                    <p class="mt-1">t.n.v. Windkracht 12 Kitesurfschool</p>
                                    <p class="mt-1">Onder vermelding van: Reservering #{{ $reservation->id }}</p>
                                    <div class="mt-4">
                                        <a href="{{ route('student.reservations.payment.form', $reservation->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            Ik heb betaald
                                        </a>
                                    </div>
                                </dd>
                            </div>
                            @else
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Betaaldatum</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->payment_date ? $reservation->payment_date->format('d-m-Y') : 'Onbekend' }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    @if($reservation->status !== 'cancelled' && $reservation->start_date > now())
                    <a href="{{ route('student.reservations.cancel.form', $reservation->id) }}" class="inline-flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                        <i class="fas fa-times-circle mr-2"></i>
                        Annuleren
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Reservering Annuleren</h1>
                    <a href="{{ route('student.reservations.show', $reservation->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar reservering
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-400 mb-6">
                    <p class="text-red-800">
                        <strong>Let op:</strong> Je staat op het punt om je reservering te annuleren. Dit kan niet ongedaan worden gemaakt.
                    </p>
                </div>
                
                <!-- Reservation Info -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold text-blue-800">Reserveringsgegevens</h2>
                    <div class="mt-2">
                        <p><span class="font-medium">Pakket:</span> {{ $reservation->package->name }}</p>
                        <p><span class="font-medium">Datum:</span> {{ $reservation->start_date->format('d-m-Y') }}</p>
                        <p><span class="font-medium">Tijd:</span> {{ $reservation->start_date->format('H:i') }} - {{ $reservation->end_date->format('H:i') }}</p>
                        <p><span class="font-medium">Locatie:</span> {{ ucfirst($reservation->location) }}</p>
                        <p><span class="font-medium">Instructeur:</span> {{ $reservation->instructor->user->name }}</p>
                        @if($reservation->is_paid)
                        <p class="mt-2 font-medium text-red-600">Let op: Deze reservering is al betaald. Neem contact op voor informatie over terugbetalingen.</p>
                        @endif
                    </div>
                </div>
                
                <form method="POST" action="{{ route('student.reservations.cancel', $reservation->id) }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">Reden voor annulering</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>{{ old('cancellation_reason') }}</textarea>
                        @error('cancellation_reason')
                            <p class="text-red-500 text-sm mt-1">Geef een reden op voor de annulering.</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-600">Geef a.u.b. een duidelijke reden op voor de annulering van je les.</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-medium text-gray-800 mb-2">Annuleringsvoorwaarden</h3>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>Annuleringen tot 48 uur voor aanvang: 100% restitutie</li>
                            <li>Annuleringen tot 24 uur voor aanvang: 50% restitutie</li>
                            <li>Annuleringen binnen 24 uur voor aanvang: geen restitutie</li>
                            <li>Bij slecht weer of andere omstandigheden buiten jouw controle, kun je kosteloos een nieuwe datum kiezen</li>
                        </ul>
                    </div>
                    
                    <div class="flex items-center mt-6">
                        <input type="checkbox" id="confirm-cancel" name="confirm_cancel" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                        <label for="confirm-cancel" class="ml-2 block text-sm text-gray-900">
                            Ik begrijp de annuleringsvoorwaarden en wil deze reservering annuleren
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('student.reservations.show', $reservation->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md mr-4">
                            Annuleren
                        </a>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                            <i class="fas fa-times-circle mr-2"></i>Reservering Annuleren
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

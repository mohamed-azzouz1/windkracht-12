@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Betaling Registreren</h1>
                    <a href="{{ route('student.reservations.show', $registration->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Registration Info -->
                <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-blue-800 mb-2">Reserveringsgegevens</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Pakket:</span> {{ $registration->package->name }}</p>
                            <p><span class="font-medium">Datum:</span> {{ $registration->start_date->format('d-m-Y') }}</p>
                            <p><span class="font-medium">Tijd:</span> {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Locatie:</span> {{ ucfirst($registration->location) }}</p>
                            <p><span class="font-medium">Instructeur:</span> {{ $registration->instructor->user->name }}</p>
                            <p><span class="font-medium">Referentie:</span> {{ $registration->reservation_ref }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Invoice Info -->
                @if(isset($invoice))
                <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Factuurgegevens</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><span class="font-medium">Factuurnummer:</span> {{ $invoice->invoice_number }}</p>
                            <p><span class="font-medium">Datum:</span> {{ $invoice->created_at->format('d-m-Y') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Bedrag:</span> € {{ number_format($invoice->amount, 2, ',', '.') }}</p>
                            <p><span class="font-medium">Status:</span> {{ $invoice->status === 'paid' ? 'Betaald' : 'Niet betaald' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-800 mb-2">Betalingsgegevens</h4>
                        <p><span class="font-medium">IBAN:</span> NL12 RABO 0123 4567 89</p>
                        <p><span class="font-medium">T.n.v.:</span> Windkracht 12 B.V.</p>
                        <p><span class="font-medium">Onder vermelding van:</span> {{ $invoice->invoice_number }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Payment Form -->
                <form method="POST" action="{{ route('student.reservations.payment', $registration->id) }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Betaalmethode</label>
                        <select id="payment_method" name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Selecteer betaalmethode</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bankoverschrijving</option>
                            <option value="ideal" {{ old('payment_method') == 'ideal' ? 'selected' : '' }}>iDEAL</option>
                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Creditcard</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Anders</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Betalingsreferentie (optioneel)</label>
                        <input type="text" id="payment_reference" name="payment_reference" value="{{ old('payment_reference') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Bijv. transactienummer">
                        <p class="mt-1 text-sm text-gray-500">Vul hier de referentie van je betaling in, indien van toepassing.</p>
                        @error('payment_reference')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Datum van betaling</label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        @error('payment_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                        <p class="text-yellow-800">
                            <strong>Let op:</strong> Door op "Betaling bevestigen" te klikken, bevestig je dat je de betaling hebt uitgevoerd. Een beheerder zal de betaling verifiëren.
                        </p>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="payment_confirmation" name="payment_confirmation" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                            <label for="payment_confirmation" class="ml-2 block text-sm text-gray-900">
                                Ik bevestig dat ik het volledige bedrag van € {{ isset($invoice) ? number_format($invoice->amount, 2, ',', '.') : number_format($registration->package->price, 2, ',', '.') }} heb overgemaakt.
                            </label>
                        </div>
                        @error('payment_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-end">
                        <a href="{{ route('student.reservations.show', $registration->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md mr-4">
                            Annuleren
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                            Betaling Bevestigen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

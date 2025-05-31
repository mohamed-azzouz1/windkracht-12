@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Betaling melden</h1>
                    <a href="{{ route('student.reservations.show', $reservation->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar reservering
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Payment Info -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold text-blue-800">Reserveringsgegevens</h2>
                    <div class="mt-2">
                        <p><span class="font-medium">Pakket:</span> {{ $reservation->package->name }}</p>
                        <p><span class="font-medium">Datum:</span> {{ $reservation->start_date->format('d-m-Y') }}</p>
                        <p><span class="font-medium">Tijd:</span> {{ $reservation->start_date->format('H:i') }} - {{ $reservation->end_date->format('H:i') }}</p>
                        <p><span class="font-medium">Te betalen bedrag:</span> €{{ number_format($reservation->package->price, 2, ',', '.') }}</p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400 mb-6">
                    <p class="text-yellow-800">
                        <strong>Let op:</strong> Meld hier alleen betalingen die je daadwerkelijk hebt gedaan. De betaling zal door onze administratie worden gecontroleerd.
                    </p>
                </div>
                
                <form method="POST" action="{{ route('student.reservations.payment', $reservation->id) }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payment Date -->
                        <div class="col-span-1">
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Datum van betaling</label>
                            <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('payment_date')
                                <p class="text-red-500 text-sm mt-1">Vul een geldige datum in.</p>
                            @enderror
                        </div>
                        
                        <!-- Payment Reference -->
                        <div class="col-span-1">
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Betalingskenmerk (optioneel)</label>
                            <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference') }}" 
                                   placeholder="Bijv. transactienummer" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('payment_reference')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Bank Info -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-800 mb-2">Bankgegevens voor je betaling</h3>
                        <p><span class="font-medium">IBAN:</span> NL12 INGB 0123 4567 89</p>
                        <p><span class="font-medium">T.n.v.:</span> Windkracht 12 Kitesurfschool</p>
                        <p><span class="font-medium">Onder vermelding van:</span> Reservering #{{ $reservation->id }}</p>
                    </div>
                    
                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                            <i class="fas fa-check-circle mr-2"></i>Ik heb betaald
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

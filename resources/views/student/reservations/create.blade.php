@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Reservering maken</h1>
                    <a href="{{ route('student.reservations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar pakketten
                    </a>
                </div>
                
                <!-- Package Info -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                    <h2 class="text-lg font-semibold text-blue-800 mb-2">Geselecteerd Pakket</h2>
                    <div class="flex flex-col md:flex-row md:justify-between">
                        <div>
                            <p class="text-blue-900 font-bold text-xl">{{ $package->name }}</p>
                            <p class="text-blue-700">{{ $package->description }}</p>
                            <p class="text-blue-600 mt-2">
                                <span class="font-medium">Duur:</span> {{ $package->duration_hours }} {{ $package->duration_hours > 1 ? 'uren' : 'uur' }}
                            </p>
                            @if($package->is_duo)
                                <p class="text-blue-600">
                                    <span class="font-medium">Type:</span> Duo les (2 personen)
                                </p>
                            @endif
                        </div>
                        <div class="mt-4 md:mt-0 text-center md:text-right">
                            <p class="text-lg font-bold text-blue-800">€{{ number_format($package->price, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('student.reservations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Date and Time Selection -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Datum en Tijd</h3>
                            
                            <div class="mb-4">
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Selecteer Datum</label>
                                <select id="date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($availableDates as $availableDate)
                                        <option value="{{ $availableDate['date'] }}">
                                            {{ $availableDate['day_name'] }} {{ $availableDate['display_date'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Selecteer Tijd</label>
                                <select id="time" name="time" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($availableDates[0]['times'] as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                                @error('time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Selecteer Locatie</label>
                                <select id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($locations as $value => $name)
                                        <option value="{{ $value }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Duo Information (if applicable) -->
                        @if($package->is_duo)
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Gegevens Duo Partner</h3>
                            
                            <div class="mb-4">
                                <label for="duo_name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                <input type="text" id="duo_name" name="duo_name" value="{{ old('duo_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                <input type="email" id="duo_email" name="duo_email" value="{{ old('duo_email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                <input type="text" id="duo_phone" name="duo_phone" value="{{ old('duo_phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Notes & Disclaimers -->
                    <div class="bg-yellow-50 p-4 rounded-lg mb-6 border border-yellow-100">
                        <h3 class="text-md font-medium text-yellow-800 mb-2">Belangrijk om te weten</h3>
                        <ul class="list-disc pl-5 text-sm text-yellow-700">
                            <li>De kitesurfles is alleen bij geschikte weersomstandigheden.</li>
                            <li>De les kan tot 24 uur van tevoren worden geannuleerd in geval van ongunstige wind of andere weersomstandigheden.</li>
                            <li>Kom ten minste 15 minuten voor aanvang van de les.</li>
                            <li>Je moet kunnen zwemmen om deel te nemen aan deze les.</li>
                            @if($package->is_duo)
                                <li>Het duopakket is voor 2 personen. Je betaalt als hoofdboeker het volledige bedrag.</li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                            Reservering Bevestigen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateSelect = document.getElementById('date');
        const timeSelect = document.getElementById('time');
        
        // Store available times for each date
        const availableTimes = {
            @foreach($availableDates as $availableDate)
                '{{ $availableDate['date'] }}': @json($availableDate['times']),
            @endforeach
        };
        
        // Update available times when date changes
        dateSelect.addEventListener('change', function() {
            const selectedDate = this.value;
            const times = availableTimes[selectedDate] || [];
            
            // Clear existing options
            timeSelect.innerHTML = '';
            
            // Add new time options
            times.forEach(time => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = time;
                timeSelect.appendChild(option);
            });
        });
    });
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Les Reserveren</h1>
                    <a href="{{ route('student.reservations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar pakketten
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Package Info -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold text-blue-800">{{ $package->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ $package->description }}</p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <span><strong>Duur:</strong> {{ $package->duration_hours }} uur</span>
                        <span><strong>Sessies:</strong> {{ $package->number_of_sessions }}</span>
                        <span><strong>Prijs:</strong> €{{ number_format($package->price, 2, ',', '.') }}</span>
                        <span><strong>Max deelnemers:</strong> {{ $package->max_participants }}</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('student.reservations.store') }}">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">1. Kies een datum en tijd</h3>
                            
                            <!-- Date Selection -->
                            <div class="mb-4">
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                                <select name="date" id="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Selecteer een datum</option>
                                    @foreach($availableDates as $date)
                                        <option value="{{ $date['date'] }}">
                                            {{ $date['readable_date'] }} ({{ $date['day_name'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Time Selection -->
                            <div class="mb-4">
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Tijd</label>
                                <select name="time" id="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Selecteer eerst een datum</option>
                                </select>
                                @error('time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Location Selection -->
                            <div class="mb-6">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Locatie</label>
                                <select name="location" id="location" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Selecteer een locatie</option>
                                    @foreach($locations as $key => $location)
                                        <option value="{{ $key }}">{{ $location }}</option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">2. Deelnemersinformatie</h3>
                            
                            @if($package->max_participants > 1)
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="has_duo" id="has_duo" class="mr-2" value="1" {{ old('has_duo') ? 'checked' : '' }}>
                                    <label for="has_duo" class="text-sm font-medium text-gray-700">Ik wil een duo-les met een andere deelnemer</label>
                                </div>
                            </div>
                            
                            <div id="duo-info" class="border-l-2 border-blue-200 pl-4 mb-6 {{ old('has_duo') ? '' : 'hidden' }}">
                                <div class="mb-4">
                                    <label for="duo_name" class="block text-sm font-medium text-gray-700 mb-1">Naam tweede deelnemer</label>
                                    <input type="text" name="duo_name" id="duo_name" value="{{ old('duo_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('duo_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="duo_email" class="block text-sm font-medium text-gray-700 mb-1">Email tweede deelnemer</label>
                                    <input type="email" name="duo_email" id="duo_email" value="{{ old('duo_email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('duo_email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="duo_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoon tweede deelnemer</label>
                                    <input type="text" name="duo_phone" id="duo_phone" value="{{ old('duo_phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('duo_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            
                            <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <strong>Let op:</strong> Je reservering is pas definitief na betaling. Na het reserveren ontvang je betalingsinstructies per e-mail.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            Reserveren
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Dynamic time slots based on selected date
    const availableDates = @json($availableDates);
    const dateSelect = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    
    dateSelect.addEventListener('change', function() {
        const selectedDate = this.value;
        timeSelect.innerHTML = '<option value="">Selecteer een tijd</option>';
        
        if(selectedDate) {
            const dateInfo = availableDates.find(d => d.date === selectedDate);
            if(dateInfo && dateInfo.available_times) {
                dateInfo.available_times.forEach(time => {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                });
            }
        }
    });
    
    // Toggle duo participant info
    const hasDuoCheckbox = document.getElementById('has_duo');
    const duoInfoSection = document.getElementById('duo-info');
    
    if(hasDuoCheckbox && duoInfoSection) {
        hasDuoCheckbox.addEventListener('change', function() {
            duoInfoSection.classList.toggle('hidden', !this.checked);
            
            const duoInputs = duoInfoSection.querySelectorAll('input');
            duoInputs.forEach(input => {
                input.required = this.checked;
            });
        });
    }
</script>
@endpush
@endsection

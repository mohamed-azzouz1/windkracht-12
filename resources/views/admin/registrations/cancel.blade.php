@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Les Annuleren</h1>
                    <a href="{{ route('admin.registrations.show', $registration->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i> Terug naar les
                    </a>
                </div>
                
                <!-- Les informatie -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Les Informatie</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Student</p>
                            <p class="font-medium">{{ $registration->student->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $registration->student->user->email }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Instructeur</p>
                            <p class="font-medium">{{ $registration->instructor->user->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Datum & Tijd</p>
                            <p class="font-medium">{{ $registration->start_date->format('d-m-Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Pakket & Locatie</p>
                            <p class="font-medium">{{ $registration->package->name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($registration->location) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Annuleringsopties -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Snelle Annulering</h2>
                    <p class="text-gray-600 mb-4">Gebruik een van deze opties om de les te annuleren met een standaardmail naar de klant.</p>
                    
                    <div class="flex flex-col md:flex-row gap-4">
                        <form action="{{ route('admin.registrations.cancel.illness', $registration->id) }}" method="POST" class="w-full md:w-1/2">
                            @csrf
                            <button type="submit" style="background-color: #F59E0B; border-color: #D97706;" class="w-full text-white font-medium py-3 px-4 rounded border hover:bg-yellow-600">
                                <i class="fas fa-user-injured mr-2"></i> Annuleren wegens ziekte instructeur
                            </button>
                            <p class="text-sm text-gray-500 mt-1">Stuurt een standaardmail naar de klant over ziekte van de instructeur.</p>
                        </form>
                        
                        <form action="{{ route('admin.registrations.cancel.weather', $registration->id) }}" method="POST" class="w-full md:w-1/2">
                            @csrf
                            <button type="submit" style="background-color: #3B82F6; border-color: #2563EB;" class="w-full text-white font-medium py-3 px-4 rounded border hover:bg-blue-600">
                                <i class="fas fa-wind mr-2"></i> Annuleren wegens slechte weersomstandigheden
                            </button>
                            <p class="text-sm text-gray-500 mt-1">Stuurt een standaardmail naar de klant over slechte weersomstandigheden (windkracht > 10).</p>
                        </form>
                    </div>
                </div>
                
                <!-- Aangepaste annulering -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Aangepaste Annulering</h2>
                    
                    <form action="{{ route('admin.registrations.cancel', $registration->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">Reden voor annulering</label>
                            <textarea name="cancellation_reason" id="cancellation_reason" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('cancellation_reason') }}</textarea>
                            @error('cancellation_reason')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email', true) ? 'checked' : '' }} 
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="send_email" class="ml-2 block text-sm text-gray-700">E-mail naar klant versturen</label>
                            </div>
                        </div>
                        
                        <button type="submit" style="background-color: #DC2626;" class="hover:bg-red-700 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-times-circle mr-1"></i> Les Annuleren
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

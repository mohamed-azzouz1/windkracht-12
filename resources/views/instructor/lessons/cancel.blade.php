@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Les Annuleren</h1>
                <p class="text-gray-500 mb-6">Voer een reden in voor het annuleren van deze les.</p>
                
                <!-- Lesson Details Card -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg text-blue-900">{{ $lesson->package->name }}</h3>
                            <p class="text-blue-800 mt-1">
                                <span class="font-medium">Datum:</span> {{ $lesson->start_date->format('d-m-Y') }}
                            </p>
                            <p class="text-blue-800">
                                <span class="font-medium">Tijd:</span> {{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}
                            </p>
                            <p class="text-blue-800">
                                <span class="font-medium">Student:</span> {{ $lesson->student->user->name }}
                                @if($lesson->duo_name)
                                    <span class="text-blue-500">+</span> {{ $lesson->duo_name }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $lesson->status === 'confirmed' ? 'green' : 'yellow' }}-100 text-{{ $lesson->status === 'confirmed' ? 'green' : 'yellow' }}-800">
                                {{ ucfirst($lesson->status) }}
                            </span>
                            <p class="text-sm text-blue-800 mt-1">
                                <span class="font-medium">Betaald:</span> {{ $lesson->is_paid ? 'Ja' : 'Nee' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Cancel Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <form action="{{ route('instructor.lessons.cancel.weather', $lesson->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cloud-rain mr-2"></i>
                            Annuleren wegens slecht weer
                        </button>
                    </form>
                    
                    <form action="{{ route('instructor.lessons.cancel.sick', $lesson->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center">
                            <i class="fas fa-thermometer-half mr-2"></i>
                            Annuleren wegens ziekte
                        </button>
                    </form>
                </div>
                
                <!-- Custom Cancel Form -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Annuleren met eigen reden</h3>
                    
                    <form action="{{ route('instructor.lessons.cancel', $lesson->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">Annuleringsreden</label>
                            <textarea id="cancellation_reason" name="cancellation_reason" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Voer een gedetailleerde reden in voor de annulering...">{{ old('cancellation_reason') }}</textarea>
                            @error('cancellation_reason')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('instructor.lessons.show', $lesson->id) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                                Annuleren
                            </a>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg">
                                Les Annuleren
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Warning -->
                <div class="mt-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Let op!</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Annuleren van lessen kort voor aanvang kan leiden tot ontevreden klanten. De student zal per e-mail worden geïnformeerd over deze annulering.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

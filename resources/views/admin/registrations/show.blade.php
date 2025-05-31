@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header with back button -->
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Les Details</h1>
                    <a href="{{ route('admin.registrations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i> Terug naar lessen
                    </a>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                <!-- Status Banner -->
                <div class="mb-4 p-3 rounded-lg flex justify-between items-center
                    @if($registration->status === 'confirmed') bg-green-100
                    @elseif($registration->status === 'pending') bg-yellow-100
                    @elseif($registration->status === 'cancelled') bg-red-100
                    @else bg-blue-100 @endif">
                    <div>
                        <span class="font-semibold text-lg
                            @if($registration->status === 'confirmed') text-green-800
                            @elseif($registration->status === 'pending') text-yellow-800
                            @elseif($registration->status === 'cancelled') text-red-800
                            @else text-blue-800 @endif">
                            Status: {{ ucfirst($registration->status) }}
                        </span>
                        @if($registration->status === 'cancelled' && $registration->cancellation_reason)
                            <p class="mt-1 text-sm text-gray-700">Reden: {{ $registration->cancellation_reason }}</p>
                        @endif
                    </div
                    
                    <!-- Quick Actions -->
                    <div class="flex space-x-2">
                        @if($registration->status !== 'confirmed' && $registration->status !== 'cancelled')
                            <form action="{{ route('admin.registrations.mark-as-confirmed', $registration->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-sm">
                                    <i class="fas fa-check mr-1"></i> Bevestigen
                                </button>
                            </form>
                        @endif
                        
                        @if(!$registration->is_paid)
                            <form action="{{ route('admin.registrations.mark-as-paid', $registration->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm">
                                    <i class="fas fa-money-bill mr-1"></i> Als betaald markeren
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                
                <!-- Lesson Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Left Column -->
                    <div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Les Informatie</h2>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Pakket</p>
                                    <p class="font-medium">{{ $registration->package->name }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Prijs</p>
                                    <p class="font-medium">€{{ number_format($registration->package->price, 2, ',', '.') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Datum</p>
                                    <p class="font-medium">{{ $registration->start_date->format('d-m-Y') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Tijd</p>
                                    <p class="font-medium">{{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Locatie</p>
                                    <p class="font-medium">{{ ucfirst($registration->location) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Betaalstatus</p>
                                    <p class="font-medium">
                                        @if($registration->is_paid)
                                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Betaald</span>
                                            @if($registration->payment_date)
                                                <span class="block text-xs text-gray-500">op {{ $registration->payment_date->format('d-m-Y') }}</span>
                                            @endif
                                        @else
                                            <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> Niet betaald</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($registration->notes)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-500">Notities</p>
                                    <p class="mt-1">{{ $registration->notes }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Instructeur</h2>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">{{ substr($registration->instructor->user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium">{{ $registration->instructor->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $registration->instructor->user->email }}</p>
                                    @if($registration->instructor->phone)
                                        <p class="text-sm text-gray-500">{{ $registration->instructor->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Student Informatie</h2>
                            
                            <div class="flex items-start mb-4">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-medium">{{ substr($registration->student->user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium">{{ $registration->student->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $registration->student->user->email }}</p>
                                    @if($registration->student->phone)
                                        <p class="text-sm text-gray-500">{{ $registration->student->phone }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                @if($registration->student->address || $registration->student->city)
                                    <div>
                                        <p class="text-sm text-gray-500">Adres</p>
                                        <p class="font-medium">
                                            {{ $registration->student->address ?: 'Niet ingevuld' }}
                                            @if($registration->student->city)
                                                <span class="block">{{ $registration->student->city }}</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                
                                @if($registration->student->date_of_birth)
                                    <div>
                                        <p class="text-sm text-gray-500">Geboortedatum</p>
                                        <p class="font-medium">{{ $registration->student->date_of_birth->format('d-m-Y') }}</p>
                                    </div>
                                @endif
                                
                                <div>
                                    <p class="text-sm text-gray-500">Niveau</p>
                                    <p class="font-medium">{{ ucfirst($registration->student->skill_level ?: 'Niet ingevuld') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($registration->duo_name || $registration->duo_email || $registration->duo_phone)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Duo Partner</h2>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Naam</p>
                                    <p class="font-medium">{{ $registration->duo_name }}</p>
                                </div>
                                
                                @if($registration->duo_email)
                                <div>
                                    <p class="text-sm text-gray-500">E-mail</p>
                                    <p class="font-medium">{{ $registration->duo_email }}</p>
                                </div>
                                @endif
                                
                                @if($registration->duo_phone)
                                <div>
                                    <p class="text-sm text-gray-500">Telefoon</p>
                                    <p class="font-medium">{{ $registration->duo_phone }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons - Now only at the bottom -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center bg-gray-100 p-3 rounded-lg">
                        <!-- Destructive actions on the left -->
                        <div>
                            <form action="{{ route('admin.registrations.destroy', $registration->id) }}" method="POST" 
                                  onsubmit="return confirm('Weet je zeker dat je deze les wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                                    <i class="fas fa-trash mr-1"></i> Verwijderen
                                </button>
                            </form>
                        </div>
                        
                        <!-- Other actions on the right -->
                        <div class="flex flex-wrap gap-2 justify-end">
                            <a href="{{ route('admin.registrations.edit', $registration->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded text-center">
                                <i class="fas fa-edit mr-1"></i> Bewerken
                            </a>
                            
                            <a href="{{ route('admin.instructors.show', $registration->instructor_id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded text-center">
                                <i class="fas fa-user-clock mr-1"></i> Bekijk instructeur
                            </a>
                            
                            @if($registration->status !== 'cancelled')
                                <a href="{{ route('admin.registrations.cancel.form', $registration->id) }}" style="background-color: #8B5CF6; color: white;" class="hover:bg-purple-700 py-2 px-4 rounded text-center">
                                    <i class="fas fa-ban mr-1"></i> Annuleren
                                </a>
                            @endif
                            
                            <button id="emailBtn" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                                <i class="fas fa-envelope mr-1"></i> Bevestiging e-mailen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('emailBtn').addEventListener('click', function() {
    alert('E-mail versturen functie nog te implementeren');
});
</script>
@endsection

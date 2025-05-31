@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Instructeur Bewerken</h1>
                    <a href="{{ route('admin.instructors.show', $instructor->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i> Terug naar details
                    </a>
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
                
                <form action="{{ route('admin.instructors.update', $instructor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Account Information -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Account Gegevens</h2>
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $instructor->user->name) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $instructor->user->email) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $instructor->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Actieve instructeur</label>
                                </div>
                                @error('is_active')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Personal Information -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Persoonlijke Informatie</h2>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $instructor->phone) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Geboortedatum</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" 
                                       value="{{ old('date_of_birth', $instructor->date_of_birth ? $instructor->date_of_birth->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('date_of_birth')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $instructor->address) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Woonplaats</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $instructor->city) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="bsn" class="block text-sm font-medium text-gray-700 mb-1">BSN</label>
                                <input type="text" name="bsn" id="bsn" value="{{ old('bsn', $instructor->bsn) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('bsn')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Professional Information -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Professionele Informatie</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="certification" class="block text-sm font-medium text-gray-700 mb-1">Certificering</label>
                                <input type="text" name="certification" id="certification" value="{{ old('certification', $instructor->certification) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Bijv. IKO Level 3, VDWS, etc.</p>
                                @error('certification')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="years_of_experience" class="block text-sm font-medium text-gray-700 mb-1">Jaren ervaring</label>
                                <input type="number" name="years_of_experience" id="years_of_experience" 
                                       value="{{ old('years_of_experience', $instructor->years_of_experience) }}" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('years_of_experience')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialisatie</label>
                                <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $instructor->specialization) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('specialization')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="biography" class="block text-sm font-medium text-gray-700 mb-1">Biografie</label>
                            <textarea id="biography" name="biography" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('biography', $instructor->biography) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Beschrijf achtergrond, ervaring en stijl van lesgeven.</p>
                            @error('biography')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Password Reset Section -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Wachtwoord resetten (optioneel)</h2>
                        
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nieuw wachtwoord</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Laat leeg om het wachtwoord ongewijzigd te laten.</p>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Bevestig wachtwoord</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                            <i class="fas fa-save mr-1"></i> Wijzigingen opslaan
                        </button>
                    </div>
                </form>
                
                <!-- Delete Form - Separated from the main form -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h2 class="text-lg font-semibold text-red-600 mb-4">Gevaarlijke acties</h2>
                    <p class="text-gray-700 mb-4">Deze actie kan niet ongedaan worden gemaakt. Dit zal alle gegevens van deze instructeur permanent verwijderen.</p>
                    
                    <form action="{{ route('admin.instructors.destroy', $instructor->id) }}" method="POST" 
                          onsubmit="return confirm('Weet je zeker dat je deze instructeur wilt verwijderen? Alle bijbehorende gegevens worden permanent verwijderd.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                            <i class="fas fa-trash mr-1"></i> Instructeur verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

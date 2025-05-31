
@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Klant bewerken</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.students.show', $student->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i> Terug naar klantgegevens
                        </a>
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
                
                <!-- Edit Form -->
                <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Basic Information -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Basisinformatie</h2>
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $student->user->name) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $student->user->email) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Geboortedatum</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('date_of_birth')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="skill_level" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                                <select name="skill_level" id="skill_level" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="beginner" {{ (old('skill_level', $student->skill_level) == 'beginner') ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ (old('skill_level', $student->skill_level) == 'intermediate') ? 'selected' : '' }}>Gevorderd</option>
                                    <option value="advanced" {{ (old('skill_level', $student->skill_level) == 'advanced') ? 'selected' : '' }}>Vergevorderd</option>
                                </select>
                                @error('skill_level')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Address Information -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Adresgegevens</h2>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $student->address) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Woonplaats</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $student->city) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notities</label>
                                <textarea name="notes" id="notes" rows="5" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $student->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                            <i class="fas fa-save mr-1"></i> Wijzigingen opslaan
                        </button>
                    </div>
                </form>
                
                <!-- Delete Form - Separated from the edit form -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h2 class="text-lg font-semibold text-red-600 mb-4">Gevaarlijke acties</h2>
                    <p class="text-gray-700 mb-4">Deze actie kan niet ongedaan worden gemaakt. Dit zal alle gegevens van deze klant permanent verwijderen.</p>
                    
                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" 
                          onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen? Alle bijbehorende gegevens worden permanent verwijderd.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                            <i class="fas fa-trash mr-1"></i> Klant verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

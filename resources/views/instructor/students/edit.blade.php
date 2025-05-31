@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Student Bewerken</h1>
                    <a href="{{ route('instructor.students.show', $student->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar details
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <form action="{{ route('instructor.students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Persoonlijke Informatie</h3>
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $student->user->name) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="email_display" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres (niet bewerkbaar)</label>
                                <input type="text" id="email_display" value="{{ $student->user->email }}" disabled
                                       class="w-full px-3 py-2 border border-gray-300 bg-gray-50 rounded-md shadow-sm">
                            </div>
                            
                            <div class="mb-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Geboortedatum</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('date_of_birth')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Contactgegevens</h3>
                            
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
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kitesurfing Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Kitesurfvaardigheden</h3>
                        
                        <div class="mb-4">
                            <label for="skill_level" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                            <select id="skill_level" name="skill_level" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="beginner" {{ old('skill_level', $student->skill_level) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('skill_level', $student->skill_level) === 'intermediate' ? 'selected' : '' }}>Gevorderd</option>
                                <option value="advanced" {{ old('skill_level', $student->skill_level) === 'advanced' ? 'selected' : '' }}>Vergevorderd</option>
                            </select>
                            @error('skill_level')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notities</label>
                            <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $student->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <form action="{{ route('instructor.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze student wilt verwijderen? Alle geplande lessen worden geannuleerd.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg">
                                Student Verwijderen
                            </button>
                        </form>
                        
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                            Wijzigingen Opslaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

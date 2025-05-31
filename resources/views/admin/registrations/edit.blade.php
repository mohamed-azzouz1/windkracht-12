@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Les Bewerken</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.registrations.show', $registration->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i> Terug naar les
                        </a>
                    </div>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <form action="{{ route('admin.registrations.update', $registration->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Student & Instructor Selection -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Personen</h2>
                            
                            <div class="mb-4">
                                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                                <select name="student_id" id="student_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecteer een student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $registration->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->user->name }} ({{ $student->user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-1">Instructeur</label>
                                <select name="instructor_id" id="instructor_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecteer een instructeur</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $registration->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Lesson Details -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Lesgegevens</h2>
                            
                            <div class="mb-4">
                                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Pakket</label>
                                <select name="package_id" id="package_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecteer een pakket</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}" {{ old('package_id', $registration->package_id) == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }} - €{{ number_format($package->price, 2, ',', '.') }} ({{ $package->duration_hours }} uur)
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Locatie</label>
                                <select name="location" id="location" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($locations as $value => $label)
                                        <option value="{{ $value }}" {{ old('location', $registration->location) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                                    <input type="date" name="date" id="date" required
                                           value="{{ old('date', $registration->start_date->format('Y-m-d')) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Starttijd</label>
                                    <select name="time" id="time" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @foreach(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                                            <option value="{{ $time }}" {{ old('time', $registration->start_date->format('H:i')) == $time ? 'selected' : '' }}>{{ $time }}</option>
                                        @endforeach
                                    </select>
                                    @error('time')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aanvullende informatie</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="status" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" {{ old('status', $registration->status) == 'pending' ? 'selected' : '' }}>In afwachting</option>
                                        <option value="confirmed" {{ old('status', $registration->status) == 'confirmed' ? 'selected' : '' }}>Bevestigd</option>
                                        <option value="cancelled" {{ old('status', $registration->status) == 'cancelled' ? 'selected' : '' }}>Geannuleerd</option>
                                        <option value="completed" {{ old('status', $registration->status) == 'completed' ? 'selected' : '' }}>Voltooid</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_paid" id="is_paid" value="1" {{ old('is_paid', $registration->is_paid) ? 'checked' : '' }} 
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="is_paid" class="ml-2 block text-sm text-gray-700">Als betaald markeren</label>
                                    </div>
                                    @error('is_paid')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                @if($registration->status === 'cancelled')
                                <div class="mb-4">
                                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">Annuleringsreden</label>
                                    <input type="text" name="cancellation_reason" id="cancellation_reason" 
                                           value="{{ old('cancellation_reason', $registration->cancellation_reason) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('cancellation_reason')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notities</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $registration->notes) }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Duo Lesson Information -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Duo-les Informatie (optioneel)</h2>
                        <p class="text-gray-600 mb-4">Vul deze velden alleen in als dit een duo-les betreft.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="mb-4">
                                <label for="duo_name" class="block text-sm font-medium text-gray-700 mb-1">Naam partner</label>
                                <input type="text" name="duo_name" id="duo_name" value="{{ old('duo_name', $registration->duo_name) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_email" class="block text-sm font-medium text-gray-700 mb-1">E-mail partner</label>
                                <input type="email" name="duo_email" id="duo_email" value="{{ old('duo_email', $registration->duo_email) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoon partner</label>
                                <input type="text" name="duo_phone" id="duo_phone" value="{{ old('duo_phone', $registration->duo_phone) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <div>
                            <!-- Delete button -->
                            <a href="#" onclick="event.preventDefault(); if(confirm('Weet je zeker dat je deze les wilt verwijderen?')) document.getElementById('delete-form').submit();" 
                               class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded inline-flex items-center">
                                <i class="fas fa-trash mr-1"></i> Verwijderen
                            </a>
                            <form id="delete-form" action="{{ route('admin.registrations.destroy', $registration->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                        
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-save mr-1"></i> Wijzigingen opslaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

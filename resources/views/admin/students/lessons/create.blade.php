@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Les inplannen voor {{ $student->user->name }}</h1>
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
                
                <!-- Student Information Summary -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                    <div class="flex flex-col md:flex-row justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-blue-800">Klantgegevens</h2>
                            <p class="text-blue-700">{{ $student->user->email }}</p>
                            @if($student->phone)
                                <p class="text-blue-700">Tel: {{ $student->phone }}</p>
                            @endif
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Niveau: {{ ucfirst($student->skill_level ?? 'Beginner') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Lesson Creation Form -->
                <form action="{{ route('admin.students.lessons.store', $student->id) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Lesson Details -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Lesgegevens</h2>
                            
                            <div class="mb-4">
                                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Pakket</label>
                                <select name="package_id" id="package_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }} - €{{ number_format($package->price, 2, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-1">Instructeur</label>
                                <select name="instructor_id" id="instructor_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                                <input type="date" name="date" id="date" value="{{ old('date') ?? date('Y-m-d') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Tijd</label>
                                <select name="time" id="time" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'] as $time)
                                        <option value="{{ $time }}" {{ old('time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endforeach
                                </select>
                                @error('time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Additional Details -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aanvullende informatie</h2>
                            
                            <div class="mb-4">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Locatie</label>
                                <select name="location" id="location" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($locations as $value => $label)
                                        <option value="{{ $value }}" {{ old('location') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>In afwachting</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Bevestigd</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_paid" id="is_paid" value="1" {{ old('is_paid') ? 'checked' : '' }} 
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_paid" class="ml-2 block text-sm text-gray-700">Als betaald markeren</label>
                                </div>
                                @error('is_paid')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notities</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Duo-gegevens (optioneel)</h2>
                        <p class="text-gray-600 mb-4">Alleen invullen als dit een duo-les betreft.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="mb-4">
                                <label for="duo_name" class="block text-sm font-medium text-gray-700 mb-1">Naam partner</label>
                                <input type="text" name="duo_name" id="duo_name" value="{{ old('duo_name') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_email" class="block text-sm font-medium text-gray-700 mb-1">E-mail partner</label>
                                <input type="email" name="duo_email" id="duo_email" value="{{ old('duo_email') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoon partner</label>
                                <input type="text" name="duo_phone" id="duo_phone" value="{{ old('duo_phone') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-plus mr-1"></i> Les inplannen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Les Inplannen voor {{ $student->user->name }}</h1>
                    <a href="{{ route('instructor.students.show', $student->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar student
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Student Info -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                    <div class="flex flex-col md:flex-row justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-blue-800">Student: {{ $student->user->name }}</h2>
                            <p class="text-blue-600">{{ $student->user->email }}</p>
                            @if($student->phone)
                                <p class="text-blue-600">Tel: {{ $student->phone }}</p>
                            @endif
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Niveau: {{ ucfirst($student->skill_level ?? 'Beginner') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('instructor.students.lessons.store', $student->id) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Lesson Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Les Details</h3>
                            
                            <div class="mb-4">
                                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Pakket</label>
                                <select id="package_id" name="package_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
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
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Tijd</label>
                                <select id="time" name="time" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach(['09:00', '10:00', '11:00', '13:00', '14:00', '15:00'] as $time)
                                        <option value="{{ $time }}" {{ old('time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endforeach
                                </select>
                                @error('time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Locatie</label>
                                <select id="location" name="location" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="noordwijk" {{ old('location') == 'noordwijk' ? 'selected' : '' }}>Noordwijk</option>
                                    <option value="scheveningen" {{ old('location') == 'scheveningen' ? 'selected' : '' }}>Scheveningen</option>
                                    <option value="ijmuiden" {{ old('location') == 'ijmuiden' ? 'selected' : '' }}>IJmuiden</option>
                                </select>
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Duo Partner Info (if applicable) -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Duo Partner (optioneel)</h3>
                            
                            <div class="mb-4">
                                <label for="duo_name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                <input type="text" id="duo_name" name="duo_name" value="{{ old('duo_name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_email" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres</label>
                                <input type="email" id="duo_email" name="duo_email" value="{{ old('duo_email') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="duo_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                <input type="text" id="duo_phone" name="duo_phone" value="{{ old('duo_phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('duo_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                            Les Inplannen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

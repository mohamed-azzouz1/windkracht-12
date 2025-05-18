@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Student Dashboard</h1>
            
            <!-- Welcome Message -->
            <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <p class="text-lg font-medium text-blue-800">Welkom terug, {{ Auth::user()->name }}!</p>
                <p class="text-gray-600">Hier vind je een overzicht van je lessen en activiteiten.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow-md text-white">
                    <h3 class="text-xl font-bold mb-4">Mijn Statistieken</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Aankomende Lessen</p>
                            <p class="text-2xl font-bold">{{ $upcomingLessonsCount ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Afgeronde Lessen</p>
                            <p class="text-2xl font-bold">{{ $completedLessonsCount ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Ervaring Niveau</p>
                            <p class="text-lg font-bold capitalize">{{ $student->skill_level ?? 'Beginner' }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Volgende Les</p>
                            <p class="text-lg font-bold">{{ $nextLesson ? $nextLesson->start_date->format('d-m-Y') : 'Geen gepland' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Next Lesson -->
                <div class="border border-gray-200 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Eerstvolgende Kitesurf Les</h3>
                    
                    @if(isset($nextLesson))
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-bold text-blue-800">{{ $nextLesson->package->name }}</h4>
                            <div class="flex flex-wrap gap-2 mt-2 text-sm">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    <i class="far fa-calendar mr-1"></i>{{ $nextLesson->start_date->format('d-m-Y') }}
                                </span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    <i class="far fa-clock mr-1"></i>{{ $nextLesson->start_date->format('H:i') }} - {{ $nextLesson->end_date->format('H:i') }}
                                </span>
                                @if($nextLesson->instructor)
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded">
                                        <i class="fas fa-user mr-1"></i>{{ $nextLesson->instructor->user->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('lessons.student') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition-colors">
                                    Bekijk Details
                                </a>
                                <a href="#" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm transition-colors">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Route
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">Je hebt nog geen lessen gepland.</p>
                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition-colors">
                                <i class="fas fa-plus-circle mr-1"></i>Boek nu een les
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Snelle Acties</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('lessons.student') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Lessen</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Boek Nieuwe Les</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mb-2">
                            <i class="fas fa-wind text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Weer Voorspelling</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full mb-2">
                            <i class="fas fa-user-cog text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Profiel</span>
                    </a>
                </div>
            </div>
            
            <!-- Tips & Tricks -->
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Kitesurfing Tips & Tricks</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-blue-700">Beginners Tip: Wind Richting</h4>
                        <p class="text-gray-600 text-sm">Leer hoe je de windrichting kunt lezen en hoe dit van invloed is op je kitesurfsessie.</p>
                        <a href="#" class="text-blue-600 text-sm font-medium mt-2 inline-block hover:underline">Lees meer</a>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-blue-700">Materiaal Onderhoud</h4>
                        <p class="text-gray-600 text-sm">Tips voor het onderhouden van je kitesurfuitrusting om de levensduur te verlengen.</p>
                        <a href="#" class="text-blue-600 text-sm font-medium mt-2 inline-block hover:underline">Lees meer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

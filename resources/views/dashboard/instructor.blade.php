@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Instructeursdashboard</h1>
            
            <!-- Welcome Message -->
            <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <p class="text-lg font-medium text-blue-800">Welkom, {{ Auth::user()->name }}!</p>
                <p class="text-gray-600">Beheer hier je lesschema en leerlingen.</p>
            </div>
            
            <!-- Profile Status -->
            @if(!$profileCompleted)
            <div class="mb-8 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Je profiel is nog niet compleet!</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Om volledig gebruik te kunnen maken van alle functies, vul a.u.b. eerst je persoonsgegevens in.</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <a href="{{ route('instructor.profile.edit') }}" class="bg-yellow-500 px-3 py-1.5 rounded-md text-sm font-medium text-white hover:bg-yellow-600">
                                    Profiel bijwerken
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Stats -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-600 font-medium">Vandaag</p>
                            <p class="text-2xl font-bold text-indigo-800">{{ $todayLessons }}</p>
                        </div>
                        <div class="rounded-full bg-indigo-100 p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-indigo-600">Geplande lessen</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 font-medium">Deze week</p>
                            <p class="text-2xl font-bold text-green-800">{{ $weekLessons }}</p>
                        </div>
                        <div class="rounded-full bg-green-100 p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-green-600">Geplande lessen</p>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Totaal studenten</p>
                            <p class="text-2xl font-bold text-purple-800">{{ $totalStudents }}</p>
                        </div>
                        <div class="rounded-full bg-purple-100 p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-purple-600">Actieve leerlingen</p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Snelle Acties</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('instructor.lessons.day') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Dagoverzicht</span>
                    </a>
                    <a href="{{ route('instructor.lessons.week') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                            <i class="fas fa-calendar-week text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Weekoverzicht</span>
                    </a>
                    <a href="{{ route('instructor.students.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mb-2">
                            <i class="fas fa-users text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Studenten</span>
                    </a>
                    <a href="{{ route('instructor.profile.edit') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-orange-100 text-orange-600 rounded-full mb-2">
                            <i class="fas fa-user-cog text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Profiel</span>
                    </a>
                </div>
            </div>
            
            <!-- Upcoming Lessons -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Aankomende Lessen</h3>
                    <a href="{{ route('instructor.lessons.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Alle lessen bekijken <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(count($upcomingLessons) > 0)
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="divide-y divide-gray-200">
                            @foreach($upcomingLessons as $lesson)
                                <div class="p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $lesson->package->name }}</h4>
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Datum:</span> {{ $lesson->start_date->format('d-m-Y') }}
                                                <span class="mx-1">•</span>
                                                <span class="font-medium">Tijd:</span> {{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}
                                            </div>
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Student:</span> {{ $lesson->student->user->name }}
                                                @if($lesson->duo_name)
                                                <span class="text-gray-400">+</span> {{ $lesson->duo_name }}
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('instructor.lessons.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Geen aankomende lessen</h3>
                        <p class="mt-1 text-sm text-gray-500">Je hebt momenteel geen aankomende lessen gepland.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

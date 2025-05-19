@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Klant Dashboard</h1>
            
            <!-- Welcome Message -->
            <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <p class="text-lg font-medium text-blue-800">Welkom, {{ Auth::user()->name }}!</p>
                <p class="text-gray-600">Bij Windkracht 12 Kitesurfschool beheer je hier al je lessen en gegevens.</p>
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
                            <p>Om gebruik te kunnen maken van alle functies, vul a.u.b. eerst je persoonsgegevens in.</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <a href="{{ route('student.profile.edit') }}" class="bg-yellow-500 px-3 py-1.5 rounded-md text-sm font-medium text-white hover:bg-yellow-600">
                                    Profiel bijwerken
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Snelle Acties</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('student.profile.edit') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                            <i class="fas fa-user text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Profiel</span>
                    </a>
                    <a href="{{ route('student.reservations.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                            <i class="fas fa-calendar-plus text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Nieuwe Reservering</span>
                    </a>
                    <a href="{{ route('student.reservations.list') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mb-2">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Lessen</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-orange-100 text-orange-600 rounded-full mb-2">
                            <i class="fas fa-question-circle text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Hulp Nodig?</span>
                    </a>
                </div>
            </div>
            
            <!-- Upcoming Lessons -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Aankomende Lessen</h3>
                    <a href="{{ route('student.reservations.list') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Alle lessen bekijken <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if($upcomingLessons && $upcomingLessons->count() > 0)
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
                                                <span class="font-medium">Instructeur:</span> {{ $lesson->instructor->user->name }}
                                                <span class="mx-1">•</span>
                                                <span class="font-medium">Status:</span>
                                                <span class="text-{{ $lesson->status === 'confirmed' ? 'green' : ($lesson->status === 'pending' ? 'yellow' : 'red') }}-600">
                                                    {{ ucfirst($lesson->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('student.reservations.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Geen lessen gepland</h3>
                        <p class="mt-1 text-sm text-gray-500">Je hebt momenteel geen aankomende lessen.</p>
                        <div class="mt-6">
                            <a href="{{ route('student.reservations.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i> Les Reserveren
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

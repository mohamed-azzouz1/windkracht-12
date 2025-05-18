@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Instructeur Dashboard</h1>
            
            <!-- Welcome Message -->
            <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <p class="text-lg font-medium text-blue-800">Welkom terug, {{ Auth::user()->name }}!</p>
                <p class="text-gray-600">Hier vind je een overzicht van je ingeplande lessen en studenten.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- Instructor Stats -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-lg shadow-md text-white">
                    <h3 class="text-xl font-bold mb-4">Mijn Statistieken</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Lessen Vandaag</p>
                            <p class="text-2xl font-bold">{{ $todayLessonsCount ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Komende Week</p>
                            <p class="text-2xl font-bold">{{ $upcomingWeekLessonsCount ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Actieve Studenten</p>
                            <p class="text-2xl font-bold">{{ $activeStudentsCount ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Afgeronde Lessen</p>
                            <p class="text-2xl font-bold">{{ $completedLessonsCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Today's Schedule -->
                <div class="border border-gray-200 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-calendar-day mr-2 text-indigo-600"></i>Vandaag's Rooster
                    </h3>
                    
                    @if(isset($todayLessons) && count($todayLessons) > 0)
                        <div class="max-h-64 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-indigo-200 scrollbar-track-gray-100">
                            <div class="space-y-3">
                                @foreach($todayLessons as $lesson)
                                    <div class="border-l-4 border-indigo-500 bg-indigo-50 p-3 rounded-r-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-indigo-800">{{ $lesson->package->name }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    <i class="far fa-clock mr-1"></i>{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}
                                                </p>
                                            </div>
                                            <div class="bg-white px-2 py-1 rounded text-xs font-medium 
                                                 text-{{ $lesson->status === 'confirmed' ? 'green' : 'yellow' }}-700
                                                 bg-{{ $lesson->status === 'confirmed' ? 'green' : 'yellow' }}-100">
                                                {{ ucfirst($lesson->status) }}
                                            </div>
                                        </div>
                                        <div class="mt-2 text-sm">
                                            <span class="font-medium">Student:</span> {{ $lesson->student->user->name }}
                                            @if($lesson->kitesurfer)
                                                <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                    {{ ucfirst($lesson->kitesurfer->skill_level) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('lessons.instructor') }}" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Je hebt vandaag geen lessen ingepland.</p>
                            <p class="text-sm text-gray-400 mt-1">Geniet van je vrije dag!</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Instructeur Tools</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('lessons.instructor') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-full mb-2">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Lesrooster</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Studenten</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full mb-2">
                            <i class="fas fa-wind text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Weer Report</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-red-100 text-red-600 rounded-full mb-2">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Meld Probleem</span>
                    </a>
                </div>
            </div>
            
            <!-- Upcoming Schedule -->
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-week mr-2 text-indigo-600"></i>Aankomende Lessen
                    <a href="{{ route('lessons.instructor') }}" class="ml-auto text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded">
                        Bekijk Alles
                    </a>
                </h3>
                
                @if(isset($upcomingLessons) && count($upcomingLessons) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Les</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcomingLessons as $lesson)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $lesson->start_date->format('d-m-Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $lesson->package->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $lesson->student->user->name }}</div>
                                        @if($lesson->kitesurfer)
                                            <div class="text-xs text-gray-500">{{ ucfirst($lesson->kitesurfer->skill_level) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                              bg-{{ $lesson->status === 'confirmed' ? 'green' : 'yellow' }}-100 
                                              text-{{ $lesson->status === 'confirmed' ? 'green' : 'yellow' }}-800">
                                            {{ ucfirst($lesson->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Geen aankomende lessen gevonden.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

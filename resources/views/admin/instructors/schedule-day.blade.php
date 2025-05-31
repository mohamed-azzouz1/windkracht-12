@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dagrooster: {{ $instructor->user->name }}</h1>
                        <p class="text-gray-600">{{ $selectedDate->format('d-m-Y') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.instructors.schedule') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i>Alle instructeurs
                        </a>
                        <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-calendar-week mr-1"></i>Weekweergave
                        </a>
                        <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-calendar-alt mr-1"></i>Maandweergave
                        </a>
                    </div>
                </div>
                
                <!-- Date Navigation -->
                <div class="flex justify-between items-center mb-6 bg-gray-100 p-4 rounded-lg">
                    <a href="{{ route('admin.instructors.schedule.day', ['id' => $instructor->id, 'date' => $previousDay]) }}" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded border border-gray-300">
                        <i class="fas fa-chevron-left mr-1"></i>Vorige dag
                    </a>
                    <form action="{{ route('admin.instructors.schedule.day', $instructor->id) }}" method="GET" class="flex items-center">
                        <label for="date" class="sr-only">Selecteer datum</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-search mr-1"></i>Bekijken
                        </button>
                    </form>
                    <a href="{{ route('admin.instructors.schedule.day', ['id' => $instructor->id, 'date' => $nextDay]) }}" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded border border-gray-300">
                        Volgende dag<i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                
                <!-- Lessons for the day -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Lessen op {{ $selectedDate->format('d-m-Y') }}
                        <span class="text-sm font-normal text-gray-600 ml-2">{{ $selectedDate->translatedFormat('l') }}</span>
                    </h2>
                    
                    @if($lessons->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="divide-y divide-gray-200">
                                @foreach($lessons as $lesson)
                                    <div class="p-4 hover:bg-gray-50 transition @if($lesson->status === 'cancelled') bg-red-50 @endif">
                                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                            <div class="mb-3 md:mb-0">
                                                <h4 class="font-medium text-gray-900">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</h4>
                                                <div class="mt-1 text-sm text-gray-600">
                                                    <span class="font-medium">Pakket:</span> {{ $lesson->package->name }}
                                                </div>
                                                <div class="mt-1 text-sm text-gray-600">
                                                    <span class="font-medium">Student:</span> {{ $lesson->student->user->name }}
                                                    @if($lesson->duo_name)
                                                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            + Duo: {{ $lesson->duo_name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-1 text-sm flex flex-wrap gap-2">
                                                    @if($lesson->status === 'pending')
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            In afwachting
                                                        </span>
                                                    @elseif($lesson->status === 'confirmed')
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Bevestigd
                                                        </span>
                                                    @elseif($lesson->status === 'cancelled')
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Geannuleerd
                                                        </span>
                                                    @elseif($lesson->status === 'completed')
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Voltooid
                                                        </span>
                                                    @endif
                                                    
                                                    @if($lesson->is_paid)
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Betaald
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Niet betaald
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.registrations.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                    Details
                                                </a>
                                                @if($lesson->status !== 'cancelled' && $lesson->status !== 'completed')
                                                    <a href="{{ route('admin.registrations.edit', $lesson->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white text-sm py-1 px-3 rounded">
                                                        Bewerken
                                                    </a>
                                                @endif
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
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen lessen gevonden</h3>
                            <p class="mt-1 text-sm text-gray-500">Er zijn geen lessen gepland voor deze dag.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.registrations.create', ['instructor_id' => $instructor->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Les Toevoegen
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

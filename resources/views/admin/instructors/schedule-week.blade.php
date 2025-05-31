@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Weekrooster: {{ $instructor->user->name }}</h1>
                        <p class="text-gray-600">{{ $weekStart->format('d-m-Y') }} tot {{ $weekEnd->format('d-m-Y') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.instructors.schedule') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i>Alle instructeurs
                        </a>
                        <a href="{{ route('admin.instructors.schedule.day', ['id' => $instructor->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-calendar-day mr-1"></i>Dagweergave
                        </a>
                        <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-calendar-alt mr-1"></i>Maandweergave
                        </a>
                    </div>
                </div>
                
                <!-- Week Navigation -->
                <div class="flex justify-between items-center mb-6 bg-gray-100 p-4 rounded-lg">
                    <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $previousWeek]) }}" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded border border-gray-300">
                        <i class="fas fa-chevron-left mr-1"></i>Vorige week
                    </a>
                    <form action="{{ route('admin.instructors.schedule.week', $instructor->id) }}" method="GET" class="flex items-center">
                        <label for="date" class="sr-only">Selecteer week</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-search mr-1"></i>Bekijken
                        </button>
                    </form>
                    <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $nextWeek]) }}" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded border border-gray-300">
                        Volgende week<i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                
                <!-- Week Calendar -->
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-7 gap-2 min-w-max">
                        <!-- Day Headers -->
                        @foreach($weekDays as $day)
                            <div class="p-2 bg-gray-100 rounded-t-lg text-center">
                                <div class="font-medium">{{ $day->translatedFormat('D') }}</div>
                                <div class="text-sm">{{ $day->format('d-m') }}</div>
                            </div>
                        @endforeach
                        
                        <!-- Day Contents -->
                        @foreach($weekDays as $day)
                            <div class="bg-white border border-gray-200 rounded-b-lg p-2 h-96 overflow-y-auto">
                                @if(isset($lessonsByDay[$day->format('Y-m-d')]))
                                    @foreach($lessonsByDay[$day->format('Y-m-d')] as $lesson)
                                        <div class="mb-2 p-2 rounded text-sm @if($lesson->status === 'cancelled') bg-red-50 border border-red-200 @else bg-blue-50 border border-blue-200 @endif">
                                            <div class="font-semibold">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</div>
                                            <div>{{ $lesson->student->user->name }}</div>
                                            <div class="text-xs">{{ $lesson->package->name }}</div>
                                            <div class="mt-1 flex items-center space-x-1">
                                                @if($lesson->status === 'pending')
                                                    <span class="px-1 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Wacht</span>
                                                @elseif($lesson->status === 'confirmed')
                                                    <span class="px-1 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">OK</span>
                                                @elseif($lesson->status === 'cancelled')
                                                    <span class="px-1 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Annul</span>
                                                @elseif($lesson->status === 'completed')
                                                    <span class="px-1 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Voltooid</span>
                                                @endif
                                                
                                                @if($lesson->is_paid)
                                                    <span class="px-1 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">€</span>
                                                @endif
                                                
                                                <a href="{{ route('admin.registrations.show', $lesson->id) }}" class="ml-auto px-2 py-0.5 bg-white rounded text-blue-700 hover:bg-blue-50 border border-blue-200">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="h-full flex items-center justify-center">
                                        <p class="text-sm text-gray-500">Geen lessen</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-6 text-right">
                    <a href="{{ route('admin.registrations.create', ['instructor_id' => $instructor->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Nieuwe Les
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

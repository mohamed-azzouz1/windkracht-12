@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Maandrooster: {{ $instructor->user->name }}</h1>
                        <p class="text-gray-600">{{ $monthStart->translatedFormat('F Y') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.instructors.schedule') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i>Alle instructeurs
                        </a>
                        <a href="{{ route('admin.instructors.schedule.day', ['id' => $instructor->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-calendar-day mr-1"></i>Dagweergave
                        </a>
                        <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-calendar-week mr-1"></i>Weekweergave
                        </a>
                    </div>
                </div>
                
                <!-- Month Navigation -->
                <div class="flex justify-between items-center mb-6 bg-gray-100 p-4 rounded-lg">
                    <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $previousMonth]) }}" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded border border-gray-300">
                        <i class="fas fa-chevron-left mr-1"></i>Vorige maand
                    </a>
                    <form action="{{ route('admin.instructors.schedule.month', $instructor->id) }}" method="GET" class="flex items-center">
                        <label for="date" class="sr-only">Selecteer maand</label>
                        <input type="month" id="date" name="date" value="{{ $selectedDate->format('Y-m') }}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-search mr-1"></i>Bekijken
                        </button>
                    </form>
                    <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $nextMonth]) }}" class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded border border-gray-300">
                        Volgende maand<i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                
                <!-- Month Calendar -->
                <div class="overflow-x-auto">
                    <!-- Days of Week Headers -->
                    <div class="grid grid-cols-7 gap-1 text-center font-medium text-gray-700 mb-1">
                        <div>Maandag</div>
                        <div>Dinsdag</div>
                        <div>Woensdag</div>
                        <div>Donderdag</div>
                        <div>Vrijdag</div>
                        <div>Zaterdag</div>
                        <div>Zondag</div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-1">
                        @foreach($calendarDays as $day)
                            @php
                                $isCurrentMonth = $day->month === $monthStart->month;
                                $isToday = $day->isToday();
                                $hasDayLessons = isset($lessonsByDay[$day->format('Y-m-d')]);
                                $dayLessons = $hasDayLessons ? $lessonsByDay[$day->format('Y-m-d')] : [];
                            @endphp
                            
                            <div class="border rounded-lg 
                                @if($isCurrentMonth) 
                                    @if($isToday) bg-blue-50 border-blue-300 @else bg-white border-gray-200 @endif 
                                @else bg-gray-50 border-gray-200 @endif 
                                p-1 min-h-[100px]">
                                
                                <div class="flex justify-between items-center mb-1">
                                    <div class="text-sm @if(!$isCurrentMonth) text-gray-400 @endif @if($isToday) font-bold text-blue-700 @endif">
                                        {{ $day->format('j') }}
                                    </div>
                                    @if($hasDayLessons)
                                        <a href="{{ route('admin.instructors.schedule.day', ['id' => $instructor->id, 'date' => $day->format('Y-m-d')]) }}" class="text-xs bg-blue-100 text-blue-800 px-1.5 rounded-full hover:bg-blue-200">
                                            {{ count($dayLessons) }}
                                        </a>
                                    @endif
                                </div>
                                
                                @if($hasDayLessons && $isCurrentMonth)
                                    <div class="space-y-1">
                                        @foreach(array_slice($dayLessons, 0, 3) as $lesson)
                                            <div class="text-xs p-1 rounded
                                                @if($lesson->status === 'cancelled') bg-red-50 @else bg-blue-50 @endif">
                                                {{ $lesson->start_date->format('H:i') }} - {{ $lesson->student->user->name }}
                                            </div>
                                        @endforeach
                                        
                                        @if(count($dayLessons) > 3)
                                            <div class="text-xs text-center text-gray-500">
                                                + {{ count($dayLessons) - 3 }} meer
                                            </div>
                                        @endif
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

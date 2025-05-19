@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Maandoverzicht Lessen</h1>
                
                <!-- Month Navigation -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('instructor.lessons.month', ['date' => $previousMonth]) }}" class="bg-white border border-gray-300 rounded-md py-2 px-4 hover:bg-gray-50">
                            <i class="fas fa-chevron-left mr-1"></i> Vorige maand
                        </a>
                        <form method="GET" action="{{ route('instructor.lessons.month') }}" class="flex">
                            <input type="month" name="month" value="{{ $selectedDate->format('Y-m') }}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <button type="submit" class="ml-2 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Toon</button>
                        </form>
                        <a href="{{ route('instructor.lessons.month', ['date' => $nextMonth]) }}" class="bg-white border border-gray-300 rounded-md py-2 px-4 hover:bg-gray-50">
                            Volgende maand <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('instructor.lessons.month') }}" class="py-2 px-4 {{ $monthStart->isCurrentMonth() ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300' }} rounded-md hover:bg-blue-600 hover:text-white">
                            Deze maand
                        </a>
                        <a href="{{ route('instructor.lessons.day', ['date' => $selectedDate->format('Y-m-d')]) }}" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50">
                            Dag
                        </a>
                        <a href="{{ route('instructor.lessons.week', ['date' => $selectedDate->format('Y-m-d')]) }}" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50">
                            Week
                        </a>
                    </div>
                </div>
                
                <!-- Month Header -->
                <div class="bg-gray-100 p-4 rounded-lg mb-6 text-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $monthStart->locale('nl')->isoFormat('MMMM YYYY') }}
                    </h2>
                    @if($monthStart->isCurrentMonth())
                        <p class="text-blue-500 font-medium">Huidige maand</p>
                    @endif
                </div>
                
                <!-- Month Calendar -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <!-- Weekday Headers -->
                    <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                        <div class="p-2 text-center border-r border-gray-200"><p class="text-xs font-medium text-gray-500">Ma</p></div>
                        <div class="p-2 text-center border-r border-gray-200"><p class="text-xs font-medium text-gray-500">Di</p></div>
                        <div class="p-2 text-center border-r border-gray-200"><p class="text-xs font-medium text-gray-500">Wo</p></div>
                        <div class="p-2 text-center border-r border-gray-200"><p class="text-xs font-medium text-gray-500">Do</p></div>
                        <div class="p-2 text-center border-r border-gray-200"><p class="text-xs font-medium text-gray-500">Vr</p></div>
                        <div class="p-2 text-center border-r border-gray-200"><p class="text-xs font-medium text-gray-500">Za</p></div>
                        <div class="p-2 text-center"><p class="text-xs font-medium text-gray-500">Zo</p></div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7">
                        @foreach($calendarDays as $day)
                            <div class="min-h-[100px] border-r border-b border-gray-200 last:border-r-0 
                                {{ !$day->isSameMonth($monthStart) ? 'bg-gray-100 opacity-50' : '' }}
                                {{ $day->isWeekend() ? 'bg-gray-50' : '' }}
                                {{ $day->isToday() ? 'bg-blue-50' : '' }}
                                relative">
                                
                                <!-- Day Number -->
                                <div class="flex justify-between p-1">
                                    <a href="{{ route('instructor.lessons.day', ['date' => $day->format('Y-m-d')]) }}" 
                                        class="text-xs {{ $day->isToday() ? 'bg-blue-500 text-white rounded-full w-5 h-5 flex items-center justify-center' : 'text-gray-700' }}">
                                        {{ $day->format('j') }}
                                    </a>
                                    
                                    @php
                                        $dayKey = $day->format('Y-m-d');
                                        $count = isset($lessonsByDay[$dayKey]) ? count($lessonsByDay[$dayKey]) : 0;
                                    @endphp
                                    
                                    @if($count > 0)
                                        <span class="text-xs px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded-full">
                                            {{ $count }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Lessons for this day -->
                                @if(isset($lessonsByDay[$day->format('Y-m-d')]))
                                    <div class="p-1 space-y-1 max-h-[80px] overflow-y-auto">
                                        @foreach($lessonsByDay[$day->format('Y-m-d')] as $lesson)
                                            <a href="{{ route('instructor.lessons.show', $lesson->id) }}" 
                                                class="block text-xs p-1 rounded 
                                                    {{ $lesson->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                      ($lesson->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                      ($lesson->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                      'bg-gray-100 text-gray-800')) }}">
                                                <span class="font-medium">{{ $lesson->start_date->format('H:i') }}</span>
                                                {{ Str::limit($lesson->student->user->name, 12) }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Legend -->
                <div class="mt-4 bg-gray-50 p-4 rounded-lg flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Bevestigd</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">In afwachting</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Geannuleerd</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Vandaag</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

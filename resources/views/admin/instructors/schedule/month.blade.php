@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Maandrooster: {{ $instructor->user->name }}</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.instructors.schedule.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i> Terug naar instructeurs
                        </a>
                    </div>
                </div>
                
                <!-- Date Navigation -->
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $previousMonth]) }}" class="text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-chevron-left mr-1"></i> Vorige maand
                    </a>
                    
                    <div class="flex items-center">
                        <div class="text-center">
                            <div class="text-lg font-bold">{{ $currentDate->format('F Y') }}</div>
                        </div>
                        
                        <form action="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id]) }}" method="GET" class="ml-4 flex items-center">
                            <input type="month" name="date" value="{{ $currentDate->format('Y-m') }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="ml-2 bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded">
                                <i class="fas fa-calendar-check mr-1"></i> Ga naar
                            </button>
                        </form>
                    </div>
                    
                    <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $nextMonth]) }}" class="text-indigo-600 hover:text-indigo-800">
                        Volgende maand <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                
                <!-- Alternate Views -->
                <div class="flex space-x-2 mb-6">
                    <a href="{{ route('admin.instructors.schedule.day', ['id' => $instructor->id, 'date' => $currentDate->format('Y-m-d')]) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-calendar-day mr-1"></i> Dag
                    </a>
                    <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $currentDate->format('Y-m-d')]) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-calendar-week mr-1"></i> Week
                    </a>
                    <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $currentDate->format('Y-m-d')]) }}" class="bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-calendar-alt mr-1"></i> Maand
                    </a>
                </div>
                
                <!-- Month Calendar -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Day headers -->
                    <div class="grid grid-cols-7 gap-px bg-gray-200">
                        <div class="bg-gray-100 p-2 text-center text-xs font-medium text-gray-500 uppercase">Ma</div>
                        <div class="bg-gray-100 p-2 text-center text-xs font-medium text-gray-500 uppercase">Di</div>
                        <div class="bg-gray-100 p-2 text-center text-xs font-medium text-gray-500 uppercase">Wo</div>
                        <div class="bg-gray-100 p-2 text-center text-xs font-medium text-gray-500 uppercase">Do</div>
                        <div class="bg-gray-100 p-2 text-center text-xs font-medium text-gray-500 uppercase">Vr</div>
                        <div class="bg-gray-100 p-2 text-center text-xs font-medium text-gray-500 uppercase">Za</div>
                        <div class="bg-gray-100 p-2 text-center text-xs font-medium text-gray-500 uppercase">Zo</div>
                    </div>
                    
                    <!-- Calendar grid -->
                    <div class="grid grid-cols-7 gap-px bg-gray-200">
                        @foreach($weeks as $week)
                            @foreach($week as $day)
                                <div class="bg-white p-1 min-h-[100px] {{ !$day['isCurrentMonth'] ? 'bg-gray-50 opacity-70' : '' }} {{ $day['isToday'] ? 'ring-2 ring-indigo-500' : '' }}">
                                    <div class="flex justify-between">
                                        <span class="text-sm {{ !$day['isCurrentMonth'] ? 'text-gray-400' : 'font-semibold' }}">{{ $day['day'] }}</span>
                                        @if($day['lessonCount'] > 0)
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded-full">{{ $day['lessonCount'] }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($day['lessonCount'] > 0)
                                        <div class="mt-1 space-y-1">
                                            @foreach($day['lessons'] as $lesson)
                                                <a href="{{ route('admin.registrations.show', $lesson->id) }}" class="block text-xs p-1 rounded
                                                    @if($lesson->status === 'confirmed') bg-green-100 hover:bg-green-200
                                                    @elseif($lesson->status === 'pending') bg-yellow-100 hover:bg-yellow-200
                                                    @elseif($lesson->status === 'cancelled') bg-red-100 hover:bg-red-200
                                                    @else bg-blue-100 hover:bg-blue-200 @endif">
                                                    <div class="font-medium">{{ $lesson->start_date->format('H:i') }}</div>
                                                    <div class="truncate">{{ $lesson->student->user->name }}</div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                
                <!-- Legend -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Legenda</h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 rounded mr-2"></div>
                            <span class="text-sm text-gray-600">Bevestigd</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-100 rounded mr-2"></div>
                            <span class="text-sm text-gray-600">In afwachting</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-100 rounded mr-2"></div>
                            <span class="text-sm text-gray-600">Geannuleerd</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-blue-100 rounded mr-2"></div>
                            <span class="text-sm text-gray-600">Anders</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

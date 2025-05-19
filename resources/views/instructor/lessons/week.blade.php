@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Weekoverzicht Lessen</h1>
                
                <!-- Week Navigation -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('instructor.lessons.week', ['date' => $previousWeek]) }}" class="bg-white border border-gray-300 rounded-md py-2 px-4 hover:bg-gray-50">
                            <i class="fas fa-chevron-left mr-1"></i> Vorige week
                        </a>
                        <form method="GET" action="{{ route('instructor.lessons.week') }}" class="flex">
                            <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <button type="submit" class="ml-2 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Toon</button>
                        </form>
                        <a href="{{ route('instructor.lessons.week', ['date' => $nextWeek]) }}" class="bg-white border border-gray-300 rounded-md py-2 px-4 hover:bg-gray-50">
                            Volgende week <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('instructor.lessons.week') }}" class="py-2 px-4 {{ $weekStart->isCurrentWeek() ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300' }} rounded-md hover:bg-blue-600 hover:text-white">
                            Deze week
                        </a>
                        <a href="{{ route('instructor.lessons.day', ['date' => $selectedDate->format('Y-m-d')]) }}" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50">
                            Dag
                        </a>
                        <a href="{{ route('instructor.lessons.month', ['date' => $selectedDate->format('Y-m-d')]) }}" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50">
                            Maand
                        </a>
                    </div>
                </div>
                
                <!-- Week Header -->
                <div class="bg-gray-100 p-4 rounded-lg mb-6 text-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Week {{ $weekStart->weekOfYear }} ({{ $weekStart->format('d M') }} - {{ $weekEnd->format('d M Y') }})
                    </h2>
                    @if($weekStart->isCurrentWeek())
                        <p class="text-blue-500 font-medium">Huidige week</p>
                    @endif
                </div>
                
                <!-- Week Calendar -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                        @foreach($weekDays as $day)
                            <div class="p-2 text-center border-r border-gray-200 last:border-r-0">
                                <p class="text-xs text-gray-500 uppercase">{{ $day->locale('nl')->isoFormat('ddd') }}</p>
                                <p class="text-sm font-medium {{ $day->isToday() ? 'text-blue-600 bg-blue-100 rounded-full inline-block w-7 h-7 leading-7' : '' }}">
                                    {{ $day->format('d') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="grid grid-cols-7 min-h-[500px]">
                        @foreach($weekDays as $day)
                            <div class="border-r border-gray-200 last:border-r-0 {{ $day->isWeekend() ? 'bg-gray-50' : '' }} {{ $day->isToday() ? 'bg-blue-50' : '' }} p-2">
                                <a href="{{ route('instructor.lessons.day', ['date' => $day->format('Y-m-d')]) }}" class="block text-xs text-center text-blue-600 hover:underline mb-2">
                                    Details
                                </a>
                                
                                @if(isset($lessonsByDay[$day->format('Y-m-d')]))
                                    <div class="space-y-2">
                                        @foreach($lessonsByDay[$day->format('Y-m-d')] as $lesson)
                                            <a href="{{ route('instructor.lessons.show', $lesson->id) }}" class="block p-2 rounded-md border {{ $lesson->status === 'confirmed' ? 'bg-green-50 border-green-200' : ($lesson->status === 'pending' ? 'bg-yellow-50 border-yellow-200' : ($lesson->status === 'cancelled' ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200')) }} hover:shadow-sm">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-medium">{{ $lesson->start_date->format('H:i') }}</span>
                                                    <span class="text-xs px-1.5 py-0.5 rounded-full {{ $lesson->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($lesson->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($lesson->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                        {{ substr(ucfirst($lesson->status), 0, 1) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs font-medium truncate mt-1">{{ $lesson->student->user->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $lesson->package->name }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <p class="text-xs text-gray-400">Geen lessen</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

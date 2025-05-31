@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Weekrooster: {{ $instructor->user->name }}</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.instructors.schedule.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i> Terug naar instructeurs
                        </a>
                    </div>
                </div>
                
                <!-- Date Navigation -->
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $previousWeek]) }}" class="text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-chevron-left mr-1"></i> Vorige week
                    </a>
                    
                    <div class="flex items-center">
                        <div class="text-center">
                            <div class="text-lg font-bold">Week {{ $startDate->weekOfYear }} ({{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }})</div>
                        </div>
                        
                        <form action="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id]) }}" method="GET" class="ml-4 flex items-center">
                            <input type="date" name="date" value="{{ $startDate->format('Y-m-d') }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="ml-2 bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded">
                                <i class="fas fa-calendar-check mr-1"></i> Ga naar
                            </button>
                        </form>
                    </div>
                    
                    <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $nextWeek]) }}" class="text-indigo-600 hover:text-indigo-800">
                        Volgende week <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                
                <!-- Alternate Views -->
                <div class="flex space-x-2 mb-6">
                    <a href="{{ route('admin.instructors.schedule.day', ['id' => $instructor->id, 'date' => $startDate->format('Y-m-d')]) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-calendar-day mr-1"></i> Dag
                    </a>
                    <a href="{{ route('admin.instructors.schedule.week', ['id' => $instructor->id, 'date' => $startDate->format('Y-m-d')]) }}" class="bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-calendar-week mr-1"></i> Week
                    </a>
                    <a href="{{ route('admin.instructors.schedule.month', ['id' => $instructor->id, 'date' => $startDate->format('Y-m-d')]) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-calendar-alt mr-1"></i> Maand
                    </a>
                </div>
                
                <!-- Week Schedule -->
                <div class="grid grid-cols-7 gap-1 bg-white rounded-lg shadow overflow-hidden">
                    <!-- Day headers -->
                    @foreach($days as $day)
                        <div class="bg-gray-100 p-2 text-center">
                            <div class="text-xs font-medium text-gray-500 uppercase">{{ $day['day'] }}</div>
                            <div class="text-lg font-semibold">{{ $day['number'] }}</div>
                        </div>
                    @endforeach
                    
                    <!-- Day content -->
                    @foreach($days as $day)
                        <div class="min-h-[150px] border border-gray-200 p-2 overflow-y-auto">
                            @if(count($day['lessons']) === 0)
                                <div class="text-center text-gray-400 text-xs py-2">Geen lessen</div>
                            @else
                                @foreach($day['lessons'] as $lesson)
                                    <a href="{{ route('admin.registrations.show', $lesson->id) }}" class="block mb-2 rounded p-2 text-xs
                                        @if($lesson->status === 'confirmed') bg-green-100 hover:bg-green-200 border-l-4 border-green-500
                                        @elseif($lesson->status === 'pending') bg-yellow-100 hover:bg-yellow-200 border-l-4 border-yellow-500
                                        @elseif($lesson->status === 'cancelled') bg-red-100 hover:bg-red-200 border-l-4 border-red-500
                                        @else bg-blue-100 hover:bg-blue-200 border-l-4 border-blue-500 @endif">
                                        <div class="font-semibold">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</div>
                                        <div>{{ $lesson->student->user->name }}</div>
                                        <div class="text-gray-600">{{ $lesson->package->name }}</div>
                                        <div class="mt-1 flex justify-between">
                                            <span class="text-xs inline-block rounded-full px-2 py-1
                                                @if($lesson->status === 'confirmed') bg-green-200 text-green-800
                                                @elseif($lesson->status === 'pending') bg-yellow-200 text-yellow-800
                                                @elseif($lesson->status === 'cancelled') bg-red-200 text-red-800
                                                @else bg-blue-200 text-blue-800 @endif">
                                                {{ ucfirst($lesson->status) }}
                                            </span>
                                            <span class="{{ $lesson->is_paid ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $lesson->is_paid ? 'Betaald' : 'Niet betaald' }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

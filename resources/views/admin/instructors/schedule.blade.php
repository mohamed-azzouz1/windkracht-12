@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Instructeur Roosters</h1>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i> Terug naar dashboard
                    </a>
                </div>
                
                <!-- Date Navigation and Filters -->
                <div class="mb-6">
                    <form action="{{ route('admin.instructors.schedule') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/4">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Selecteer week</label>
                            <input type="date" name="date" id="date" value="{{ $currentDate->format('Y-m-d') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="w-full md:w-1/4">
                            <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-1">Instructeur</label>
                            <select name="instructor_id" id="instructor_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Alle instructeurs</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ $selectedInstructorId == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                Filter toepassen
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Week Display -->
                <div class="mb-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Week {{ $weekStart->format('d M') }} - {{ $weekEnd->format('d M Y') }}
                    </h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.instructors.schedule', ['date' => $weekStart->copy()->subWeek()->format('Y-m-d'), 'instructor_id' => $selectedInstructorId]) }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-1 px-3 rounded">
                            <i class="fas fa-chevron-left"></i> Vorige week
                        </a>
                        <a href="{{ route('admin.instructors.schedule', ['date' => Carbon\Carbon::today()->format('Y-m-d'), 'instructor_id' => $selectedInstructorId]) }}" 
                           class="bg-blue-100 hover:bg-blue-200 text-blue-700 py-1 px-3 rounded">
                            Huidige week
                        </a>
                        <a href="{{ route('admin.instructors.schedule', ['date' => $weekStart->copy()->addWeek()->format('Y-m-d'), 'instructor_id' => $selectedInstructorId]) }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-1 px-3 rounded">
                            Volgende week <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Schedule Grid -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 w-40">
                                    Instructeur
                                </th>
                                @foreach($dates as $date)
                                    <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium {{ $date->isToday() ? 'text-blue-600' : 'text-gray-500' }} uppercase tracking-wider border-b border-r border-gray-200">
                                        <div>{{ $date->isoFormat('dddd') }}</div>
                                        <div>{{ $date->format('d-m-Y') }}</div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($schedule as $instructorId => $instructorSchedule)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">
                                        <div class="font-medium">{{ $instructorSchedule['instructor']->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $instructorSchedule['instructor']->specialization ?: 'Geen specialisatie' }}</div>
                                    </td>
                                    
                                    @foreach($dates as $date)
                                        @php $daySchedule = $instructorSchedule['days'][$date->format('Y-m-d')] @endphp
                                        <td class="px-2 py-2 text-sm border-r border-gray-200 {{ $date->isToday() ? 'bg-blue-50' : '' }}">
                                            @if($daySchedule['registrations']->count() > 0)
                                                @foreach($daySchedule['registrations'] as $registration)
                                                    <div class="mb-2 p-2 rounded-md {{ $registration->is_paid ? 'bg-green-100' : 'bg-yellow-100' }} text-xs">
                                                        <div class="font-medium">
                                                            {{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}
                                                        </div>
                                                        <div>{{ $registration->student->user->name }}</div>
                                                        <div class="text-gray-500">{{ $registration->package->name }}</div>
                                                        <a href="{{ route('admin.registrations.show', $registration->id) }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                                            Details
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-xs text-gray-400 py-2">Geen lessen</div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Legend -->
                <div class="mt-4 flex items-center text-sm">
                    <span class="inline-block w-4 h-4 bg-green-100 mr-1 border border-green-200 rounded"></span>
                    <span class="mr-4">Betaald</span>
                    
                    <span class="inline-block w-4 h-4 bg-yellow-100 mr-1 border border-yellow-200 rounded"></span>
                    <span>Niet betaald</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

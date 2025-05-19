@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Dagoverzicht Lessen</h1>
                
                <!-- Date Navigation -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('instructor.lessons.day', ['date' => $previousDay]) }}" class="bg-white border border-gray-300 rounded-md py-2 px-4 hover:bg-gray-50">
                            <i class="fas fa-chevron-left mr-1"></i> Vorige dag
                        </a>
                        <form method="GET" action="{{ route('instructor.lessons.day') }}" class="flex">
                            <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <button type="submit" class="ml-2 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Toon</button>
                        </form>
                        <a href="{{ route('instructor.lessons.day', ['date' => $nextDay]) }}" class="bg-white border border-gray-300 rounded-md py-2 px-4 hover:bg-gray-50">
                            Volgende dag <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('instructor.lessons.day') }}" class="py-2 px-4 {{ $selectedDate->isToday() ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300' }} rounded-md hover:bg-blue-600 hover:text-white">
                            Vandaag
                        </a>
                        <a href="{{ route('instructor.lessons.week', ['date' => $selectedDate->format('Y-m-d')]) }}" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50">
                            Week
                        </a>
                        <a href="{{ route('instructor.lessons.month', ['date' => $selectedDate->format('Y-m-d')]) }}" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50">
                            Maand
                        </a>
                    </div>
                </div>
                
                <!-- Date Header -->
                <div class="bg-gray-100 p-4 rounded-lg mb-6 text-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $selectedDate->locale('nl')->isoFormat('dddd D MMMM YYYY') }}
                    </h2>
                    <p class="text-gray-600">
                        @if($selectedDate->isToday())
                            <span class="text-blue-500 font-medium">Vandaag</span>
                        @elseif($selectedDate->isTomorrow())
                            <span class="text-green-500 font-medium">Morgen</span>
                        @elseif($selectedDate->isYesterday())
                            <span class="text-red-500 font-medium">Gisteren</span>
                        @endif
                    </p>
                </div>
                
                <!-- Lessons -->
                <div class="bg-white rounded-lg border border-gray-200">
                    @if($lessons->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($lessons as $lesson)
                                <div class="p-4 hover:bg-gray-50 transition">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <div class="mb-2 sm:mb-0">
                                            <div class="flex items-center">
                                                <div class="relative w-3 h-3 mr-2">
                                                    <div class="absolute bg-{{ $lesson->status === 'confirmed' ? 'green' : ($lesson->status === 'pending' ? 'yellow' : ($lesson->status === 'cancelled' ? 'red' : 'gray')) }}-500 rounded-full w-3 h-3"></div>
                                                </div>
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">
                                                    {{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}
                                                </span>
                                                <h3 class="text-md font-semibold text-gray-800 ml-2">{{ $lesson->package->name }}</h3>
                                            </div>
                                            <div class="ml-5 mt-1">
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Student:</span> {{ $lesson->student->user->name }}
                                                    @if($lesson->duo_name)
                                                        <span class="text-gray-400">+</span> {{ $lesson->duo_name }}
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Status:</span>
                                                    <span class="text-{{ $lesson->status === 'confirmed' ? 'green' : ($lesson->status === 'pending' ? 'yellow' : ($lesson->status === 'cancelled' ? 'red' : 'gray')) }}-600">
                                                        {{ ucfirst($lesson->status) }}
                                                    </span>
                                                    <span class="mx-1">•</span>
                                                    <span class="font-medium">Betaald:</span>
                                                    <span class="text-{{ $lesson->is_paid ? 'green' : 'red' }}-600">
                                                        {{ $lesson->is_paid ? 'Ja' : 'Nee' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('instructor.lessons.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Details
                                            </a>
                                            @if($lesson->status !== 'cancelled' && $lesson->status !== 'completed')
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-3 rounded">
                                                        Annuleren
                                                    </button>
                                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                                        <div class="py-1">
                                                            <form action="{{ route('instructor.lessons.cancel.sick', $lesson->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                                                    Instructeur ziek
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('instructor.lessons.cancel.weather', $lesson->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                                                    Slecht weer
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('instructor.lessons.cancel.form', $lesson->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                Andere reden...
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen lessen gepland</h3>
                            <p class="mt-1 text-sm text-gray-500">Er zijn geen lessen gepland voor deze dag.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
@endpush
@endsection

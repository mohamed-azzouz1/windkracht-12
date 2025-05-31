@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Mijn Lessen</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('instructor.lessons.day') }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-calendar-day mr-1"></i>Dagweergave
                        </a>
                        <a href="{{ route('instructor.lessons.week') }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-calendar-week mr-1"></i>Weekweergave
                        </a>
                        <a href="{{ route('instructor.lessons.month') }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-calendar-alt mr-1"></i>Maandweergave
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-indigo-600 font-medium">Vandaag</p>
                                <p class="text-2xl font-bold text-indigo-800">{{ $todayCount }}</p>
                            </div>
                            <div class="rounded-full bg-indigo-100 p-3">
                                <i class="fas fa-calendar-day text-indigo-600"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-indigo-600">Lessen voor vandaag</p>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Aankomend</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $upcomingCount }}</p>
                            </div>
                            <div class="rounded-full bg-blue-100 p-3">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-blue-600">Geplande lessen</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600 font-medium">Voltooid</p>
                                <p class="text-2xl font-bold text-green-800">{{ $completedCount }}</p>
                            </div>
                            <div class="rounded-full bg-green-100 p-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-green-600">Afgeronde lessen</p>
                    </div>
                </div>
                
                <!-- Filters -->
                <div class="mb-6">
                    <form action="{{ route('instructor.lessons.index') }}" method="GET" class="flex flex-wrap gap-4 items-end bg-gray-50 p-4 rounded-lg">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Alle statussen</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        @if($status == 'pending')
                                            In afwachting
                                        @elseif($status == 'confirmed')
                                            Bevestigd
                                        @elseif($status == 'cancelled')
                                            Geannuleerd
                                        @elseif($status == 'completed')
                                            Voltooid
                                        @else
                                            {{ ucfirst($status) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Vanaf datum</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tot datum</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Naam of email student" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        
                        <div class="flex space-x-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                                <i class="fas fa-filter mr-1"></i>Filteren
                            </button>
                            <a href="{{ route('instructor.lessons.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md">
                                <i class="fas fa-undo mr-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Lessons Table -->
                @if($lessons->count() > 0)
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tijd</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakket</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locatie</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($lessons as $lesson)
                            <tr class="{{ $lesson->status === 'cancelled' ? 'bg-red-50' : ($lesson->start_date->isToday() ? 'bg-yellow-50' : '') }}">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    {{ $lesson->start_date->format('d-m-Y') }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    {{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}
                                </td>
                                <td class="py-3 px-4">
                                    <div>{{ $lesson->student->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $lesson->student->user->email }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    {{ $lesson->package->name }}
                                    @if($lesson->duo_name)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                            Duo
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    {{ ucfirst($lesson->location ?? 'Onbekend') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($lesson->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        In afwachting
                                    </span>
                                    @elseif($lesson->status == 'confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Bevestigd
                                    </span>
                                    @elseif($lesson->status == 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Geannuleerd
                                    </span>
                                    @elseif($lesson->status == 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Voltooid
                                    </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('instructor.lessons.show', $lesson->id) }}" class="text-blue-600 hover:text-blue-900" title="Details bekijken">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($lesson->status !== 'cancelled' && $lesson->status !== 'completed')
                                        <div class="relative group">
                                            <button type="button" class="text-red-600 hover:text-red-900" title="Annuleren">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                                                <a href="{{ route('instructor.lessons.cancel.form', $lesson->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Annuleren met reden
                                                </a>
                                                <form action="{{ route('instructor.lessons.cancel.weather', $lesson->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Annuleren wegens weer
                                                    </button>
                                                </form>
                                                <form action="{{ route('instructor.lessons.cancel.sick', $lesson->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Annuleren wegens ziekte
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $lessons->withQueryString()->links() }}
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Geen lessen gevonden</h3>
                    <p class="mt-1 text-sm text-gray-500">Er zijn geen lessen die voldoen aan de geselecteerde criteria.</p>
                    <div class="mt-6">
                        <a href="{{ route('instructor.lessons.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-undo mr-2"></i> Alle lessen tonen
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle dropdowns for mobile
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.group');
            dropdowns.forEach(function(dropdown) {
                if (!dropdown.contains(event.target)) {
                    const menu = dropdown.querySelector('.group-hover\\:block');
                    if (menu) {
                        menu.classList.add('hidden');
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection

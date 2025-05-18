@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Mijn Lessen</h1>
            
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
            
            @if(session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                    <p>{{ session('info') }}</p>
                </div>
            @endif
            <div class="mb-6">
                <p class="text-gray-700">Hieronder vind je een overzicht van al je lessen. Je kunt lessen boeken, annuleren of meer informatie bekijken.</p>
            </div>
            <!-- Upcoming Lessons -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-blue-800 mb-4 border-b border-blue-200 pb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>Aankomende Lessen
                </h2>
                
                @if($upcoming->count() > 0)
                    <div class="grid gap-4">
                        @foreach($upcoming as $registration)
                            <div class="bg-blue-50 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                                <div class="p-4 border-l-4 border-blue-500 flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="mb-4 md:mb-0">
                                        <h3 class="text-lg font-bold text-blue-900">{{ $registration->package->name }}</h3>
                                        <div class="flex flex-wrap gap-3 text-sm mt-2">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                <i class="far fa-calendar mr-1"></i>{{ $registration->start_date->format('d-m-Y') }}
                                            </span>
                                            
                                            @if($registration->kitesurfer)
                                                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded">
                                                    <i class="fas fa-wind mr-1"></i>{{ ucfirst($registration->kitesurfer->skill_level) }}
                                                </span>
                                            @endif
                                            
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                <i class="fas fa-clock mr-1"></i>{{ $registration->package->duration_hours }} uur
                                            </span>
                                            
                                            <span class="bg-{{ $registration->status === 'confirmed' ? 'green' : 'yellow' }}-100
                                                  text-{{ $registration->status === 'confirmed' ? 'green' : 'yellow' }}-800 px-2 py-1 rounded">
                                                <i class="fas fa-info-circle mr-1"></i>{{ ucfirst($registration->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2">
                                        @if($registration->instructor)
                                            <div class="bg-white shadow-sm rounded-lg p-3 text-center min-w-[160px]">
                                                <p class="text-xs text-gray-500 uppercase tracking-wide">Instructeur</p>
                                                <p class="font-medium">{{ $registration->instructor->user->name }}</p>
                                            </div>
                                        @endif
                                        
                                        <div class="bg-white shadow-sm rounded-lg p-3 text-center min-w-[160px]">
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Locatie</p>
                                            <p class="font-medium">Strandopgang 12, Noordwijk</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-4 border-t border-gray-100">
                                    <p class="text-sm text-gray-600 mb-3"><i class="fas fa-info-circle mr-1"></i>Zorg dat je een half uur van tevoren aanwezig bent</p>
                                    
                                    <div class="flex flex-wrap gap-2">
                                        <a href="#" class="text-sm bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition">
                                            <i class="fas fa-calendar-alt mr-1"></i>In mijn agenda
                                        </a>
                                        <a href="#" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded transition">
                                            <i class="fas fa-map-marker-alt mr-1"></i>Route
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 mb-4">Je hebt nog geen aankomende lessen gepland.</p>
                        <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition">
                            <i class="fas fa-plus-circle mr-1"></i>Les boeken
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Past Lessons -->
            <div>
                <h2 class="text-xl font-semibold text-blue-800 mb-4 border-b border-blue-200 pb-2">
                    <i class="fas fa-history mr-2"></i>Afgeronde Lessen
                </h2>
                
                @if($past->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructeur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($past as $registration)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->package->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $registration->start_date->format('d-m-Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $registration->instructor ? $registration->instructor->user->name : 'Niet toegewezen' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                  bg-{{ $registration->status === 'completed' ? 'green' : 'gray' }}-100 
                                                  text-{{ $registration->status === 'completed' ? 'green' : 'gray' }}-800">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye mr-1"></i>Details</a>
                                            <a href="#" class="text-green-600 hover:text-green-900"><i class="fas fa-redo-alt mr-1"></i>Opnieuw boeken</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Je hebt nog geen afgeronde lessen.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

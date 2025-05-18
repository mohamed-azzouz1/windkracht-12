@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold text-blue-900">Mijn Lesschema</h1>
                
                <div class="mt-4 md:mt-0 flex space-x-2">
                    <a href="#" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded transition">
                        <i class="fas fa-calendar-alt mr-2"></i>Week Overzicht
                    </a>
                    <a href="#" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium py-2 px-4 rounded transition">
                        <i class="fas fa-clock mr-2"></i>Beschikbaarheid Instellen
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
            
            <!-- Today's Lessons -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-blue-800 mb-4 border-b border-blue-200 pb-2">
                    <i class="fas fa-sun mr-2"></i>Vandaag ({{ now()->format('d-m-Y') }})
                </h2>
                
                @if($today->count() > 0)
                    <div class="grid gap-4">
                        @foreach($today as $registration)
                            <div class="bg-blue-50 rounded-lg overflow-hidden shadow-md">
                                <div class="p-4 border-l-4 border-green-500 flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="mb-4 md:mb-0">
                                        <h3 class="text-lg font-bold text-blue-900">{{ $registration->package->name }}</h3>
                                        <div class="flex flex-wrap gap-3 text-sm mt-2">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">
                                                <i class="far fa-calendar mr-1"></i>Vandaag
                                            </span>
                                            
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                <i class="fas fa-users mr-1"></i>{{ $registration->package->max_participants }} deelnemers
                                            </span>
                                            
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                <i class="fas fa-clock mr-1"></i>{{ $registration->package->duration_hours }} uur
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col md:items-end">
                                        <div class="bg-white shadow-sm rounded-lg p-3 text-center min-w-[200px]">
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Student</p>
                                            <p class="font-medium">{{ $registration->student->user->name }}</p>
                                            @if($registration->kitesurfer)
                                                <span class="inline-block mt-1 text-xs bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded">
                                                    {{ ucfirst($registration->kitesurfer->skill_level) }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-end mt-3 space-x-2">
                                            <a href="#" class="text-sm bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded transition">
                                                <i class="fas fa-check mr-1"></i>Aftekenen
                                            </a>
                                            <a href="#" class="text-sm bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded transition">
                                                <i class="fas fa-info-circle mr-1"></i>Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($registration->kitesurfer && $registration->kitesurfer->equipment_needs)
                                    <div class="bg-yellow-50 p-3 border-t border-yellow-100">
                                        <p class="text-sm text-yellow-800"><i class="fas fa-exclamation-triangle mr-1"></i><strong>Materiaal nodig:</strong> {{ $registration->kitesurfer->equipment_needs }}</p>
                                    </div>
                                @endif
                                
                                <div class="bg-white p-4 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt mr-1"></i>Strandopgang 12, Noordwijk</p>
                                        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-comment-alt mr-1"></i>Contact Opnemen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Je hebt vandaag geen lessen ingepland.</p>
                    </div>
                @endif
            </div>
            
            <!-- Upcoming Lessons -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-blue-800 mb-4 border-b border-blue-200 pb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>Aankomende Lessen
                </h2>
                
                @if($upcoming->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcoming->sortBy('start_date') as $registration)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->start_date->format('d-m-Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $registration->start_date->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $registration->package->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $registration->package->duration_hours }} uur</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->student->user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($registration->kitesurfer)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                      bg-indigo-100 text-indigo-800">
                                                    {{ ucfirst($registration->kitesurfer->skill_level) }}
                                                </span>
                                            @else
                                                <span class="text-gray-500 text-sm">Onbekend</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                  bg-{{ $registration->status === 'confirmed' ? 'green' : 'yellow' }}-100 
                                                  text-{{ $registration->status === 'confirmed' ? 'green' : 'yellow' }}-800">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-info-circle mr-1"></i>Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Je hebt geen aankomende lessen gepland.</p>
                @endif
            </div>
            
            <!-- Lesson Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Totaal deze maand</h3>
                    <p class="text-3xl font-bold text-blue-900">{{ $registrations->where('start_date', '>=', now()->startOfMonth())->count() }}</p>
                    <p class="text-sm text-blue-600 mt-1">lessen gegeven</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Aankomende week</h3>
                    <p class="text-3xl font-bold text-green-900">{{ $registrations->where('start_date', '>=', now())->where('start_date', '<=', now()->addDays(7))->count() }}</p>
                    <p class="text-sm text-green-600 mt-1">lessen ingepland</p>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-800 mb-2">Gemiddelde rating</h3>
                    <p class="text-3xl font-bold text-purple-900">4.8<span class="text-xl">/5</span></p>
                    <p class="text-sm text-purple-600 mt-1">van studenten</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

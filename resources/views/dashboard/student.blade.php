@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Klant Dashboard</h1>
            
            <!-- Welcome Message -->
            <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <p class="text-lg font-medium text-blue-800">Welkom, {{ Auth::user()->name }}!</p>
                <p class="text-gray-600">Bij Windkracht 12 Kitesurfschool beheer je hier al je lessen en gegevens.</p>
            </div>
            
            <!-- Profile Status -->
            @if(!$profileCompleted)
            <div class="mb-8 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Je profiel is nog niet compleet!</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Om gebruik te kunnen maken van alle functies, vul a.u.b. eerst je persoonsgegevens in.</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <a href="{{ route('student.profile.edit') }}" class="bg-yellow-500 px-3 py-1.5 rounded-md text-sm font-medium text-white hover:bg-yellow-600">
                                    Profiel bijwerken
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Stats -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-600 font-medium">Aankomend</p>

                            <p class="text-2xl font-bold text-indigo-800">{{ isset($upcomingLessons) ? $upcomingLessons->count() : 0 }}</p>
                        </div>
                        <div class="rounded-full bg-indigo-100 p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-indigo-600">Geplande lessen</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 font-medium">Afgerond</p>
                            <p class="text-2xl font-bold text-green-800">{{ isset($pastLessons) ? $pastLessons->count() : 0 }}</p>
                        </div>
                        <div class="rounded-full bg-green-100 p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-green-600">Afgeronde lessen</p>
                </div>
                
                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-600 font-medium">Geannuleerd</p>
                            <p class="text-2xl font-bold text-red-800">{{ isset($cancelledLessons) ? $cancelledLessons->count() : 0 }}</p>
                        </div>
                        <div class="rounded-full bg-red-100 p-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-red-600">Geannuleerde lessen</p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Snelle Acties</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('student.profile.edit') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                            <i class="fas fa-user text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Profiel</span>
                    </a>
                    <a href="{{ route('student.reservations.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                            <i class="fas fa-calendar-plus text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Nieuwe Reservering</span>
                    </a>
                    <a href="{{ route('student.reservations.list') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mb-2">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Alle Lessen</span>
                    </a>
                    <a href="{{ route('student.reservations.list') }}#past" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-gray-100 text-gray-600 rounded-full mb-2">
                            <i class="fas fa-history text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Geschiedenis</span>
                    </a>
                    <a href="{{ route('student.reservations.list', ['tab' => 'cancelled']) }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-red-100 text-red-600 rounded-full mb-2">
                            <i class="fas fa-times-circle text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Geannuleerde Lessen</span>
                    </a>
                </div>
            </div>
            
            <!-- Upcoming Lessons -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Aankomende Lessen</h3>
                    <a href="{{ route('student.reservations.list') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Alle lessen bekijken <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(isset($upcomingLessons) && $upcomingLessons->count() > 0)
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="divide-y divide-gray-200">
                            @foreach($upcomingLessons as $lesson)
                                <div class="p-4 hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                        <div class="mb-2 md:mb-0">
                                            <h4 class="font-medium text-gray-900">{{ $lesson->package->name }}</h4>
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Datum:</span> {{ $lesson->start_date->format('d-m-Y') }}
                                                <span class="mx-1">•</span>
                                                <span class="font-medium">Tijd:</span> {{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}
                                            </div>
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Locatie:</span> {{ ucfirst($lesson->location) }}
                                                <span class="mx-1">•</span>
                                                <span class="font-medium">Instructeur:</span> {{ $lesson->instructor->user->name }}
                                            </div>
                                            <div class="mt-1 text-sm flex items-center">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($lesson->is_paid) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                                    {{ $lesson->is_paid ? 'Betaald' : 'Niet betaald' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col space-y-2">
                                            <a href="{{ route('student.reservations.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Details
                                            </a>
                                            @if(!$lesson->is_paid)
                                            <a href="{{ route('student.reservations.payment.form', $lesson->id) }}" class="bg-green-500 hover:bg-green-600 text-white text-sm py-1 px-3 rounded">
                                                Betaling melden
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="mb-4 text-gray-500">Je hebt momenteel geen aankomende lessen gepland.</p>
                            <a href="{{ route('student.reservations.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Les Boeken
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Package Overview -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Mijn Lespakketten</h3>
                </div>
                
                @if(isset($packageRegistrations) && count($packageRegistrations) > 0)
                    <div class="space-y-6">
                        @foreach($packageRegistrations as $packageId => $info)
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                                    <h3 class="text-lg font-medium text-white">{{ $info['package']->name }}</h3>
                                    <p class="text-sm text-blue-100">{{ $info['package']->description }}</p>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="p-4 bg-white border-b">
                                    <div class="flex justify-between items-center mb-1">
                                        <div class="text-sm font-medium text-gray-700">Voortgang</div>
                                        <div class="text-sm font-medium text-gray-700">{{ $info['completed'] }}/{{ $info['total'] }} lessen voltooid</div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $info['progress'] }}%"></div>
                                    </div>
                                </div>
                                
                                <!-- Lesson Days Table -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tijd</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locatie</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actie</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($info['registrations'] as $reg)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $reg->start_date->format('d-m-Y') }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $reg->start_date->format('H:i') }} - {{ $reg->end_date->format('H:i') }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($reg->location) }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $reg->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                            ($reg->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                            ($reg->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                            {{ $reg->status === 'completed' ? 'Voltooid' : 
                                                            ($reg->status === 'cancelled' ? 'Geannuleerd' : 
                                                            ($reg->status === 'confirmed' ? 'Bevestigd' : 'In afwachting')) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right">
                                                        <a href="{{ route('student.reservations.show', $reg->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Details</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white p-8 rounded-lg border border-gray-200 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Geen lespakketten gevonden</h3>
                            <p class="text-gray-500 mb-6">Je hebt nog geen lespakketten geboekt.</p>
                            <a href="{{ route('student.reservations.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Lespakket Kiezen
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Past Lessons -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Afgeronde Lessen</h3>
                    <a href="{{ route('student.reservations.list') }}#past" class="text-sm text-blue-600 hover:text-blue-800">
                        Alle afgeronde lessen <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(isset($pastLessons) && $pastLessons->count() > 0)
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="divide-y divide-gray-200">
                            @foreach($pastLessons->take(2) as $lesson)
                                <div class="p-4 hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                        <div class="mb-2 md:mb-0">
                                            <h4 class="font-medium text-gray-900">{{ $lesson->package->name }}</h4>
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Datum:</span> {{ $lesson->start_date->format('d-m-Y') }}
                                                <span class="mx-1">•</span>
                                                <span class="font-medium">Instructeur:</span> {{ $lesson->instructor->user->name }}
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('student.reservations.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Geen afgeronde lessen</h3>
                        <p class="mt-1 text-sm text-gray-500">Je hebt nog geen lessen afgerond.</p>
                    </div>
                @endif
            </div>
            
            <!-- Cancelled Lessons -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Geannuleerde Lessen</h3>
                    <a href="{{ route('student.reservations.list') }}#cancelled" class="text-sm text-blue-600 hover:text-blue-800">
                        Alle geannuleerde lessen <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(isset($cancelledLessons) && $cancelledLessons->count() > 0)
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="divide-y divide-gray-200">
                            @foreach($cancelledLessons->take(2) as $lesson)
                                <div class="p-4 hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                        <div class="mb-2 md:mb-0">
                                            <h4 class="font-medium text-gray-900">{{ $lesson->package->name }}</h4>
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Datum:</span> {{ $lesson->start_date->format('d-m-Y') }}
                                                <span class="mx-1">•</span>
                                                <span class="font-medium">Reden:</span> {{ $lesson->cancellation_reason ?? 'Geen reden opgegeven' }}
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('student.reservations.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Geen geannuleerde lessen</h3>
                        <p class="mt-1 text-sm text-gray-500">Je hebt geen geannuleerde lessen.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

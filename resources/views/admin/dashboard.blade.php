@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Eigenaar Dashboard</h1>
                
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Klanten</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $studentCount ?? 0 }}</p>
                            </div>
                            <div class="rounded-full bg-blue-100 p-3">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.students.index') }}" class="block mt-2 text-sm text-blue-600 hover:text-blue-800">
                            Bekijk alle klanten <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600 font-medium">Instructeurs</p>
                                <p class="text-2xl font-bold text-green-800">{{ $instructorCount ?? 0 }}</p>
                            </div>
                            <div class="rounded-full bg-green-100 p-3">
                                <i class="fas fa-chalkboard-teacher text-green-600"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.instructors.index') }}" class="block mt-2 text-sm text-green-600 hover:text-green-800">
                            Bekijk alle instructeurs <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-600 font-medium">Lessen</p>
                                <p class="text-2xl font-bold text-purple-800">{{ $upcomingLessons->count() ?? 0 }}</p>
                            </div>
                            <div class="rounded-full bg-purple-100 p-3">
                                <i class="fas fa-calendar-alt text-purple-600"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.registrations.index') }}" class="block mt-2 text-sm text-purple-600 hover:text-purple-800">
                            Bekijk alle lessen <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-red-600 font-medium">Niet Betaald</p>
                                <p class="text-2xl font-bold text-red-800">{{ $unpaidLessons->count() ?? 0 }}</p>
                            </div>
                            <div class="rounded-full bg-red-100 p-3">
                                <i class="fas fa-euro-sign text-red-600"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.registrations.index', ['is_paid' => 0]) }}" class="block mt-2 text-sm text-red-600 hover:text-red-800">
                            Bekijk onbetaalde lessen <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Snelle Acties</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.students.create') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                            <span class="h-12 w-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                                <i class="fas fa-user-plus text-xl"></i>
                            </span>
                            <span class="text-sm font-medium text-center">Nieuwe Gebruiker</span>
                        </a>
                        
                        <a href="{{ route('admin.registrations.create') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                            <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                                <i class="fas fa-calendar-plus text-xl"></i>
                            </span>
                            <span class="text-sm font-medium text-center">Nieuwe Les</span>
                        </a>
                        
                        <a href="{{ route('admin.profile.edit') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                            <span class="h-12 w-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mb-2">
                                <i class="fas fa-user-cog text-xl"></i>
                            </span>
                            <span class="text-sm font-medium text-center">Mijn Profiel</span>
                        </a>
                        
                        <a href="{{ route('admin.instructors.schedule.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                            <span class="h-12 w-12 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full mb-2">
                                <i class="fas fa-calendar-week text-xl"></i>
                            </span>
                            <span class="text-sm font-medium text-center">Instructeur Roosters</span>
                        </a>
                    </div>
                </div>
                
                <!-- Upcoming Lessons -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Aankomende Lessen</h2>
                        <a href="{{ route('admin.registrations.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Alle lessen bekijken <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    @if($upcomingLessons->count() > 0)
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
                                                    <span class="font-medium">Student:</span> {{ $lesson->student->user->name }}
                                                    <span class="mx-1">•</span>
                                                    <span class="font-medium">Instructeur:</span> {{ $lesson->instructor->user->name }}
                                                </div>
                                                <div class="mt-1 text-sm flex flex-wrap gap-2">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($lesson->status === 'confirmed') bg-green-100 text-green-800 
                                                        @else bg-yellow-100 text-yellow-800 @endif">
                                                        {{ $lesson->status === 'confirmed' ? 'Bevestigd' : 'In afwachting' }}
                                                    </span>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($lesson->is_paid) bg-green-100 text-green-800 
                                                        @else bg-red-100 text-red-800 @endif">
                                                        {{ $lesson->is_paid ? 'Betaald' : 'Niet betaald' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex flex-col space-y-2">
                                                <a href="{{ route('admin.registrations.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                    Details
                                                </a>
                                                @if(!$lesson->is_paid)
                                                <form action="{{ route('admin.registrations.mark-as-paid', $lesson->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm py-1 px-3 rounded w-full">
                                                        Als betaald markeren
                                                    </button>
                                                </form>
                                                @endif
                                                @if($lesson->status === 'pending')
                                                <form action="{{ route('admin.registrations.mark-as-confirmed', $lesson->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-1 px-3 rounded w-full">
                                                        Bevestigen
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen aankomende lessen</h3>
                            <p class="mt-1 text-sm text-gray-500">Er zijn momenteel geen aankomende lessen gepland.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.registrations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Les Toevoegen
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Unpaid Lessons -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Onbetaalde Lessen</h2>
                        <a href="{{ route('admin.registrations.index', ['is_paid' => 0]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Alle onbetaalde lessen <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    @if($unpaidLessons->count() > 0)
                        <div class="bg-white rounded-lg border border-gray-200">
                            <div class="divide-y divide-gray-200">
                                @foreach($unpaidLessons as $lesson)
                                    <div class="p-4 hover:bg-gray-50 transition">
                                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                            <div class="mb-2 md:mb-0">
                                                <h4 class="font-medium text-gray-900">{{ $lesson->package->name }}</h4>
                                                <div class="mt-1 text-sm text-gray-600">
                                                    <span class="font-medium">Datum:</span> {{ $lesson->start_date->format('d-m-Y') }}
                                                    <span class="mx-1">•</span>
                                                    <span class="font-medium">Student:</span> {{ $lesson->student->user->name }}
                                                </div>
                                                <div class="mt-1 text-sm text-gray-600">
                                                    <span class="font-medium">Bedrag:</span> €{{ number_format($lesson->package->price, 2, ',', '.') }}
                                                </div>
                                            </div>
                                            <div class="flex flex-col space-y-2">
                                                <a href="{{ route('admin.registrations.show', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded">
                                                    Details
                                                </a>
                                                <form action="{{ route('admin.registrations.mark-as-paid', $lesson->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm py-1 px-3 rounded w-full">
                                                        Als betaald markeren
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen onbetaalde lessen</h3>
                            <p class="mt-1 text-sm text-gray-500">Alle lessen zijn betaald.</p>
                        </div>
                    @endif
                </div>
                
                <!-- User Management -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Gebruikersbeheer</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- User Management Card -->
                        <a href="{{ route('admin.users.index') }}" class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition duration-150 ease-in-out">
                            <div class="flex items-center">
                                <div class="bg-purple-100 rounded-full p-3 mr-4">
                                    <i class="fas fa-users-cog text-purple-500 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-700">Gebruikersbeheer</h3>
                                    <p class="text-gray-500">Wijzig rollen van gebruikers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

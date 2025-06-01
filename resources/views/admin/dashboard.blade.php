@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Eigenaar Dashboard</h1>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 shadow-sm flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-blue-600">Klanten</h3>
                        <p class="text-3xl font-bold text-blue-800">{{ $studentCount ?? 0 }}</p>
                        <a href="{{ route('admin.students.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center mt-1">
                            Bekijk alle klanten <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3">
                        <i class="fas fa-user-friends text-blue-500 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg border border-green-100 shadow-sm flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-green-600">Instructeurs</h3>
                        <p class="text-3xl font-bold text-green-800">{{ $instructorCount ?? 0 }}</p>
                        <a href="{{ route('admin.instructors.index') }}" class="text-sm text-green-600 hover:underline inline-flex items-center mt-1">
                            Bekijk alle instructeurs <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <div class="rounded-full bg-green-100 p-3">
                        <i class="fas fa-chalkboard-teacher text-green-500 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100 shadow-sm flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-purple-600">Lessen</h3>
                        <p class="text-3xl font-bold text-purple-800">{{ $upcomingLessons->count() ?? 0 }}</p>
                        <a href="{{ route('admin.registrations.index') }}" class="text-sm text-purple-600 hover:underline inline-flex items-center mt-1">
                            Bekijk alle lessen <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3">
                        <i class="fas fa-calendar-alt text-purple-500 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-red-50 p-4 rounded-lg border border-red-100 shadow-sm flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-red-600">Niet Betaald</h3>
                        <p class="text-3xl font-bold text-red-800">{{ $unpaidLessons->count() ?? 0 }}</p>
                        <a href="{{ route('admin.registrations.unpaid') }}" class="text-sm text-red-600 hover:underline inline-flex items-center mt-1">
                            Bekijk onbetaalde lessen <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <div class="rounded-full bg-red-100 p-3">
                        <i class="fas fa-euro-sign text-red-500 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Gebruikersbeheer Section -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Gebruikersbeheer</h2>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 shadow-sm">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 mr-4">
                            <i class="fas fa-users-cog text-blue-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-blue-800">Gebruikersbeheer</h3>
                            <p class="text-sm text-blue-600">Wijzig rollen van gebruikers</p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm text-sm">
                                Beheer gebruikers
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Snelle Acties</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.students.create') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                            <i class="fas fa-user-plus text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Nieuwe Student</span>
                    </a>
                    
                    <a href="{{ route('admin.registrations.create') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                            <i class="fas fa-calendar-plus text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Nieuwe Les</span>
                    </a>
                    
                    <a href="{{ route('admin.profile.edit') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mb-2">
                            <i class="fas fa-user-circle text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Mijn Profiel</span>
                    </a>
                    
                    <a href="{{ route('admin.instructors.schedule.index') }}" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 transition-colors">
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
                    <h2 class="text-xl font-bold text-gray-800">Aankomende Lessen</h2>
                    <a href="{{ route('admin.registrations.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Alle lessen bekijken <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(isset($upcomingLessons) && $upcomingLessons->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakket</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum & Tijd</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructeur</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcomingLessons as $lesson)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $lesson->student->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $lesson->student->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $lesson->package->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $lesson->start_date->format('d-m-Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $lesson->instructor->user->name ?? 'Niet toegewezen' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $lesson->status == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($lesson->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-white p-4 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-500">Geen aankomende lessen gevonden.</p>
                    </div>
                @endif
            </div>

            <!-- Unpaid Lessons -->
            @if(isset($unpaidLessons) && $unpaidLessons->count() > 0)
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Onbetaalde Lessen</h2>
                        <a href="{{ route('admin.registrations.unpaid') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Alle onbetaalde lessen bekijken <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakket</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum & Tijd</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bedrag</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actie</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($unpaidLessons as $lesson)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $lesson->student->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $lesson->student->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $lesson->package->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $lesson->start_date->format('d-m-Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">€ {{ number_format($lesson->package->price, 2, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('admin.registrations.mark-as-paid', $lesson->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-sm">
                                                    Markeer als betaald
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Instructeur Details</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.instructors.edit', $instructor->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
                            <i class="fas fa-edit mr-1"></i> Bewerken
                        </a>
                        <a href="{{ route('admin.instructors.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i> Terug
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                <!-- Instructor Details -->
                <div class="bg-blue-50 p-6 rounded-lg shadow-sm mb-6 border border-blue-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Personal Information -->
                        <div>
                            <h2 class="text-xl font-bold text-blue-800 mb-4">Persoonlijke informatie</h2>
                            <p class="mb-2"><span class="font-semibold">Naam:</span> {{ $instructor->user->name }}</p>
                            <p class="mb-2"><span class="font-semibold">Email:</span> {{ $instructor->user->email }}</p>
                            <p class="mb-2"><span class="font-semibold">Telefoon:</span> {{ $instructor->phone ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Geboortedatum:</span> {{ $instructor->date_of_birth ? $instructor->date_of_birth->format('d-m-Y') : 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Status:</span> 
                                <span class="px-2 py-1 rounded-full text-xs {{ $instructor->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $instructor->is_active ? 'Actief' : 'Inactief' }}
                                </span>
                            </p>
                        </div>
                        
                        <!-- Address Information -->
                        <div>
                            <h2 class="text-xl font-bold text-blue-800 mb-4">Adresgegevens</h2>
                            <p class="mb-2"><span class="font-semibold">Adres:</span> {{ $instructor->address ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Plaats:</span> {{ $instructor->city ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">BSN:</span> {{ $instructor->bsn ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Account aangemaakt:</span> {{ $instructor->created_at->format('d-m-Y H:i') }}</p>
                        </div>
                        
                        <!-- Professional Information -->
                        <div>
                            <h2 class="text-xl font-bold text-blue-800 mb-4">Professionele informatie</h2>
                            <p class="mb-2"><span class="font-semibold">Certificering:</span> {{ $instructor->certification ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Ervaring:</span> {{ $instructor->years_of_experience ? $instructor->years_of_experience . ' jaar' : 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Specialisatie:</span> {{ $instructor->specialization ?: 'Niet ingevuld' }}</p>
                        </div>
                    </div>
                    
                    @if($instructor->biography)
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <h2 class="text-xl font-bold text-blue-800 mb-2">Biografie</h2>
                        <p class="text-gray-700">{{ $instructor->biography }}</p>
                    </div>
                    @endif
                </div>
                
                <!-- Upcoming Lessons -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Aankomende Lessen</h2>
                    
                    @if($upcomingLessons->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pakket
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Datum & Tijd
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acties
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($upcomingLessons as $lesson)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $lesson->student->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $lesson->student->phone }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $lesson->package->name }}</div>
                                                <div class="text-sm text-gray-500">{{ ucfirst($lesson->location) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $lesson->start_date->format('d-m-Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($lesson->status === 'confirmed') bg-green-100 text-green-800
                                                    @elseif($lesson->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($lesson->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    {{ ucfirst($lesson->status) }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $lesson->is_paid ? 'Betaald' : 'Niet betaald' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.registrations.show', $lesson->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $upcomingLessons->links() }}
                        </div>
                    @else
                        <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                            <p class="text-gray-500">Deze instructeur heeft geen aankomende lessen.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

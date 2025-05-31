@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Student Details</h1>
                    <div class="space-x-2">
                        <a href="{{ route('instructor.students.lessons.create', $student->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-plus mr-1"></i>Les Inplannen
                        </a>
                        <a href="{{ route('instructor.students.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i>Terug naar studenten
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
                
                <!-- Student Info -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                    <div class="flex flex-col md:flex-row justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-blue-800">{{ $student->user->name }}</h2>
                            <p class="text-blue-600">{{ $student->user->email }}</p>
                            @if($student->phone)
                                <p class="text-blue-600">Tel: {{ $student->phone }}</p>
                            @endif
                            @if($student->address && $student->city)
                                <p class="text-blue-600 mt-2">{{ $student->address }}, {{ $student->city }}</p>
                            @endif
                            @if($student->date_of_birth)
                                <p class="text-blue-600">Geboortedatum: {{ $student->date_of_birth->format('d-m-Y') }}</p>
                            @endif
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-2">
                                Niveau: {{ ucfirst($student->skill_level ?? 'Beginner') }}
                            </span>
                            <a href="{{ route('instructor.students.edit', $student->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-1 px-3 rounded mt-2">
                                Gegevens Bewerken
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Student Lessons -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Lessen</h2>
                    
                    @if(isset($lessons) && $lessons->count() > 0)
                        <div class="overflow-hidden border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
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
                                    @foreach($lessons as $lesson)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $lesson->package->name }}</div>
                                                <div class="text-sm text-gray-500">{{ ucfirst($lesson->location) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $lesson->start_date->format('d-m-Y') }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($lesson->status === 'confirmed') bg-green-100 text-green-800
                                                    @elseif($lesson->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($lesson->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    {{ ucfirst($lesson->status) }}
                                                </span>
                                                <div class="text-sm text-gray-500 mt-1">
                                                    {{ $lesson->is_paid ? 'Betaald' : 'Niet betaald' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('instructor.lessons.show', $lesson->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if(method_exists($lessons, 'hasPages') && $lessons->hasPages())
                            <div class="mt-4">
                                {{ $lessons->links() }}
                            </div>
                        @endif
                    @else
                        <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen lessen gevonden</h3>
                            <p class="mt-1 text-sm text-gray-500">Deze student heeft nog geen lessen bij jou gevolgd.</p>
                            <div class="mt-6">
                                <a href="{{ route('instructor.students.lessons.create', $student->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Les Inplannen
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Student Notes -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Notities</h2>
                    
                    <div class="mb-4">
                        <form action="{{ route('instructor.students.update', $student->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" name="name" value="{{ $student->user->name }}">
                            <input type="hidden" name="address" value="{{ $student->address }}">
                            <input type="hidden" name="city" value="{{ $student->city }}">
                            <input type="hidden" name="phone" value="{{ $student->phone }}">
                            <input type="hidden" name="date_of_birth" value="{{ optional($student->date_of_birth)->format('Y-m-d') }}">
                            <input type="hidden" name="skill_level" value="{{ $student->skill_level }}">
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notities over deze student</label>
                                <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $student->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                                    Notities Opslaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

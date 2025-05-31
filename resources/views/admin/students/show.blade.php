@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Klantgegevens</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.students.lessons.create', $student->id) }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                            <i class="fas fa-plus mr-1"></i> Nieuwe les
                        </a>
                        <a href="{{ route('admin.students.edit', $student->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
                            <i class="fas fa-edit mr-1"></i> Bewerken
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i> Terug
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                <!-- Student Details -->
                <div class="bg-blue-50 p-6 rounded-lg shadow-sm mb-6 border border-blue-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-xl font-bold text-blue-800 mb-4">Persoonlijke informatie</h2>
                            <p class="mb-2"><span class="font-semibold">Naam:</span> {{ $student->user->name }}</p>
                            <p class="mb-2"><span class="font-semibold">Email:</span> {{ $student->user->email }}</p>
                            <p class="mb-2"><span class="font-semibold">Telefoon:</span> {{ $student->phone ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Geboortedatum:</span> {{ $student->date_of_birth ? $student->date_of_birth->format('d-m-Y') : 'Niet ingevuld' }}</p>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-blue-800 mb-4">Adresgegevens</h2>
                            <p class="mb-2"><span class="font-semibold">Adres:</span> {{ $student->address ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Plaats:</span> {{ $student->city ?: 'Niet ingevuld' }}</p>
                            <p class="mb-2"><span class="font-semibold">Niveau:</span> {{ ucfirst($student->skill_level ?: 'Beginner') }}</p>
                            <p class="mb-2"><span class="font-semibold">Account aangemaakt:</span> {{ $student->created_at->format('d-m-Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($student->notes)
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <h2 class="text-xl font-bold text-blue-800 mb-2">Notities</h2>
                        <p class="text-gray-700">{{ $student->notes }}</p>
                    </div>
                    @endif
                </div>
                
                <!-- Lessons -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Lessen</h2>
                        <a href="{{ route('admin.students.lessons.create', $student->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus mr-1"></i> Les toevoegen
                        </a>
                    </div>
                    
                    @if($lessons->count() > 0)
                        <div class="overflow-x-auto">
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
                                            Instructeur
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
                                                <div class="text-sm text-gray-500">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $lesson->instructor->user->name }}</div>
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
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('admin.students.lessons.edit', [$student->id, $lesson->id]) }}" class="text-yellow-600 hover:text-yellow-900">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.students.lessons.destroy', [$student->id, $lesson->id]) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze les wilt verwijderen?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $lessons->links() }}
                        </div>
                    @else
                        <div class="bg-gray-50 p-6 rounded-lg text-center">
                            <p class="text-gray-700">Deze klant heeft nog geen lessen geboekt.</p>
                            <a href="{{ route('admin.students.lessons.create', $student->id) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                                Les toevoegen
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

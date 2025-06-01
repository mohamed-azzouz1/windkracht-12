@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Klanten beheren</h1>
                    <a href="{{ route('admin.students.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-plus mr-1"></i> Nieuwe klant
                    </a>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                <!-- Search & Filters -->
                <div class="mb-6">
                    <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/3">
                            <label for="search" class="sr-only">Zoeken</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       class="pl-10 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       placeholder="Zoek op naam, email of telefoon">
                            </div>
                        </div>
                        
                        <div class="w-full md:w-1/4">
                            <label for="status" class="sr-only">Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Alle statussen</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actieve klanten</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactieve klanten</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Filteren
                        </button>
                        
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.students.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
                
                <!-- Students Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Naam
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Adres
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lessen
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acties
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->formatted_date_of_birth }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $student->user->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->phone ?: 'Geen telefoon' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $student->address ?: 'Geen adres' }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->city ?: '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $student->registrations_count ?? $student->registrations()->count() }} lessen
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Niveau: {{ ucfirst($student->skill_level ?: 'Beginner') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.students.show', $student->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Geen klanten gevonden
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(method_exists($students, 'links'))
                    <div class="mt-4">
                        {{ $students->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

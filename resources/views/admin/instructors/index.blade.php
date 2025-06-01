@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Instructeurs</h1>
                <a href="{{ route('admin.instructors.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i>Nieuwe Instructeur
                </a>
            </div>
            
            <!-- Search and filters -->
            <div class="mb-6">
                <form action="{{ route('admin.instructors.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-grow">
                        <input type="text" name="search" placeholder="Zoek op naam..." value="{{ request('search') }}" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <select name="status" class="px-4 py-2 border rounded-lg">
                            <option value="">Alle statussen</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actief</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactief</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-search mr-2"></i>Zoeken
                    </button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('admin.instructors.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Reset
                        </a>
                    @endif
                </form>
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
            
            <!-- Instructors table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($instructors as $instructor)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if(isset($instructor->user) && $instructor->user)
                                            {{ $instructor->user->name }}
                                        @else
                                            <span class="text-red-500">Gebruiker niet gevonden</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        @if(isset($instructor->user) && $instructor->user)
                                            {{ $instructor->user->email }}<br>
                                        @endif
                                        @if($instructor->phone)
                                            Tel: {{ $instructor->phone }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $instructor->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $instructor->is_active ? 'Actief' : 'Inactief' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.instructors.show', $instructor->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-eye"></i> Bekijken
                                    </a>
                                    <a href="{{ route('admin.instructors.edit', $instructor->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Bewerken
                                    </a>
                                    <a href="{{ route('admin.instructors.schedule.day', $instructor->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-calendar-alt"></i> Rooster
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(count($instructors) === 0)
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Geen instructeurs gevonden
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $instructors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

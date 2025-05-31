@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Lessen Overzicht</h1>
                    <a href="{{ route('admin.registrations.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        <i class="fas fa-plus mr-1"></i> Nieuwe Les
                    </a>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                <!-- Search & Filters -->
                <div class="mb-6">
                    <form action="{{ route('admin.registrations.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/4">
                            <label for="search" class="sr-only">Zoeken</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       class="pl-10 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       placeholder="Zoek op naam of e-mail">
                            </div>
                        </div>
                        
                        <div class="w-full md:w-1/6">
                            <label for="status" class="sr-only">Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Alle statussen</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>In afwachting</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Bevestigd</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Geannuleerd</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Voltooid</option>
                            </select>
                        </div>
                        
                        <div class="w-full md:w-1/6">
                            <label for="is_paid" class="sr-only">Betaling</label>
                            <select name="is_paid" id="is_paid" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Alle betalingen</option>
                                <option value="1" {{ request('is_paid') === '1' ? 'selected' : '' }}>Betaald</option>
                                <option value="0" {{ request('is_paid') === '0' ? 'selected' : '' }}>Niet betaald</option>
                            </select>
                        </div>
                        
                        <div class="w-full md:w-1/6">
                            <label for="date_from" class="sr-only">Vanaf datum</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   placeholder="Vanaf datum">
                        </div>
                        
                        <div class="w-full md:w-1/6">
                            <label for="date_to" class="sr-only">Tot datum</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   placeholder="Tot datum">
                        </div>
                        
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Filteren
                        </button>
                        
                        @if(request()->anyFilled(['search', 'status', 'is_paid', 'date_from', 'date_to']))
                            <a href="{{ route('admin.registrations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
                
                <!-- Registrations Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pakket & Locatie
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
                            @forelse($registrations ?? [] as $registration)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration->student->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->package->name }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($registration->location) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->start_date->format('d-m-Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $registration->instructor->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($registration->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($registration->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $registration->is_paid ? 'Betaald' : 'Niet betaald' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.registrations.show', $registration->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.registrations.edit', $registration->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.registrations.destroy', $registration->id) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze les wilt verwijderen?');">
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
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Geen lessen gevonden.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(isset($registrations) && method_exists($registrations, 'links'))
                    <div class="mt-4">
                        {{ $registrations->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

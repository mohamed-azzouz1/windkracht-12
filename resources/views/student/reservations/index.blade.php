@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Kies een lespakket</h1>
                    <a href="{{ route('student.reservations.list') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-list mr-1"></i>Mijn reserveringen
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($packages as $package)
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-blue-600 text-white p-4">
                            <h2 class="text-xl font-bold">{{ $package->name }}</h2>
                            <p class="text-sm text-blue-100">{{ $package->duration_hours }} uur</p>
                        </div>
                        
                        <div class="p-4">
                            <p class="text-gray-700 mb-4">{{ $package->description }}</p>
                            
                            <div class="flex items-center mb-4">
                                <span class="text-2xl font-bold text-blue-600">€{{ number_format($package->price, 2, ',', '.') }}</span>
                                @if($package->original_price > $package->price)
                                <span class="ml-2 text-sm line-through text-gray-500">€{{ number_format($package->original_price, 2, ',', '.') }}</span>
                                @endif
                            </div>
                            
                            <ul class="text-sm text-gray-600 mb-4">
                                <li class="flex items-center mb-1">
                                    <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $package->number_of_sessions }} {{ $package->number_of_sessions > 1 ? 'lessen' : 'les' }}
                                </li>
                                <li class="flex items-center mb-1">
                                    <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Maximaal {{ $package->max_participants }} {{ $package->max_participants > 1 ? 'deelnemers' : 'deelnemer' }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Professionele instructie
                                </li>
                            </ul>
                            
                            <form action="{{ route('student.reservations.create') }}" method="GET">
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                                    Reserveer nu
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

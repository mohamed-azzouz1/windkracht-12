@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Kitesurfles Boeken</h1>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <div class="mb-8 bg-blue-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-blue-800 mb-2">Kies een pakket</h2>
                    <p class="text-gray-600">Selecteer een van onze lespakketten die bij jouw niveau past.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($packages as $package)
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="bg-blue-500 text-white p-4">
                            <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                            <p class="text-sm opacity-90">{{ $package->duration_hours }} uur | {{ $package->number_of_sessions }} {{ $package->number_of_sessions > 1 ? 'lessen' : 'les' }}</p>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-600 mb-4">{{ $package->description }}</p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-2xl font-bold text-gray-800">€{{ number_format($package->price, 2, ',', '.') }}</span>
                                    @if($package->original_price > $package->price)
                                    <span class="text-sm line-through text-gray-500 ml-2">€{{ number_format($package->original_price, 2, ',', '.') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('student.reservations.create', ['package_id' => $package->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                    Selecteren
                                </a>
                            </div>
                            <div class="mt-3 text-sm text-gray-500">
                                <p>Max. {{ $package->max_participants }} {{ $package->max_participants > 1 ? 'deelnemers' : 'deelnemer' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Meer informatie</h3>
                    <p class="text-gray-600 mb-4">Neem contact op als je vragen hebt over onze lespakketten of als je specifieke wensen hebt.</p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Bij een duo-pakket kun je een tweede persoon meenemen voor dezelfde prijs.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Kies je pakket</h1>
                <p class="text-gray-500 mb-6">Selecteer een kitesurfpakket dat het beste bij jou past.</p>
                
                <!-- Individual Packages -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Individuele Lessen</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($regularPackages as $package)
                            <div class="border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                <div class="bg-blue-50 py-3 px-4">
                                    <h3 class="text-lg font-medium text-blue-900">{{ $package->name }}</h3>
                                </div>
                                <div class="p-4">
                                    <div class="mb-4">
                                        <p class="text-gray-600">{{ $package->description }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">Duur:</span> {{ $package->duration_hours }} {{ $package->duration_hours > 1 ? 'uren' : 'uur' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">Geschikt voor:</span> {{ $package->suitable_for }}
                                        </p>
                                    </div>
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-xl font-bold text-blue-600">€{{ number_format($package->price, 2, ',', '.') }}</span>
                                        <form action="{{ route('student.reservations.create') }}" method="GET">
                                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded">
                                                Selecteren
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 py-4 text-center text-gray-500">
                                <p>Geen individuele pakketten beschikbaar.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Duo Packages -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Duo Lessen</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($duoPackages as $package)
                            <div class="border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                <div class="bg-green-50 py-3 px-4">
                                    <h3 class="text-lg font-medium text-green-900">{{ $package->name }}</h3>
                                </div>
                                <div class="p-4">
                                    <div class="mb-4">
                                        <p class="text-gray-600">{{ $package->description }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">Duur:</span> {{ $package->duration_hours }} {{ $package->duration_hours > 1 ? 'uren' : 'uur' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">Geschikt voor:</span> {{ $package->suitable_for }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">Personen:</span> 2 personen
                                        </p>
                                    </div>
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-xl font-bold text-green-600">€{{ number_format($package->price, 2, ',', '.') }}</span>
                                        <form action="{{ route('student.reservations.create') }}" method="GET">
                                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-4 rounded">
                                                Selecteren
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 py-4 text-center text-gray-500">
                                <p>Geen duo pakketten beschikbaar.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

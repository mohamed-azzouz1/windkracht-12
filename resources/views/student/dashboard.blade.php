@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Mijn Reserveringen</h1>
            
            <div class="flex justify-between items-center mb-8">
                <div class="flex space-x-8 border-b border-gray-200 w-full">
                    <a href="{{ route('student.reservations.list', ['tab' => 'upcoming']) }}" 
                       class="{{ request('tab', 'upcoming') === 'upcoming' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700' }} py-4">
                        Aankomende Lessen
                    </a>
                    <a href="{{ route('student.reservations.list', ['tab' => 'past']) }}" 
                       class="{{ request('tab') === 'past' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700' }} py-4">
                        Afgelopen Lessen
                    </a>
                    <a href="{{ route('student.reservations.list', ['tab' => 'cancelled']) }}" 
                       class="{{ request('tab') === 'cancelled' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700' }} py-4">
                        Geannuleerde Lessen
                    </a>
                </div>
                <a href="{{ route('student.reservations.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    <i class="fas fa-plus mr-1"></i>Nieuwe Reservering
                </a>
            </div>
            
            <!-- Example reservation card -->
            <div class="p-4 border rounded-lg shadow-sm hover:bg-gray-50 transition mb-4">
                <div class="flex flex-col md:flex-row justify-between">
                    <div>
                        <h4 class="text-lg font-semibold">Lesse Duo Kiteles</h4>
                        <div class="mt-2 text-gray-600">
                            <p><span class="font-medium">Datum:</span> 09-06-2025 • <span class="font-medium">Tijd:</span> 10:00 - 14:00</p>
                            <p><span class="font-medium">Locatie:</span> Scheveningen • <span class="font-medium">Instructeur:</span> Instructor 4</p>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">In afwachting</span>
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Betaald</span>
                        </div>
                    </div>
                    <div class="flex mt-4 md:mt-0 space-x-2">
                        <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-4 rounded">Details</a>
                        <a href="#" class="bg-red-500 hover:bg-red-600 text-white font-medium py-1.5 px-4 rounded">Annuleren</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

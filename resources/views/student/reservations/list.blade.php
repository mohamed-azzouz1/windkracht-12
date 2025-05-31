@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Mijn Reserveringen</h1>
                    <a href="{{ route('student.reservations.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        <i class="fas fa-plus"></i> Nieuwe Reservering
                    </a>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <ul class="flex">
                        <li class="mr-8">
                            <a href="{{ route('student.reservations.list') }}" class="tab-btn py-4 {{ request('tab', 'upcoming') === 'upcoming' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700' }}">
                                Aankomende Lessen
                            </a>
                        </li>
                        <li class="mr-8">
                            <a href="{{ route('student.reservations.list', ['tab' => 'past']) }}" class="tab-btn py-4 {{ request('tab') === 'past' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700' }}">
                                Afgelopen Lessen
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.reservations.list', ['tab' => 'cancelled']) }}" class="tab-btn py-4 {{ request('tab') === 'cancelled' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700' }}">
                                Geannuleerde Lessen
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Upcoming Lessons Tab -->
                <div id="upcoming-content" class="tab-content {{ request('tab', 'upcoming') === 'upcoming' ? 'block' : 'hidden' }}">
                    @if(count($upcoming) > 0)
                        <div class="space-y-4">
                            @foreach($upcoming as $reservation)
                                <div class="p-4 border rounded-lg shadow-sm hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start">
                                        <div class="mb-4 md:mb-0">
                                            <h4 class="text-lg font-semibold">{{ $reservation->package->name }}</h4>
                                            <div class="mt-2 text-gray-600">
                                                <p><span class="font-medium">Datum:</span> {{ $reservation->start_date->format('d-m-Y') }} • <span class="font-medium">Tijd:</span> {{ $reservation->start_date->format('H:i') }} - {{ $reservation->end_date->format('H:i') }}</p>
                                                <p><span class="font-medium">Locatie:</span> {{ ucfirst($reservation->location) }} • <span class="font-medium">Instructeur:</span> {{ $reservation->instructor->user->name }}</p>
                                            </div>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @if($reservation->status == 'pending')
                                                <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">In afwachting</span>
                                                @else
                                                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Bevestigd</span>
                                                @endif
                                                
                                                @if($reservation->is_paid)
                                                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Betaald</span>
                                                @else
                                                <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Niet betaald</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('student.reservations.show', $reservation->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-4 rounded">
                                                Details
                                            </a>
                                            <a href="{{ route('student.reservations.cancel.form', $reservation->id) }}" class="bg-red-500 hover:bg-red-600 text-white font-medium py-1.5 px-4 rounded">
                                                Annuleren
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen aankomende lessen</h3>
                            <p class="mt-1 text-sm text-gray-500">Je hebt momenteel geen aankomende lessen gepland.</p>
                            <div class="mt-6">
                                <a href="{{ route('student.reservations.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Les Reserveren
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Past Lessons Tab -->
                <div id="past-content" class="tab-content {{ request('tab') === 'past' ? 'block' : 'hidden' }}">
                    @if(count($past) > 0)
                        <div class="space-y-4">
                            @foreach($past as $reservation)
                                <div class="p-4 border rounded-lg shadow-sm hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start">
                                        <div class="mb-4 md:mb-0">
                                            <h4 class="text-lg font-semibold">{{ $reservation->package->name }}</h4>
                                            <div class="mt-2 text-gray-600">
                                                <p><span class="font-medium">Datum:</span> {{ $reservation->start_date->format('d-m-Y') }} • <span class="font-medium">Tijd:</span> {{ $reservation->start_date->format('H:i') }} - {{ $reservation->end_date->format('H:i') }}</p>
                                                <p><span class="font-medium">Locatie:</span> {{ ucfirst($reservation->location) }} • <span class="font-medium">Instructeur:</span> {{ $reservation->instructor->user->name }}</p>
                                            </div>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                    Afgerond
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('student.reservations.show', $reservation->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-4 rounded">
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen afgeronde lessen</h3>
                            <p class="mt-1 text-sm text-gray-500">Je hebt nog geen lessen afgerond.</p>
                            <div class="mt-6">
                                <a href="{{ route('student.reservations.list', ['tab' => 'cancelled']) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-times-circle mr-2 text-red-500"></i> Bekijk geannuleerde lessen
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Cancelled Lessons Tab -->
                <div id="cancelled-content" class="tab-content {{ request('tab') === 'cancelled' ? 'block' : 'hidden' }}">
                    @if(count($cancelled) > 0)
                        <div class="space-y-4">
                            @foreach($cancelled as $reservation)
                                <div class="p-4 border rounded-lg shadow-sm hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start">
                                        <div class="mb-4 md:mb-0">
                                            <h4 class="text-lg font-semibold">{{ $reservation->package->name }}</h4>
                                            <div class="mt-2 text-gray-600">
                                                <p><span class="font-medium">Datum:</span> {{ $reservation->start_date->format('d-m-Y') }} • <span class="font-medium">Tijd:</span> {{ $reservation->start_date->format('H:i') }} - {{ $reservation->end_date->format('H:i') }}</p>
                                                <p><span class="font-medium">Locatie:</span> {{ ucfirst($reservation->location) }} • <span class="font-medium">Instructeur:</span> {{ $reservation->instructor->user->name }}</p>
                                                <p class="mt-1"><span class="font-medium">Reden:</span> {{ $reservation->cancellation_reason ?? 'Geen reden opgegeven' }}</p>
                                                @if($reservation->cancelled_at)
                                                <p class="mt-1"><span class="font-medium">Geannuleerd op:</span> {{ $reservation->cancelled_at->format('d-m-Y H:i') }}</p>
                                                @endif
                                            </div>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Geannuleerd</span>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('student.reservations.show', $reservation->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-4 rounded">
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen geannuleerde lessen</h3>
                            <p class="mt-1 text-sm text-gray-500">Je hebt geen geannuleerde lessen.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabId = btn.getAttribute('data-tab');
                
                // Update URL without reloading page
                const url = new URL(window.location);
                url.searchParams.set('tab', tabId);
                window.history.pushState({}, '', url);
                
                // Update active button styles
                tabBtns.forEach(b => {
                    b.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600', 'font-medium');
                    b.classList.add('text-gray-500');
                });
                btn.classList.add('text-blue-600', 'border-b-2', 'border-blue-600', 'font-medium');
                btn.classList.remove('text-gray-500');
                
                // Show/hide tab content
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(`${tabId}-content`).classList.remove('hidden');
            });
        });
    });
</script>
@endpush
@endsection

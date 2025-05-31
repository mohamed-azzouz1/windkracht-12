@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Lesdetails</h1>
                    <a href="{{ route('instructor.lessons.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar overzicht
                    </a>
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

                <!-- Lesson Details -->
                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold text-blue-800 mb-4">Lesinformatie</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700 mb-2">Algemene informatie</h3>
                            <dl>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Pakket</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lesson->package->name }}</dd>
                                </div>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Datum</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lesson->start_date->format('d-m-Y') }}</dd>
                                </div>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Tijdstip</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lesson->start_date->format('H:i') }} - {{ $lesson->end_date->format('H:i') }}</dd>
                                </div>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Locatie</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($lesson->location ?? 'Niet gespecificeerd') }}</dd>
                                </div>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        @if($lesson->status == 'pending')
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">In afwachting</span>
                                        @elseif($lesson->status == 'confirmed')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Bevestigd</span>
                                        @elseif($lesson->status == 'cancelled')
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Geannuleerd</span>
                                        @elseif($lesson->status == 'completed')
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Voltooid</span>
                                        @endif
                                        
                                        @if($lesson->is_paid)
                                        <span class="ml-2 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Betaald</span>
                                        @else
                                        <span class="ml-2 px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Niet betaald</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-700 mb-2">Student informatie</h3>
                            <dl>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Student</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lesson->student->user->name }}</dd>
                                </div>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lesson->student->user->email }}</dd>
                                </div>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Telefoon</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lesson->student->phone ?? 'Niet opgegeven' }}</dd>
                                </div>
                                <div class="py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Niveau</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if(isset($lesson->kitesurfer) && $lesson->kitesurfer->skill_level)
                                            @if($lesson->kitesurfer->skill_level == 'beginner')
                                                Beginner
                                            @elseif($lesson->kitesurfer->skill_level == 'intermediate')
                                                Gemiddeld
                                            @elseif($lesson->kitesurfer->skill_level == 'advanced')
                                                Gevorderd
                                            @else
                                                {{ $lesson->kitesurfer->skill_level }}
                                            @endif
                                        @else
                                            Onbekend
                                        @endif
                                    </dd>
                                </div>
                                
                                @if($lesson->duo_name)
                                <div class="mt-4 pt-2 border-t border-gray-300">
                                    <h4 class="font-medium text-gray-700">Duo partner</h4>
                                    <div class="py-2 border-b border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500">Naam</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $lesson->duo_name }}</dd>
                                    </div>
                                    <div class="py-2 border-b border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $lesson->duo_email ?? 'Niet opgegeven' }}</dd>
                                    </div>
                                    <div class="py-2 border-b border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500">Telefoon</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $lesson->duo_phone ?? 'Niet opgegeven' }}</dd>
                                    </div>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Cancellation Info -->
                @if($lesson->status === 'cancelled')
                <div class="bg-red-50 p-6 rounded-lg mb-6 border border-red-200">
                    <h2 class="text-xl font-semibold text-red-800 mb-4">Annuleringsinformatie</h2>
                    <dl>
                        <div class="py-2 border-b border-red-100">
                            <dt class="text-sm font-medium text-red-700">Reden voor annulering</dt>
                            <dd class="mt-1 text-sm text-red-900">{{ $lesson->cancellation_reason ?? 'Geen reden opgegeven' }}</dd>
                        </div>
                        @if($lesson->cancelled_at)
                        <div class="py-2 border-b border-red-100">
                            <dt class="text-sm font-medium text-red-700">Geannuleerd op</dt>
                            <dd class="mt-1 text-sm text-red-900">{{ $lesson->cancelled_at->format('d-m-Y H:i') }}</dd>
                        </div>
                        @endif
                        @if($lesson->cancellation_type)
                        <div class="py-2 border-b border-red-100">
                            <dt class="text-sm font-medium text-red-700">Type annulering</dt>
                            <dd class="mt-1 text-sm text-red-900">
                                @if($lesson->cancellation_type == 'weather')
                                    Weersomstandigheden
                                @elseif($lesson->cancellation_type == 'instructor_sick')
                                    Instructeur ziek
                                @elseif($lesson->cancellation_type == 'student_request')
                                    Aangevraagd door student
                                @else
                                    {{ $lesson->cancellation_type }}
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @endif
                
                <!-- Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    @if($lesson->status !== 'cancelled' && $lesson->status !== 'completed')
                    <a href="{{ route('instructor.lessons.cancel.form', $lesson->id) }}" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                        <i class="fas fa-times-circle mr-2"></i>Les annuleren
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

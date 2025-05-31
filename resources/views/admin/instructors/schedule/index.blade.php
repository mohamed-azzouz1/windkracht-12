@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Instructeur Rooster</h1>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i> Terug naar dashboard
                    </a>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-600">Selecteer een instructeur om zijn/haar lesrooster te bekijken.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($instructors as $instructor)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                    {{ substr($instructor->user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $instructor->user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $instructor->user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2 mt-4">
                                <a href="{{ route('admin.instructors.schedule.day', $instructor->id) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded text-sm text-center">
                                    <i class="fas fa-calendar-day mr-1"></i> Dag
                                </a>
                                <a href="{{ route('admin.instructors.schedule.week', $instructor->id) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-3 rounded text-sm text-center">
                                    <i class="fas fa-calendar-week mr-1"></i> Week
                                </a>
                                <a href="{{ route('admin.instructors.schedule.month', $instructor->id) }}" class="flex-1 bg-purple-500 hover:bg-purple-600 text-white py-2 px-3 rounded text-sm text-center">
                                    <i class="fas fa-calendar-alt mr-1"></i> Maand
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

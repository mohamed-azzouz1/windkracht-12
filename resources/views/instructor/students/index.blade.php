@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Mijn Studenten</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('instructor.students.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-user-plus mr-1"></i>Nieuwe Student
                        </a>
                        <a href="{{ route('instructor.lessons.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i>Terug naar lessen
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                <!-- Search and Filters -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('instructor.students.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/3">
                            <label for="search" class="sr-only">Zoeken</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       class="pl-10 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       placeholder="Zoek op naam of email">
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Zoeken
                        </button>
                        @if(request('search'))
                            <a href="{{ route('instructor.students.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
                
                <!-- Students List -->
                @if(isset($students) && $students->count() > 0)
                    <div class="overflow-hidden border border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Student
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contactgegevens
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
                                @foreach($students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-800 font-medium">{{ substr($student->user->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $student->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        @if($student->skill_level)
                                                            Niveau: {{ ucfirst($student->skill_level) }}
                                                        @else
                                                            Niveau: Beginner
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $student->user->email }}</div>
                                            @if($student->phone)
                                                <div class="text-sm text-gray-500">{{ $student->phone }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $student->lessonCount ?? $student->registrations->count() }} lessen
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $student->upcomingCount ?? $student->registrations->where('start_date', '>', now())->where('status', '!=', 'cancelled')->count() }} gepland
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('instructor.students.show', $student->id) }}" class="text-blue-600 hover:text-blue-900">
                                                Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($students) && method_exists($students, 'hasPages') && $students->hasPages())
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Geen studenten gevonden</h3>
                        <p class="mt-1 text-sm text-gray-500">Er zijn nog geen studenten aan jou toegewezen.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

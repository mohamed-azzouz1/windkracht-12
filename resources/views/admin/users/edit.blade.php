@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Gebruikersrol wijzigen</h1>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i> Terug naar gebruikers
                    </a>
                </div>
                
                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- User Info Card -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Gebruiker Informatie</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Naam</p>
                            <p class="font-medium">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">E-mail</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Huidige rol</p>
                            <p class="font-medium">
                                @if($user->is_admin)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Beheerder
                                    </span>
                                @elseif($user->instructor)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Instructeur
                                    </span>
                                @elseif($user->student)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Student
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Geen rol
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Geregistreerd op</p>
                            <p class="font-medium">{{ $user->created_at->format('d-m-Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Role Change Form -->
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Nieuwe rol toewijzen</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center mb-2">
                                    <input type="radio" id="role_student" name="role" value="student" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" 
                                           {{ (!$user->is_admin && !$user->instructor) || $user->student ? 'checked' : '' }}>
                                    <label for="role_student" class="ml-2 text-gray-800 font-medium">Student</label>
                                </div>
                                <p class="text-sm text-gray-500 ml-6">Kan lessen boeken en het eigen profiel beheren.</p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center mb-2">
                                    <input type="radio" id="role_instructor" name="role" value="instructor" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" 
                                           {{ $user->instructor ? 'checked' : '' }}>
                                    <label for="role_instructor" class="ml-2 text-gray-800 font-medium">Instructeur</label>
                                </div>
                                <p class="text-sm text-gray-500 ml-6">Kan lessen geven en studenten beheren.</p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center mb-2">
                                    <input type="radio" id="role_admin" name="role" value="admin" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" 
                                           {{ $user->is_admin ? 'checked' : '' }}>
                                    <label for="role_admin" class="ml-2 text-gray-800 font-medium">Beheerder</label>
                                </div>
                                <p class="text-sm text-gray-500 ml-6">Volledige toegang tot alle functies van het systeem.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional fields for instructor/student -->
                    <div id="additional_fields" class="mb-6 hidden">
                        <div id="instructor_fields" class="border border-gray-200 rounded-lg p-4 mb-4 hidden">
                            <h3 class="font-medium text-gray-800 mb-2">Extra informatie voor instructeur</h3>
                            
                            <div class="mb-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                <input type="text" name="phone" id="phone" value="{{ $user->instructor->phone ?? '' }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biografie</label>
                                <textarea name="bio" id="bio" rows="3" 
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ $user->instructor->bio ?? '' }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Korte beschrijving van de ervaring en specialisaties van de instructeur.</p>
                            </div>
                        </div>
                        
                        <div id="student_fields" class="border border-gray-200 rounded-lg p-4 mb-4 hidden">
                            <h3 class="font-medium text-gray-800 mb-2">Extra informatie voor student</h3>
                            
                            <div class="mb-3">
                                <label for="student_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                <input type="text" name="student_phone" id="student_phone" value="{{ $user->student->phone ?? '' }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded">
                            Rol opslaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const additionalFields = document.getElementById('additional_fields');
    const instructorFields = document.getElementById('instructor_fields');
    const studentFields = document.getElementById('student_fields');
    
    function toggleFields() {
        const selectedRole = document.querySelector('input[name="role"]:checked').value;
        
        additionalFields.classList.remove('hidden');
        
        if (selectedRole === 'instructor') {
            instructorFields.classList.remove('hidden');
            studentFields.classList.add('hidden');
        } else if (selectedRole === 'student') {
            instructorFields.classList.add('hidden');
            studentFields.classList.remove('hidden');
        } else {
            additionalFields.classList.add('hidden');
        }
    }
    
    // Initial state
    toggleFields();
    
    // Event listeners
    roleInputs.forEach(input => {
        input.addEventListener('change', toggleFields);
    });
});
</script>
@endsection

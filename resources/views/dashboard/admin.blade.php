@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <h1 class="text-2xl font-bold text-blue-900 mb-6">Admin Dashboard</h1>
            
            <!-- Welcome Message -->
            <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <p class="text-lg font-medium text-blue-800">Welkom terug, {{ Auth::user()->name }}!</p>
                <p class="text-gray-600">Hier vind je een overzicht van alle beheerdersfuncties.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- Admin Stats -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-lg shadow-md text-white">
                    <h3 class="text-xl font-bold mb-4">Systeem Overzicht</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Totaal Gebruikers</p>
                            <p class="text-2xl font-bold">{{ $totalUsers ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Actieve Instructeurs</p>
                            <p class="text-2xl font-bold">{{ $activeInstructors ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Totaal Studenten</p>
                            <p class="text-2xl font-bold">{{ $totalStudents ?? 0 }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-lg">
                            <p class="text-sm opacity-80">Lessen Geboekt</p>
                            <p class="text-2xl font-bold">{{ $totalLessons ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="border border-gray-200 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-history mr-2 text-blue-600"></i>Recente Activiteit
                    </h3>
                    
                    <div class="max-h-64 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-blue-200 scrollbar-track-gray-100">
                        <div class="space-y-3">
                            @if(isset($recentActivities) && count($recentActivities) > 0)
                                @foreach($recentActivities as $activity)
                                    <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded-r-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-blue-800">{{ $activity->title }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    <i class="far fa-clock mr-1"></i>{{ $activity->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="bg-white px-2 py-1 rounded text-xs font-medium text-blue-700">
                                                {{ $activity->type }}
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600">{{ $activity->message }}</p>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500">Geen recente activiteiten</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Tools -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Beheerder Tools</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="/admin/accounts" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mb-2">
                            <i class="fas fa-users text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Account Overzicht</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full mb-2">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Lessen Beheer</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full mb-2">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Financiën</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="h-12 w-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mb-2">
                            <i class="fas fa-cog text-xl"></i>
                        </span>
                        <span class="text-sm font-medium text-center">Instellingen</span>
                    </a>
                </div>
            </div>
            
            <!-- System Info -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- User Distribution -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Gebruikers Verdeling</h3>
                    <div class="flex items-center h-40 justify-center">
                        <!-- Placeholder for chart -->
                        <div class="flex space-x-4">
                            <div class="text-center">
                                <div class="w-16 h-36 bg-gradient-to-t from-blue-500 to-blue-300 rounded-t-lg relative">
                                    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-blue-600 rounded-t-lg"></div>
                                </div>
                                <p class="text-xs mt-1 font-medium">Admins</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-36 bg-gradient-to-t from-indigo-500 to-indigo-300 rounded-t-lg relative">
                                    <div class="absolute inset-x-0 bottom-0 h-1/3 bg-indigo-600 rounded-t-lg"></div>
                                </div>
                                <p class="text-xs mt-1 font-medium">Instructeurs</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-36 bg-gradient-to-t from-green-500 to-green-300 rounded-t-lg relative">
                                    <div class="absolute inset-x-0 bottom-0 h-4/5 bg-green-600 rounded-t-lg"></div>
                                </div>
                                <p class="text-xs mt-1 font-medium">Studenten</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions Panel -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Snelle Acties</h3>
                    <div class="space-y-3">
                        <a href="#" class="flex items-center justify-between px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                                <span class="font-medium">Nieuwe Gebruiker Aanmaken</span>
                            </span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="#" class="flex items-center justify-between px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-download text-green-600 mr-3"></i>
                                <span class="font-medium">Rapportage Downloaden</span>
                            </span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="#" class="flex items-center justify-between px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-envelope text-purple-600 mr-3"></i>
                                <span class="font-medium">Email aan Alle Gebruikers</span>
                            </span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Gebruiker Details</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.accounts.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded">
                            <i class="fas fa-edit mr-1"></i>Bewerken
                        </a>
                        
                        @if($user->role->name !== 'admin')
                        <form method="POST" action="{{ route('admin.accounts.change-role', $user->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="role" value="{{ $user->role->name === 'student' ? 'instructor' : 'student' }}" 
                                    class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                                <i class="fas fa-exchange-alt mr-1"></i>
                                Wijzig naar {{ $user->role->name === 'student' ? 'Instructeur' : 'Student' }}
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('admin.accounts.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i>Terug naar overzicht
                        </a>
                    </div>
                </div>

                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <!-- User Information -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Gebruiker Informatie</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Account details en instellingen.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Volledige naam</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Email adres</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Rol</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                          bg-{{ $user->role->name === 'admin' ? 'red' : ($user->role->name === 'instructor' ? 'blue' : 'green') }}-100 
                                          text-{{ $user->role->name === 'admin' ? 'red' : ($user->role->name === 'instructor' ? 'blue' : 'green') }}-800">
                                        {{ ucfirst($user->role->name) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Aangemaakt op</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->created_at->format('d-m-Y H:i') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Laatst bijgewerkt</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->updated_at->format('d-m-Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Profile Information (if available) -->
                @if($profile)
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ ucfirst($user->role->name) }} Profiel</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Aanvullende profiel informatie.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @if($user->role->name === 'student')
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Geboortedatum</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->date_of_birth }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Adres</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->address ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Woonplaats</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->city ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Mobiel</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->phone ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Ervaringsniveau</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($profile->skill_level) }}</dd>
                            </div>
                            @if($profile->notes)
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Notities</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->notes }}</dd>
                            </div>
                            @endif
                            @elseif($user->role->name === 'instructor')
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Geboortedatum</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->date_of_birth ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Adres</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->address ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Woonplaats</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->city ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">BSN-nummer</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->bsn ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Mobiel</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->phone ?? 'Niet ingevuld' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Certificering</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->certification }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Jaren ervaring</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $profile->years_of_experience }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                          bg-{{ $profile->is_active ? 'green' : 'red' }}-100 
                                          text-{{ $profile->is_active ? 'green' : 'red' }}-800">
                                        {{ $profile->is_active ? 'Actief' : 'Inactief' }}
                                    </span>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
                @else
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ ucfirst($user->role->name) }} Profiel</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Geen profiel informatie beschikbaar.</p>
                    </div>
                    <div class="border-t border-gray-200 p-6 text-center">
                        <p class="text-gray-500">Deze gebruiker heeft nog geen profiel aangemaakt.</p>
                        
                        @if($user->role->name === 'student' || $user->role->name === 'instructor')
                        <a href="{{ route('admin.accounts.create-profile', $user->id) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25">
                            Profiel Aanmaken
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Lesson Registrations (for students) -->
                @if($user->role->name === 'student' && isset($registrations) && count($registrations) > 0)
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Lespakketten</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Geboekte lessen en pakketten.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakket</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructeur</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Betaalstatus</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registrations as $registration)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->package->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $registration->start_date->format('d-m-Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $registration->start_date->format('H:i') }} - {{ $registration->end_date->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $registration->instructor ? $registration->instructor->user->name : 'Nog niet toegewezen' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                              bg-{{ $registration->status === 'completed' ? 'green' : ($registration->status === 'confirmed' ? 'blue' : ($registration->status === 'cancelled' ? 'red' : 'yellow')) }}-100 
                                              text-{{ $registration->status === 'completed' ? 'green' : ($registration->status === 'confirmed' ? 'blue' : ($registration->status === 'cancelled' ? 'red' : 'yellow')) }}-800">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                              bg-{{ $registration->is_paid ? 'green' : 'red' }}-100 
                                              text-{{ $registration->is_paid ? 'green' : 'red' }}-800">
                                            {{ $registration->is_paid ? 'Betaald' : 'Niet betaald' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Details</a>
                                        
                                        @if(!$registration->is_paid)
                                        <form method="POST" action="{{ route('admin.registrations.mark-as-paid', $registration->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                Markeer als betaald
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

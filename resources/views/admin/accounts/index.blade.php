@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Gebruikers Beheer</h2>
                    <a href="{{ route('admin.accounts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        <i class="fas fa-plus-circle mr-1"></i>Nieuwe Gebruiker
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

                <!-- Search and Filter -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('admin.accounts.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <input type="text" name="search" placeholder="Zoek op naam of email" class="w-full px-4 py-2 border rounded-lg" value="{{ request('search') }}">
                        </div>
                        <div class="w-full md:w-48">
                            <select name="role" class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Alle rollen</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                                <i class="fas fa-search mr-1"></i>Zoeken
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Naam</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-left">Rol</th>
                                <th class="py-3 px-6 text-left">Aangemaakt op</th>
                                <th class="py-3 px-6 text-center">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            @foreach($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left">{{ $user->id }}</td>
                                <td class="py-3 px-6 text-left">{{ $user->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                                <td class="py-3 px-6 text-left">
                                    @if($user->role)
                                    <span class="bg-{{ $user->role->name === 'admin' ? 'red' : ($user->role->name === 'instructor' ? 'blue' : 'green') }}-100 
                                          text-{{ $user->role->name === 'admin' ? 'red' : ($user->role->name === 'instructor' ? 'blue' : 'green') }}-800 
                                          py-1 px-3 rounded-full text-xs">
                                        {{ ucfirst($user->role->name) }}
                                    </span>
                                    @else
                                    <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-full text-xs">
                                        Geen rol
                                    </span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">{{ $user->created_at->format('d-m-Y') }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="{{ route('admin.accounts.show', $user->id) }}" class="w-4 mr-4 transform hover:text-blue-500 hover:scale-110 transition-all" title="Bekijken">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accounts.edit', $user->id) }}" class="w-4 mr-4 transform hover:text-yellow-500 hover:scale-110 transition-all" title="Bewerken">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.accounts.destroy', $user->id) }}" 
                                              onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-4 transform hover:text-red-500 hover:scale-110 transition-all" title="Verwijderen">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

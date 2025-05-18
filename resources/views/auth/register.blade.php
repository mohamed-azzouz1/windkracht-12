@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-xl shadow-lg p-6 sm:p-10">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-blue-900">
                Maak een account aan
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Of
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    log in op je bestaande account
                </a>
            </p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="rounded-md -space-y-px">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                    <input id="name" name="name" type="text" autocomplete="name" required 
                        value="{{ old('name') }}"
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 
                        placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none 
                        focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm
                        @error('name') border-red-500 @enderror"
                        placeholder="Jouw naam">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        value="{{ old('email') }}"
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 
                        placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none 
                        focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm
                        @error('email') border-red-500 @enderror"
                        placeholder="naam@voorbeeld.nl">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Wachtwoord</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 
                        placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none 
                        focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm
                        @error('password') border-red-500 @enderror"
                        placeholder="••••••••">
                </div>
                
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Bevestig Wachtwoord</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 
                        placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none 
                        focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent 
                    text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 
                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors
                    sm:text-base">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Registreren
                </button>
            </div>
        </form>
        
        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">
                Door te registreren ga je akkoord met onze 
                <a href="#" class="text-blue-600 hover:underline">Servicevoorwaarden</a> en 
                <a href="#" class="text-blue-600 hover:underline">Privacybeleid</a>.
            </p>
        </div>
    </div>
</div>
@endsection

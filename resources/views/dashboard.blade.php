@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-blue-900 mb-4">Dashboard</h1>
        
        <div class="mb-6">
            <p class="text-gray-700">Welkom {{ Auth::user()->name }}, u bent succesvol ingelogd.</p>
        </div>
        
        <div class="bg-blue-50 p-4 rounded-lg">
            <h2 class="text-xl font-bold text-blue-900 mb-2">Uw Boekingen</h2>
            <p class="text-gray-700">U heeft momenteel geen actieve boekingen.</p>
            <a href="#pricing" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                Bekijk Lespakketten
            </a>
        </div>
    </div>
</div>
@endsection

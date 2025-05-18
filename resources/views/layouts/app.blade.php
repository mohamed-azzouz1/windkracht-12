<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Windkracht 12') }}</title>
    
    <!-- Load Tailwind directly from CDN to avoid build issues -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold">Windkracht 12</a>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="/" class="hover:text-blue-300 transition">Home</a>
                <a href="/#about" class="hover:text-blue-300 transition">Over Ons</a>
                <a href="/#pricing" class="hover:text-blue-300 transition">Lessen</a>
                <a href="/#contact" class="hover:text-blue-300 transition">Contact</a>
            </div>
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-300 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Register</a>
                @else
                    <span class="text-white">{{ Auth::user()->name }}</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
            <div class="md:hidden">
                <button class="focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-blue-900 text-white py-6">
        <div class="container mx-auto px-4">
            <p class="text-center">&copy; {{ date('Y') }} Windkracht 12. Alle rechten voorbehouden.</p>
        </div>
    </footer>

    <!-- Custom Scrollbar Styles -->
    <style>
        /* Customize scrollbar for webkit browsers */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 10px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #818cf8;
        }
        
        /* For Firefox */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #c7d2fe #f1f1f1;
        }
    </style>
</body>
</html>

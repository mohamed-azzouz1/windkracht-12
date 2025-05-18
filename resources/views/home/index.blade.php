<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Windkracht 12 - Kitesurfschool aan de Kust</title>
    
    <!-- Load Tailwind directly from CDN to avoid build issues -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Enhanced CSS for animations and interaction without JS -->
    <style>
        /* Animation keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Applied animations */
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-bounce {
            animation: bounce 2s infinite;
        }
        
        /* CSS-only hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
        
        .hover\:-translate-y-1:hover {
            transform: translateY(-0.25rem);
            transition: transform 0.3s ease;
        }
        
        /* CSS-only accordion for mobile navigation */
        #mobile-menu-toggle:checked ~ .mobile-menu {
            display: block;
            max-height: 300px;
        }
        
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        /* Fixed background image loading */
        .hero-image {
            background-image: url('https://source.unsplash.com/featured/1600x900/?kitesurfing,ocean');
            background-size: cover;
            background-position: center;
        }
        
        /* Make image cards pulse on hover */
        .card-hover:hover {
            animation: pulse 1.5s infinite;
        }
        
        /* Smooth scrolling without JS */
        html {
            scroll-behavior: smooth;
        }
        
        /* Mobile menu toggle functionality with pure CSS */
        #mobile-menu-button:focus + #mobile-menu,
        #mobile-menu:hover {
            display: block;
        }
    </style>
    
    <!-- Only include Vite if it exists in the project -->
    @if(file_exists(public_path('build/assets/app.js')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold">Windkracht 12</h1>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="#home" class="hover:text-blue-300 transition">Home</a>
                <a href="#about" class="hover:text-blue-300 transition">Over Ons</a>
                <a href="#pricing" class="hover:text-blue-300 transition">Lessen</a>
                <a href="#contact" class="hover:text-blue-300 transition">Contact</a>
            </div>
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-300 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Register</a>
                @else
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
                <button class="focus:outline-none" id="mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile menu dropdown -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-4 py-3 space-y-2 bg-blue-800">
                <a href="#home" class="block hover:text-blue-300 transition py-2">Home</a>
                <a href="#about" class="block hover:text-blue-300 transition py-2">Over Ons</a>
                <a href="#pricing" class="block hover:text-blue-300 transition py-2">Lessen</a>
                <a href="#contact" class="block hover:text-blue-300 transition py-2">Contact</a>
                
                <div class="pt-2 mt-2 border-t border-blue-700">
                    @guest
                        <a href="{{ route('login') }}" class="block hover:text-blue-300 transition py-2">Login</a>
                        <a href="{{ route('register') }}" class="block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-center mt-2">Register</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block hover:text-blue-300 transition py-2">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-center mt-2">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="home" class="relative bg-gradient-to-r from-blue-900 to-blue-700 text-white">
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ asset('img/kitesurf-hero.jpg') }}" alt="Kitesurfing" class="w-full h-full object-cover opacity-40" onerror="this.src='https://source.unsplash.com/featured/1600x900/?kitesurfing,ocean'">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900 via-blue-800 to-transparent opacity-70"></div>
        </div>
        
        <div class="container mx-auto px-4 py-32 relative z-10">
            <div class="max-w-2xl animate-fade-in-up">
                <span class="inline-block bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold mb-4">NEDERLAND'S BESTE KITESURFSCHOOL</span>
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Windkracht 12</h1>
                <h2 class="text-2xl md:text-3xl mb-6 text-blue-200">DÉ Kitesurfschool aan de Nederlandse Kust!</h2>
                <p class="text-xl mb-8 text-white leading-relaxed">
                    Leer kitesurfen onder professionele begeleiding in een veilige omgeving.
                    <span class="block mt-2">Ervaar de ultieme vrijheid op het water met Windkracht 12!</span>
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#pricing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition transform hover:scale-105 hover:shadow-lg">
                        <span class="flex items-center">
                            <span>Bekijk Onze Lessen</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </span>
                    </a>
                    <a href="#contact" class="bg-transparent hover:bg-white hover:text-blue-900 text-white font-bold py-3 px-8 rounded-lg border-2 border-white transition transform hover:scale-105">
                        <span class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>Contact</span>
                        </span>
                    </a>
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 text-center pb-8 animate-bounce hidden md:block">
                <a href="#about" class="text-white text-3xl hover:text-blue-300 transition">
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-blue-900">Over Windkracht 12</h2>
            <p class="text-xl text-center mb-12 text-gray-600 max-w-3xl mx-auto">Al 10+ jaar de meest ervaren en enthousiaste kitesurfschool aan de Nederlandse kust</p>
            
            <!-- About content with split layout -->
            <div class="grid md:grid-cols-2 gap-12 mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-4">Ontdek de vrijheid van kitesurfen</h3>
                    <p class="text-lg mb-6 text-gray-700">
                        Wil jij kitesurfen leren onder professionele begeleiding in een veilige omgeving? Bij Windkracht 12 draait alles om jouw passie voor kitesurfen en de kracht van de natuur. Of je nu voor het eerst een kite vasthoudt of je skills naar een hoger niveau wilt tillen — wij staan voor je klaar.
                    </p>
                    <p class="text-lg mb-6 text-gray-700">
                        Onze ervaren instructeurs zorgen voor een persoonlijke aanpak en maximale veiligheid, zodat jij met vertrouwen het water op kunt. Met de nieuwste materialen en technieken leer je snel en efficiënt de basis van het kitesurfen.
                    </p>
                    
                    <div class="border-l-4 border-blue-600 pl-4 mb-8 italic text-gray-600">
                        "Windkracht 12 heeft mij in slechts drie dagen leren kitesurfen. De instructeurs zijn geduldig, kundig en maken er een geweldige ervaring van!" - <span class="font-semibold">Lisa, 28</span>
                    </div>
                    
                    <a href="#pricing" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition hover:shadow-lg mt-4">
                        <span class="flex items-center">
                            <span>Bekijk Onze Lespakketten</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </span>
                    </a>
                </div>
                
                <!-- Main feature image with overlay design -->
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-900 rounded-lg transform translate-x-4 translate-y-4 opacity-20"></div>
                    <img src="{{ asset('img/kitesurf-1.jpg') }}" alt="Kitesurfing in Action" class="rounded-lg shadow-xl relative z-10 w-full h-full object-cover" onerror="this.src='https://source.unsplash.com/random/800x600/?kitesurfing,action'">
                </div>
            </div>
            
            <!-- Photo gallery with 3 images -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-16">
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img src="{{ asset('img/kitesurf-2.jpg') }}" alt="Kitesurfing Lesson on Beach" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://source.unsplash.com/random/600x400/?kitesurfing,beach'">
                    <div class="p-4 bg-white">
                        <h4 class="font-bold text-blue-900">Lessen op het strand</h4>
                        <p class="text-sm text-gray-600">Eerste stappen op veilig terrein</p>
                    </div>
                </div>
                
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img src="{{ asset('img/kitesurf-3.jpg') }}" alt="Kitesurfing Water Practice" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://source.unsplash.com/random/600x400/?kitesurfing,water'">
                    <div class="p-4 bg-white">
                        <h4 class="font-bold text-blue-900">Praktijk op het water</h4>
                        <p class="text-sm text-gray-600">Leer op een veilige manier het water op</p>
                    </div>
                </div>
                
                <div class="overflow-hidden rounded-lg shadow-lg group">
                    <img src="{{ asset('img/kitesurf-4.jpg') }}" alt="Kitesurfing Jump" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://source.unsplash.com/random/600x400/?kitesurfing,jump'">
                    <div class="p-4 bg-white">
                        <h4 class="font-bold text-blue-900">Gevorderde technieken</h4>
                        <p class="text-sm text-gray-600">Leer springen en andere coole tricks</p>
                    </div>
                </div>
            </div>
            
            <!-- Features in a nicer grid -->
            <div class="bg-blue-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-blue-900 mb-8 text-center">Waarom kiezen voor onze lessen?</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-certificate text-xl"></i>
                        </div>
                        <h3 class="font-bold text-blue-900 text-center mb-2">Gecertificeerde Instructeurs</h3>
                        <p class="text-sm text-gray-700 text-center">IKO-gecertificeerde professionals met jarenlange ervaring</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-life-ring text-xl"></i>
                        </div>
                        <h3 class="font-bold text-blue-900 text-center mb-2">Veiligheid Voorop</h3>
                        <p class="text-sm text-gray-700 text-center">Moderne veiligheidsuitrusting en gecontroleerde leeromgeving</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <h3 class="font-bold text-blue-900 text-center mb-2">Kleine Groepen</h3>
                        <p class="text-sm text-gray-700 text-center">Maximaal 2 studenten per instructeur voor persoonlijke aandacht</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <h3 class="font-bold text-blue-900 text-center mb-2">Perfecte Locatie</h3>
                        <p class="text-sm text-gray-700 text-center">Ideale wind- en watercondities aan de Nederlandse kust</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-blue-900">Onze Lespakketten</h2>
            <p class="text-xl text-center mb-12 text-gray-600">Voor elk niveau de perfecte les</p>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $packages = [
                        [
                            'title' => 'Materiaal huur',
                            'subtitle' => '2,5 uur kitesurfen',
                            'orig_price' => '190',
                            'price' => '175',
                            'bg_color' => 'bg-blue-800',
                            'hover_color' => 'hover:bg-blue-900',
                            'features' => [
                                'Privéles 2,5 uur',
                                'EÉN persoon per les',
                                '1 dagdeel'
                            ],
                            'cta' => 'Ja! Ik wil materiaal huren',
                            'button_text' => 'Ja! Ik wil materiaal huren'
                        ],
                        [
                            'title' => 'Lesse Duo Kiteles',
                            'subtitle' => 'Op het board in 1 dagen!',
                            'orig_price' => '200',
                            'price' => '135',
                            'bg_color' => 'bg-blue-700',
                            'hover_color' => 'hover:bg-blue-800',
                            'features' => [
                                '3,5 uur',
                                'Maximaal 2 personen per les',
                                '1 dagdeel'
                            ],
                            'cta' => 'Boek de 1 kiteles',
                            'button_text' => 'Boek de 1 kiteles'
                        ],
                        [
                            'title' => 'Kitesurf Duo lespakket 3 lessen',
                            'subtitle' => 'Op het board in 3 dagen!',
                            'orig_price' => '500',
                            'price' => '375',
                            'bg_color' => 'bg-blue-600',
                            'hover_color' => 'hover:bg-blue-700',
                            'features' => [
                                '10,5 uur',
                                '€ 375,- per persoon inclusief materialen',
                                'Maximaal 2 personen per les',
                                '3 dagdelen'
                            ],
                            'cta' => 'Boek de 3 dagen',
                            'button_text' => 'Boek de 3 dagen'
                        ],
                        [
                            'title' => 'Kitesurf Duo lespakket 5 lessen',
                            'subtitle' => 'Op het board in 5 dagen!',
                            'orig_price' => '800',
                            'price' => '675',
                            'bg_color' => 'bg-blue-500',
                            'hover_color' => 'hover:bg-blue-600',
                            'features' => [
                                '17,5 uur',
                                'Maximaal 2 personen per les',
                                '5 dagdelen'
                            ],
                            'cta' => 'Boek de 5 dagen',
                            'button_text' => 'Boek de 5 dagen'
                        ]
                    ];
                @endphp

                @foreach($packages as $package)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transition transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="{{ $package['bg_color'] }} text-white py-4 px-6">
                        <h3 class="text-xl font-bold">{{ $package['title'] }}</h3>
                        <p class="text-sm">{{ $package['subtitle'] }}</p>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <span class="text-gray-400 text-sm line-through">€ {{ $package['orig_price'] }},-</span>
                            <p class="text-3xl font-bold text-blue-900">€ {{ $package['price'] }},-</p>
                            <p class="text-sm text-gray-600">inclusief alle materialen</p>
                        </div>
                        <ul class="mb-8 space-y-2">
                            @foreach($package['features'] as $feature)
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> {{ $feature }}</li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center {{ $package['bg_color'] }} {{ $package['hover_color'] }} text-white font-bold py-2 rounded transition">
                            {{ $package['button_text'] }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-16 bg-blue-900 text-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Waarom kiezen voor Windkracht 12?</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $features = [
                        [
                            'icon' => 'fas fa-shield-alt',
                            'title' => 'Gecertificeerde en ervaren instructeurs die veiligheid en plezier voorop zetten.',
                            'description' => 'Onze instructeurs zijn allemaal gecertificeerd en hebben jarenlange ervaring.'
                        ],
                        [
                            'icon' => 'fas fa-users',
                            'title' => 'Les in kleine groepen voor de beste persoonlijke aandacht en de optimale leerervaring.',
                            'description' => 'Maximaal 2 studenten per instructeur voor optimale begeleiding.'
                        ],
                        [
                            'icon' => 'fas fa-map-marker-alt',
                            'title' => 'Perfecte locatie met ideale wind- en watercondities voor beginners en gevorderden.',
                            'description' => 'Onze locatie biedt de perfecte omstandigheden voor zowel beginners als gevorderden.'
                        ]
                    ];
                @endphp

                @foreach($features as $feature)
                <div class="bg-blue-800 rounded-lg p-6 hover:bg-blue-700 transition">
                    <i class="{{ $feature['icon'] }} text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-3">{{ $feature['title'] }}</h3>
                    <p class="text-blue-100">{{ $feature['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-blue-900">Denk jij het ook?</h2>
            
            <div class="max-w-3xl mx-auto">
                <div class="mb-8 bg-gray-50 rounded-lg p-6">
                    <p class="text-lg mb-4 text-gray-700">
                        "Bij ons draait het niet alleen om leren kitesurfen, maar om een beleving die je nooit meer vergeet. De wind in je gezicht, de spanning van de eerste keer opstaan op het board en het gevoel van vrijheid dat kitesurfen je geeft. Windkracht 12 is er voor iedereen — van beginner tot pro. Boek vandaag nog jouw les en ontdek waarom de wind altijd harder waait bij Windkracht 12!"
                    </p>
                    <p class="font-bold text-blue-900">- Team Windkracht 12</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-blue-900">Neem Contact Op</h2>
            
            <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12">
                <div>
                    <form action="#" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-gray-700 mb-2">Naam</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 mb-2">E-mail</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="package" class="block text-gray-700 mb-2">Gewenst Pakket</label>
                            <select id="package" name="package" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('package') border-red-500 @enderror">
                                <option value="">Selecteer een pakket</option>
                                @foreach($packages as $index => $package)
                                <option value="{{ $package['title'] }}" {{ old('package') == $package['title'] ? 'selected' : '' }}>{{ $package['title'] }}</option>
                                @endforeach
                            </select>
                            @error('package')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="message" class="block text-gray-700 mb-2">Bericht</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition w-full">
                            Verstuur Bericht
                        </button>
                    </form>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-blue-900">Contactgegevens</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold">Adres</h4>
                                <p class="text-gray-700">Strandopgang 12, Noordwijk aan Zee</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-phone text-blue-600 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold">Telefoon</h4>
                                <p class="text-gray-700">+31 (0)6 123 45 678</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-blue-600 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold">Email</h4>
                                <p class="text-gray-700">info@windkracht12.nl</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-clock text-blue-600 mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-bold">Openingstijden</h4>
                                <p class="text-gray-700">Maandag t/m zondag: 9:00 - 18:00</p>
                                <p class="text-gray-700">(Afhankelijk van windcondities)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="font-bold mb-2">Volg ons</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook-f text-xl"></i></a>
                            <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-instagram text-xl"></i></a>
                            <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-youtube text-xl"></i></a>    <!-- No JS needed - using pure CSS for animations and effects -->
                        </div>
                    </div>
                </div>            </div>        </div>    </section>    <!-- Footer -->    <footer class="bg-blue-900 text-white py-8">        <div class="container mx-auto px-4">            <div class="md:flex justify-between">                <div class="mb-6 md:mb-0">                    <h2 class="text-2xl font-bold mb-4">Windkracht 12</h2>                    <p class="max-w-xs">DÉ Kitesurfschool aan de Nederlandse kust voor beginners en gevorderden.</p>                </div>                                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">                    <div>                        <h3 class="text-lg font-bold mb-4">Links</h3>                        <ul class="space-y-2">                            <li><a href="#home" class="hover:text-blue-300">Home</a></li>                            <li><a href="#about" class="hover:text-blue-300">Over Ons</a></li>                            <li><a href="#pricing" class="hover:text-blue-300">Lessen</a></li>                            <li><a href="#contact" class="hover:text-blue-300">Contact</a></li>                        </ul>                    </div>                                        <div>                        <h3 class="text-lg font-bold mb-4">Informatie</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-blue-300">Voorwaarden</a></li>
                            <li><a href="#" class="hover:text-blue-300">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-blue-300">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-blue-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} Windkracht 12. Alle rechten voorbehouden.</p>
                <div class="mt-4 md:mt-0">
                    <!-- Replace external image with inline SVG to avoid loading errors -->
                    <div class="h-8 flex items-center space-x-2">
                        <span class="text-white text-xs">Betaalmethoden:</span>
                        <svg class="h-6 w-auto" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg">
                            <g fill="none" fill-rule="evenodd">
                                <rect fill="#FFF" width="38" height="24" rx="3"/>
                                <path d="M1 19.001h36M1 5h36" stroke="#E6E6E6"/>
                                <circle fill="#F7B600" cx="15.6" cy="12" r="6.6"/>
                                <path d="M15.6 5.4a6.6 6.6 0 1 0 0 13.2A6.6 6.6 0 0 0 15.6 5.4z" fill="#F7B600"/>
                                <path d="M15.6 5.4a6.6 6.6 0 1 0 0 13.2A6.6 6.6 0 0 0 15.6 5.4z" stroke="#E6E6E6"/>
                                <path d="M15.6 5.4a6.6 6.6 0 1 0 0 13.2A6.6 6.6 0 0 0 15.6 5.4z" stroke="#E6E6E6"/>
                                <path d="M15.6 5.4a6.6 6.6 0 1 0 0 13.2A6.6 6.6 0 0 0 15.6 5.4z" stroke="#E6E6E6"/>
                                <path d="M15.6 5.4a6.6 6.6 0 1 0 0 13.2A6.6 6.6 0 0 0 15.6 5.4z" stroke="#E6E6E6"/>
                            </g>
                        </svg>
                        <svg class="h-6 w-auto

                            <g fill="none" fill-rule="evenodd">
                                <path d="M1 19.001h36M1 5h36" stroke="#E6E6E6"/>
                                <circle fill="#F7B600" cx="15.6" cy="12" r="6.6"/>
                                <path d="M15.6 5.4a6.6 6.6 0 1 0 0 13.2A6.6 6.6 0 0 0 15.6 5.4z" fill="#F7B600"/>
                            </g>
                            <path d="M1 19.001h36M1 5h36" stroke="#E6E6E6"/>
" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg">
                            <g fill="none" fill-rule="evenodd">
                                <path d="M1 19.001h36M1 5h36" stroke="#E6E6E6"/>
                                <circle fill="#F7B600" cx="15.6" cy="12" r="6.6"/>
                                <path d="M15.6 5.4a6.6 6.6 0 1 0 0 13.2A6.6 6.6 0 0 0 15.6 5.4z" fill="#F7B600"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
        // Fade-in effect for elements
        const fadeInElements = document.querySelectorAll('.animate-fade-in-up');
        fadeInElements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('opacity-100');
            }, index * 200);
        });
        // Bounce effect for the down arrow
        const bounceArrow = document.querySelector('.animate-bounce');
        setInterval(() => {
            bounceArrow.classList.toggle('opacity-50');
        }, 1000);
    </script>
    <style>
        /* Custom animations */
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.5s ease-out forwards;
        }
        .animate-bounce {
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        /* Custom styles */
        .bg-gradient-to-r {
            background: linear-gradient(to right, #1e3a8a, #2563eb);
        }
        .bg-gradient-to-r:hover {
            background: linear-gradient(to right, #1e3a8a, #3b82f6);
        }
        .bg-blue-800 {
            background-color: #1e3a8a;
        }
        .bg-blue-700 {
            background-color: #2563eb;
        }
        .bg-blue-600 {
            background-color: #3b82f6;
        }
        .bg-blue-500 {
            background-color: #60a5fa;
        }
        .bg-blue-400 {
            background-color: #93c5fd;
        }
        .bg-blue-300 {
            background-color: #bfdbfe;
        }
        .bg-blue-200 {
            background-color: #dbeafe;
        }
        .bg-blue-100 {
            background-color: #f0f9ff;
        }
        .bg-blue-50 {
            background-color: #f9fafb;
        }
        .text-blue-900 {
            color: #1e3a8a;
        }
        .text-blue-800 {
            color: #1e40af;
        }
        .text-blue-700 {
            color: #1d4ed8;
        }   
        .text-blue-600 {
            color: #2563eb;
        }
        .text-blue-500 {
            color: #3b82f6;
        }
        .text-blue-400 {
            color: #60a5fa;
        }
        .text-blue-300 {
            color: #93c5fd;
        }
        .text-blue-200 {
            color: #bfdbfe;
        }
        .text-blue-100 {
            color: #dbeafe;
        }
        .text-blue-50 {
            color: #f0f9ff;
        }
        .text-blue-900 {
            color: #1e3a8a;
        }
        .text-blue-800 {
            color: #1e40af;
        }
        .text-blue-700 {
            color: #1d4ed8;
        }
        .text-blue-600 {
            color: #2563eb;
        }
        .text-blue-500 {
            color: #3b82f6;
        }
        .text-blue-400 {
            color: #60a5fa;
        }
        .text-blue-300 {
            color: #93c5fd;
        }
        .text-blue-200 {
            color: #bfdbfe;
        }
        .text-blue-100 {
            color: #dbeafe;
        }   
        .text-blue-50 {
            color: #f0f9ff;
        }
        .text-blue-900 {
            color: #1e3a8a;
        }
        .text-blue-800 {
            color: #1e40af;
        }
        .text-blue-700 {
            color: #1d4ed8;
        }
































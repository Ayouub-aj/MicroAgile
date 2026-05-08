<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .float-animation {
                animation: float 3s ease-in-out infinite;
            }
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left side - Form -->
            <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-4 bg-gradient-to-br from-gray-50 to-blue-50">
                <div class="max-w-md w-full">
                    <div class="bg-white rounded-2xl shadow-xl p-5 sm:p-6 fade-in-up">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right side - Branding -->
            <div class="hidden lg:flex lg:flex-1 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 items-center justify-center p-6 relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-300 rounded-full blur-3xl"></div>
                </div>

                <div class="max-w-md relative z-10">
                    <div class="text-center mb-8">
                        <div class="inline-block float-animation mb-4">
                            <i class="fas fa-rocket text-white text-4xl drop-shadow-lg"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2 drop-shadow-md">MicroAgile</h1>
                        <p class="text-white text-base font-medium">Gérez vos projets avec agilité</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-400 rounded-lg flex items-center justify-center shadow-lg">
                                    <i class="fas fa-users text-white text-base"></i>
                                </div>
                            </div>
                            <div class="text-left">
                                <h3 class="text-white font-bold text-sm">Collaboration en équipe</h3>
                                <p class="text-white text-opacity-90 text-xs mt-0.5">Travaillez ensemble efficacement</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center shadow-lg">
                                    <i class="fas fa-chart-line text-white text-base"></i>
                                </div>
                            </div>
                            <div class="text-left">
                                <h3 class="text-white font-bold text-sm">Suivi en temps réel</h3>
                                <p class="text-white text-opacity-90 text-xs mt-0.5">Gardez le contrôle sur vos tâches</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-pink-400 rounded-lg flex items-center justify-center shadow-lg">
                                    <i class="fas fa-magic text-white text-base"></i>
                                </div>
                            </div>
                            <div class="text-left">
                                <h3 class="text-white font-bold text-sm">Interface intuitive</h3>
                                <p class="text-white text-opacity-90 text-xs mt-0.5">Simple et agréable à utiliser</p>

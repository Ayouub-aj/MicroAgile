<x-guest-layout>
    <div>
        <!-- Logo for mobile -->
        <div class="lg:hidden mb-6 text-center">
            <div class="inline-block p-2.5 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl mb-2">
                <i class="fas fa-rocket text-white text-3xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900">MicroAgile</h2>
        </div>

        <!-- Header -->
        <div class="mb-5">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Créer un compte</h2>
            <p class="mt-1 text-sm text-gray-600">Commencez à gérer vos projets dès aujourd'hui</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" value="Nom complet" class="text-xs font-semibold text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400 text-sm"></i>
                    </div>
                    <x-text-input id="name" class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Jean Dupont" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Adresse email" class="text-xs font-semibold text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400 text-sm"></i>
                    </div>
                    <x-text-input id="email" class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="votre@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Mot de passe" class="text-xs font-semibold text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400 text-sm"></i>
                    </div>
                    <x-text-input id="password" class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password"
                                    placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" value="Confirmer le mot de passe" class="text-xs font-semibold text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400 text-sm"></i>
                    </div>
                    <x-text-input id="password_confirmation" class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    type="password"
                                    name="password_confirmation"
                                    required autocomplete="new-password"
                                    placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Submit Button -->
            <div class="pt-1">
                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-[1.02] transition-all duration-200">
                    <i class="fas fa-user-plus mr-2 text-sm"></i>
                    Créer mon compte
                </button>
            </div>

            <!-- Divider -->
            <div class="relative py-1.5">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-3 bg-white text-gray-500">ou</span>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center pb-1">
                <p class="text-xs text-gray-600">
                    Vous avez déjà un compte ?
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500 hover:underline transition">
                        Se connecter
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>

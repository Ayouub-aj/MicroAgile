<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nom complet" class="text-sm font-semibold text-gray-700" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <x-text-input id="name" name="name" type="text" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Adresse email" class="text-sm font-semibold text-gray-700" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email" name="email" type="email" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" :value="old('email', $user->email)" required autocomplete="username" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Votre adresse email n'est pas vérifiée.
                    </p>
                    <button form="send-verification" class="mt-2 text-sm text-blue-600 hover:text-blue-500 font-medium underline">
                        Cliquez ici pour renvoyer l'email de vérification.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                <i class="fas fa-save mr-2"></i>
                Enregistrer
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium flex items-center"
                >
                    <i class="fas fa-check-circle mr-1"></i>
                    Enregistré
                </p>
            @endif
        </div>
    </form>
</section>

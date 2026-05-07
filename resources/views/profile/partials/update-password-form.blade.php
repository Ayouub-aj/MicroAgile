<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <x-input-label for="update_password_current_password" value="Mot de passe actuel" class="text-sm font-semibold text-gray-700" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <x-input-label for="update_password_password" value="Nouveau mot de passe" class="text-sm font-semibold text-gray-700" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-key text-gray-400"></i>
                </div>
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="update_password_password_confirmation" value="Confirmer le mot de passe" class="text-sm font-semibold text-gray-700" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-gray-400"></i>
                </div>
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                <i class="fas fa-save mr-2"></i>
                Enregistrer
            </button>

            @if (session('status') === 'password-updated')
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

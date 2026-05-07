<section class="space-y-4">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-sm text-red-800">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement supprimées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
        </p>
    </div>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200"
    >
        <i class="fas fa-trash-alt mr-2"></i>
        Supprimer le compte
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">
                    Êtes-vous sûr de vouloir supprimer votre compte ?
                </h2>
            </div>

            <p class="text-sm text-gray-600 text-center mb-6">
                Une fois votre compte supprimé, toutes ses ressources et données seront définitivement supprimées. Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.
            </p>

            <div>
                <x-input-label for="password" value="Mot de passe" class="sr-only" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Entrez votre mot de passe"
                    />
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-md transition">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Supprimer le compte
                </button>
            </div>
        </form>
    </x-modal>
</section>

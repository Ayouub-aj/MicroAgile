<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-user-circle mr-2 text-blue-600"></i>
            Mon Profil
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Profile Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-id-card mr-2 text-blue-600"></i>
                    Informations du profil
                </h3>
            </div>
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-key mr-2 text-green-600"></i>
                    Modifier le mot de passe
                </h3>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-xl shadow-sm border border-red-200">
            <div class="border-b border-red-200 px-6 py-4 bg-gradient-to-r from-red-50 to-pink-50">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                    Supprimer le compte
                </h3>
            </div>
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>

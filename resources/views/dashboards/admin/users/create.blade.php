<x-app-layout>
    <form action="{{ route('admin.users.store') }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
        @csrf

        <div class="mb-6">
            <label for="name" class="block mb-2 font-semibold text-gray-700">Nom complet</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nom complet" value="{{ old('name') }}" required>
        </div>

        <div class="mb-6">
            <label for="email" class="block mb-2 font-semibold text-gray-700">Adresse e-mail</label>
            <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-6">
            <label for="password" class="block mb-2 font-semibold text-gray-700">Mot de passe</label>
            <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Mot de passe" required>
        </div>

        <div class="mb-6">
            <label for="role" class="block mb-2 font-semibold text-gray-700">Rôle</label>
            <select name="role" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="" disabled selected>-- Sélectionner un rôle --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">Créer</button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Annuler</a>
        </div>
    </form>
</x-app-layout>



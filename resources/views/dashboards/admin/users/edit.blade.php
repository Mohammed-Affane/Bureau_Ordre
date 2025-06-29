<x-app-layout>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Nom</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border rounded" required>
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border rounded" required>
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Mot de passe <small class="text-gray-500">(laisser vide pour ne pas modifier)</small></label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded">
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Rôle</label>
            <select name="role" class="w-full px-4 py-2 border rounded" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</x-app-layout>

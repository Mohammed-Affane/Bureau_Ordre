<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">Modifier l'entité : {{ $entite->nom }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Mettre à jour les informations de l'entité</p>
                    </div>
                    <form action="{{ route('admin.entites.update', $entite) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'entité</label>
                            <input type="text" name="nom" id="nom" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nom', $entite->nom) }}" required>
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <input type="text" name="type" id="type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('type', $entite->type) }}" required>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                            <input type="text" name="code" id="code" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('code', $entite->code) }}">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Entité parente</label>
                            <select name="parent_id" id="parent_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Aucune</option>
                                @foreach($entites ?? [] as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $entite->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->nom }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="responsable_id" class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                            <select name="responsable_id" id="responsable_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Aucun</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ old('responsable_id', $entite->responsable_id) == $user->id ? 'selected' : '' }}>{{ $user->nom_complet ?? $user->name }}</option>
                                @endforeach
                            </select>
                            @error('responsable_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.entites.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Annuler</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
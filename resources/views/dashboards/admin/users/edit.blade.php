<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">Modifier Utilisateur: {{ $user->name }}</h1>
                        <p  class="text-sm text-gray-500 mt-1">Update Utilisateur details</p>
                    </div>
                    
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" name="name" id="name" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot De Pass<small class="text-gray-500">(laisser vide pour ne pas modifier)</small></label>
                            <input type="password" name="password" id="password" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old($user->password) }}">
                           
                        </div>
                    <div class="mb-6">
                        <label class="block mb-2 font-semibold text-gray-700">Rôle</label>
                        <select name="role" class="w-full px-4 py-2 border rounded" required>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                        
                        
                        
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


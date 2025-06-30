<x-app-layout>
    <x-slot name="title">Utilisateurs</x-slot>

    <h2 class="text-2xl font-bold mb-4">Liste des utilisateurs</h2>

    <a href="{{ route('admin.users.create') }}" class="mb-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        + Nouvel utilisateur
    </a>


    <table class="table-dark table-hover min-w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nom</th>
                <th class="p-2 text-left">Email</th>
                <th class="p-2 text-left">Rôles</th>
                <th class="p-2 text-left">Date de création</th>
                
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-t">
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">
            @foreach($user->getRoleNames() as $role)
                <span class="badge">{{ $role }}</span>
            @endforeach
        </td>
                    <td class="p-2">{{ $user->created_at }}</td>
                    <td class="p-2 text-center">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="link-primary">Modifier</a>
                        |
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>

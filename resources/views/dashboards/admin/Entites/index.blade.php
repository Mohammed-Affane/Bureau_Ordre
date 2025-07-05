@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Entités</h1>
    <a href="{{ route('admin.entites.create') }}" class="btn btn-primary mb-3">Créer une entité</a>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Code</th>
                <th>Entité Parente</th>
                <th>Responsable</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entites as $entite)
                <tr>
                    <td>{{ $entite->nom }}</td>
                    <td>{{ $entite->type }}</td>
                    <td>{{ $entite->code }}</td>
                    <td>{{ $entite->parent->nom ?? '-' }}</td>
                    <td>{{ $entite->responsable->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.entites.show', $entite->id) }}" class="btn btn-info btn-sm">Voir</a>
                        <a href="{{ route('admin.entites.edit', $entite->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route('admin.entites.destroy', $entite->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
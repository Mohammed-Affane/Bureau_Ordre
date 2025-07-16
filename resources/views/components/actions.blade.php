<div class="action-container" data-courrier-id="{{ $courrier->id }}">
<select class="border rounded px-3 py-2 text-sm action-dropdown" >
  <option value="">Actions</option>
  <option value="show" class="text-indigo-600 hover:text-indigo-900 mr-3">Voir</option>
  <option value="edit" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</option>
  <option value="delete" class="text-red-600 hover:text-red-900 mr-3">Supprimer</option>
  <option value="affecte" class="text-green-600 hover:text-red-900 mr-3">Affecter</option>
</select>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>


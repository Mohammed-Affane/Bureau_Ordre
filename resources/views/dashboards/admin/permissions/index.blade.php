<x-app-layout>
    <div class="py-8">
        <div class="w-full px-6">
            
            <!-- Notification Container - ADD THIS -->
            <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-3 w-80 max-w-full"></div>


            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex items-start justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette permission ? Cette action est irréversible.</p>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        <!-- Create/Edit Permission Modal -->
        <div id="permissionModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Créer une permission</h3>
                    <button type="button" onclick="closePermissionModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="permissionForm" method="POST" class="mt-4">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="permission_id" name="permission_id">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom de la permission</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">Utilisez le format snake_case (ex: edit_users)</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closePermissionModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

            <!-- Main Content -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Gestion des Permissions</h1>
                            <p class="text-sm text-gray-500 mt-1">Gérer les permissions d'accès au système</p>
                        </div>
                        <button onclick="openPermissionModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Nouvelle permission
                        </button>
                    </div>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200" id="permissionsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de création</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="permissionsTable" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be populated via JavaScript -->
                            </tbody>
                        </table>
                        <div id="pagination" class="mt-4 flex gap-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD THESE STYLES -->
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .notification-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        .notification-exit {
            animation: slideOutRight 0.3s ease-out;
        }
    </style>

    <script>
        let deletecurentURL = 
        // ADD THIS BEAUTIFUL NOTIFICATION FUNCTION
        function showNotification(type, message) {
            const container = document.getElementById('notificationContainer');
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification-enter max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden`;
            
            const iconColor = type === 'success' ? 'text-green-400' : 'text-red-400';
            const bgColor = type === 'success' ? 'bg-green-50' : 'bg-red-50';
            const textColor = type === 'success' ? 'text-green-800' : 'text-red-800';
            
            const icon = type === 'success' 
                ? `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                   </svg>`
                : `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                   </svg>`;
            
            notification.innerHTML = `
                <div class="flex items-start p-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 ${bgColor} rounded-full flex items-center justify-center">
                            <div class="${iconColor}">
                                ${icon}
                            </div>
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">
                            ${type === 'success' ? 'Succès' : 'Erreur'}
                        </p>
                        <p class="mt-1 text-sm text-gray-500">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="removeNotification(this.closest('.notification-enter'))">
                            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                removeNotification(notification);
            }, 5000);
        }
        
        function removeNotification(notification) {
            if (notification) {
                notification.classList.remove('notification-enter');
                notification.classList.add('notification-exit');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }

        // Modal functions
        function openModal(action) {
            document.getElementById('deleteForm').action = action;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        
        function openPermissionModal(permission = null) {
            const form = document.getElementById('permissionForm');
            const modalTitle = document.getElementById('modalTitle');
            
            form.reset();
            if (permission) {
                modalTitle.textContent = 'Modifier la permission';
                document.getElementById('permission_id').value = permission.id;
                document.getElementById('name').value = permission.name;
            } else {
                modalTitle.textContent = 'Créer une permission';
                document.getElementById('permission_id').value = '';
            }
            
            document.getElementById('permissionModal').classList.remove('hidden');
        }
        
        function closePermissionModal() {
            document.getElementById('permissionModal').classList.add('hidden');
        }
        
        // Form submission
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const permissionId = formData.get('permission_id');
            const name = formData.get('name');
            
            // Check if we have the required data
            if (!name || name.trim() === '') {
                showNotification('error', 'Le nom de la permission est requis');
                return;
            }
            
            const isUpdate = permissionId && permissionId !== '';
            const url = isUpdate 
                ? `/admin/permissions/${permissionId}` 
                : '/admin/permissions';
            
            // For both POST and PUT, use FormData with _method field for Laravel
            if (isUpdate) {
                formData.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: 'POST', // Always use POST, Laravel will handle _method
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closePermissionModal();
                    loadPermissions();
                    showNotification('success', data.success);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors && error.errors.name) {
                    showNotification('error', error.errors.name[0]);
                } else if (error.message) {
                    showNotification('error', error.message);
                } else {
                    showNotification('error', 'Une erreur est survenue');
                }
            });
        });
        
       function loadPermissions(page = 1) {
            fetch(`/admin/permissions?ajax=1&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Fetched paginated data:", data);

                    const tbody = document.querySelector('#permissionsTable tbody');
                    tbody.innerHTML = '';

                    if (!data.data || data.data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-4">
                                    Aucune permission trouvée.
                                </td>
                            </tr>`;
                        return;
                    }

                    data.data.forEach((permission, index) => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50';
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${permission.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${permission.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${permission.created_at ? new Date(permission.created_at).toLocaleDateString() : ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                <button onclick="openPermissionModal(${JSON.stringify(permission).replace(/"/g, '&quot;')})" class="text-yellow-600 hover:text-yellow-900"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="openModal('/admin/permissions/${permission.id}')" class="text-red-600 hover:text-red-900"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    renderPagination(data);
                });
        }
        
        // Load permissions on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPermissions();
        });

        function renderPagination(data) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            for (let i = 1; i <= data.last_page; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.className = `px-3 py-1 rounded ${
                    i === data.current_page
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                }`;
                button.addEventListener('click', () => loadPermissions(i));
                pagination.appendChild(button);
            }
        }

    </script>
</x-app-layout>
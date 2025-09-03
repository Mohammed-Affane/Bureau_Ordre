<x-app-layout>
    <div class="py-8">
        <div class="w-full px-6">
               
            <!-- Notification Container -->
            <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-3 w-80 max-w-full"></div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
                            </div>
                        </div>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette permission ? Cette action est irréversible et supprimera définitivement la permission du système.</p>
                        <p class="text-sm text-red-600 mt-2 font-medium">Permission: <span id="permissionToDelete"></span></p>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Annuler
                        </button>
                        <button id="confirmDeleteBtn" type="button" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <span id="deleteButtonText">Supprimer</span>
                            <svg id="deleteSpinner" class="hidden animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
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
                        <input type="hidden" name="_token" value="csrf-token-placeholder">
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
                        <button onclick="openPermissionModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
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
                            <tbody id="permissionsTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be populated via JavaScript -->
                                <tr>
                                    <td colspan="4" class="text-center py-8">
                                        <div class="flex justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-gray-500">Chargement des permissions...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="pagination" class="mt-4 flex gap-2 justify-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
                transform: translate3d(0, 0, 0);
            }
            40%, 43% {
                animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
                transform: translate3d(0, -8px, 0);
            }
            70% {
                animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
                transform: translate3d(0, -4px, 0);
            }
            90% {
                transform: translate3d(0, -2px, 0);
            }
        }
        
        .notification-enter {
            animation: slideInRight 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .notification-exit {
            animation: slideOutRight 0.3s ease-in;
        }
        
        .notification-success {
            border-left: 4px solid #10b981;
        }
        
        .notification-error {
            border-left: 4px solid #ef4444;
        }
        
        .notification-warning {
            border-left: 4px solid #f59e0b;
        }
    </style>

    <script>
        let currentDeleteId = null;
        let currentDeleteName = null;
        let currentPage = 1;

        // Beautiful notification function with enhanced styling
        function showNotification(type, message, title = null) {
            const container = document.getElementById('notificationContainer');
            
            const notification = document.createElement('div');
            notification.className = `notification-enter notification-${type} max-w-sm w-full bg-white shadow-2xl rounded-xl pointer-events-auto ring-1 ring-gray-200 overflow-hidden transform transition-all duration-300 hover:scale-105`;
            
            const colors = {
                success: {
                    iconBg: 'bg-green-100',
                    iconColor: 'text-green-600',
                    titleColor: 'text-green-800',
                    icon: `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                           </svg>`
                },
                error: {
                    iconBg: 'bg-red-100',
                    iconColor: 'text-red-600',
                    titleColor: 'text-red-800',
                    icon: `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                           </svg>`
                },
                warning: {
                    iconBg: 'bg-yellow-100',
                    iconColor: 'text-yellow-600',
                    titleColor: 'text-yellow-800',
                    icon: `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                           </svg>`
                }
            };
            
            const config = colors[type];
            const notificationTitle = title || (type === 'success' ? 'Succès' : type === 'error' ? 'Erreur' : 'Attention');
            
            notification.innerHTML = `
                <div class="flex items-start p-5">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 ${config.iconBg} rounded-full flex items-center justify-center shadow-sm">
                            <div class="${config.iconColor}">
                                ${config.icon}
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <p class="text-sm font-semibold ${config.titleColor}">
                            ${notificationTitle}
                        </p>
                        <p class="mt-1 text-sm text-gray-600 leading-relaxed">${message}</p>
                        <div class="mt-3">
                            <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r ${type === 'success' ? 'from-green-400 to-green-600' : type === 'error' ? 'from-red-400 to-red-600' : 'from-yellow-400 to-yellow-600'} rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="bg-gray-50 hover:bg-gray-100 rounded-lg p-1.5 inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200" onclick="removeNotification(this.closest('.notification-enter'))">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Auto remove after 6 seconds for success, 8 seconds for errors
            const timeout = type === 'success' ? 6000 : 8000;
            setTimeout(() => {
                removeNotification(notification);
            }, timeout);
            
            // Add click to dismiss
            notification.addEventListener('click', (e) => {
                if (!e.target.closest('button')) {
                    removeNotification(notification);
                }
            });
        }
        
        function removeNotification(notification) {
            if (notification && notification.parentNode) {
                notification.classList.remove('notification-enter');
                notification.classList.add('notification-exit');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }

        // Enhanced modal functions
        function openModal(permissionId, permissionName) {
            currentDeleteId = permissionId;
            currentDeleteName = permissionName;
            document.getElementById('permissionToDelete').textContent = permissionName;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        function closeModal() {
            currentDeleteId = null;
            currentDeleteName = null;
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        // Delete confirmation with loading state
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!currentDeleteId) return;
            
            const button = this;
            const buttonText = document.getElementById('deleteButtonText');
            const spinner = document.getElementById('deleteSpinner');
            
            // Show loading state
            button.disabled = true;
            buttonText.textContent = 'Suppression...';
            spinner.classList.remove('hidden');
            button.classList.add('opacity-75');
            
            fetch(`/admin/permissions/${currentDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
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
                    closeModal();
                    loadPermissions(currentPage);
                    showNotification('success', data.success, 'Permission supprimée');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                const errorMessage = error.message || error.error || 'Une erreur est survenue lors de la suppression';
                showNotification('error', errorMessage, 'Erreur de suppression');
            })
            .finally(() => {
                // Reset button state
                button.disabled = false;
                buttonText.textContent = 'Supprimer';
                spinner.classList.add('hidden');
                button.classList.remove('opacity-75');
            });
        });
        
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
            document.body.classList.add('overflow-hidden');
        }
        
        function closePermissionModal() {
            document.getElementById('permissionModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        // Form submission with enhanced error handling
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const permissionId = formData.get('permission_id');
            const name = formData.get('name');
            
            if (!name || name.trim() === '') {
                showNotification('error', 'Le nom de la permission est requis', 'Champ obligatoire');
                return;
            }
            
            // Validate snake_case format
            const snakeCaseRegex = /^[a-z]+(_[a-z]+)*$/;
            if (!snakeCaseRegex.test(name)) {
                showNotification('error', 'Le format doit être snake_case (ex: edit_users)', 'Format invalide');
                return;
            }
            
            const isUpdate = permissionId && permissionId !== '';
            const url = isUpdate 
                ? `/admin/permissions/${permissionId}` 
                : '/admin/permissions';
            
            if (isUpdate) {
                formData.append('_method', 'PUT');
            }
            
            // Show loading state on submit button
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Enregistrement...
            `;
            
            fetch(url, {
                method: 'POST',
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
                    loadPermissions(currentPage);
                    const action = isUpdate ? 'modifiée' : 'créée';
                    showNotification('success', data.success, `Permission ${action}`);
                }
            })
            .catch(error => {
                console.error('Form error:', error);
                if (error.errors && error.errors.name) {
                    showNotification('error', error.errors.name[0], 'Erreur de validation');
                } else if (error.message) {
                    showNotification('error', error.message, 'Erreur');
                } else {
                    showNotification('error', 'Une erreur est survenue', 'Erreur inattendue');
                }
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
        
        function loadPermissions(page = 1) {
            currentPage = page;
            const tbody = document.getElementById('permissionsTableBody');
            
            // Show loading state
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-8">
                        <div class="flex justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-500">Chargement des permissions...</span>
                        </div>
                    </td>
                </tr>`;
            
            fetch(`/admin/permissions?ajax=1&page=${page}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    tbody.innerHTML = '';

                    if (!data.data || data.data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-8">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7" />
                                        </svg>
                                        <p class="text-lg font-medium">Aucune permission trouvée</p>
                                        <p class="text-sm">Commencez par créer une nouvelle permission</p>
                                    </div>
                                </td>
                            </tr>`;
                        return;
                    }

                    data.data.forEach((permission, index) => {
                        const rowNumber = (data.current_page - 1) * data.per_page + index + 1;
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50 transition-colors duration-200';
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${rowNumber}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-900">${permission.name}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${permission.created_at ? new Date(permission.created_at).toLocaleDateString('fr-FR', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                }) : ''}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="openPermissionModal(${JSON.stringify(permission).replace(/"/g, '&quot;')})" 
                                            class="inline-flex items-center p-2 text-yellow-600 hover:text-yellow-900 hover:bg-yellow-50 rounded-lg transition-all duration-200 group" 
                                            title="Modifier la permission"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="openModal(${permission.id}, '${permission.name.replace(/'/g, "\\'")}')" 
                                            class="inline-flex items-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-all duration-200 group" 
                                            title="Supprimer la permission"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    renderPagination(data);
                })
                .catch(error => {
                    console.error('Load permissions error:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-8">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-red-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-lg font-medium text-red-600">Erreur de chargement</p>
                                    <p class="text-sm">Impossible de charger les permissions</p>
                                    <button onclick="loadPermissions(${page})" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                        Réessayer
                                    </button>
                                </div>
                            </td>
                        </tr>`;
                    showNotification('error', 'Erreur lors du chargement des permissions', 'Erreur de chargement');
                });
        }
        
        function renderPagination(data) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (data.last_page <= 1) return;

            // Previous button
            if (data.current_page > 1) {
                const prevButton = document.createElement('button');
                prevButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                `;
                prevButton.className = 'px-3 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors';
                prevButton.addEventListener('click', () => loadPermissions(data.current_page - 1));
                pagination.appendChild(prevButton);
            }

            // Page numbers
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = `px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 ${
                        i === data.current_page
                            ? 'bg-indigo-600 text-white shadow-md transform scale-105'
                            : 'bg-white text-gray-700 border border-gray-300 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300'
                    }`;
                    button.addEventListener('click', () => loadPermissions(i));
                    pagination.appendChild(button);
                } else if (i === data.current_page - 2 || i === data.current_page + 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-2 py-2 text-gray-500';
                    pagination.appendChild(ellipsis);
                }
            }

            // Next button
            if (data.current_page < data.last_page) {
                const nextButton = document.createElement('button');
                nextButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                `;
                nextButton.className = 'px-3 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors';
                nextButton.addEventListener('click', () => loadPermissions(data.current_page + 1));
                pagination.appendChild(nextButton);
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            const deleteModal = document.getElementById('deleteModal');
            const permissionModal = document.getElementById('permissionModal');
            
            if (deleteModal && !deleteModal.classList.contains('hidden') && e.target === deleteModal) {
                closeModal();
            }
            
            if (permissionModal && !permissionModal.classList.contains('hidden') && e.target === permissionModal) {
                closePermissionModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const deleteModal = document.getElementById('deleteModal');
                const permissionModal = document.getElementById('permissionModal');
                
                if (deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeModal();
                }
                
                if (permissionModal && !permissionModal.classList.contains('hidden')) {
                    closePermissionModal();
                }
            }
        });

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadPermissions(1);
            
            // Add CSRF token to all forms
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = csrfToken;
            });
            
            // Add CSRF token to AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        });
    </script>
</x-app-layout>
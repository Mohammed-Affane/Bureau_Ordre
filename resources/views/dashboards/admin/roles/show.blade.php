<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-semibold text-gray-900">Role Details</h1>
                                <p class="text-sm text-gray-500 mt-1">View role information and assigned permissions</p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit Role
                                </a>
                                <a href="{{ route('admin.roles.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Back to List
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Role Name -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Role Name</h3>
                            <div class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                                {{ $role->name }}
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Assigned Permissions</h3>
                            @if ($rolePermissions->isEmpty())
                                <div class="mt-1 text-sm text-gray-500 italic p-3 bg-gray-50 rounded-md">
                                    No permissions assigned to this role
                                </div>
                            @else
                                <ul class="mt-1 border border-gray-200 rounded-md divide-y divide-gray-200">
                                    @foreach ($rolePermissions as $permission)
                                        <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                            <div class="w-0 flex-1 flex items-center">
                                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-2 flex-1 w-0 truncate">
                                                    {{ $permission->name }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
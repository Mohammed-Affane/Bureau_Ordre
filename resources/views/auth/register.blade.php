<x-guest-layout>
    <div class="min-h-screen corporate-gradient flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 text-red-600">
                    <path fill-rule="evenodd" d="M4.5 9.75a6 6 0 0111.573-2.226 3.75 3.75 0 014.133 4.303A4.5 4.5 0 0118 20.25H6.75a5.25 5.25 0 01-2.23-10.004 6.072 6.072 0 01-.02-.496z" clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Create Enterprise Account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Or <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500">sign in to your existing account</a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow rounded-lg sm:px-10 border border-gray-200 dark:border-gray-700">
                <form class="mb-0 space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <div class="mt-1">
                            <x-text-input 
                                id="name" 
                                class="block w-full rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700" 
                                type="text" 
                                name="name" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                autocomplete="name" 
                            />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Corporate Email')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <div class="mt-1">
                            <x-text-input 
                                id="email" 
                                class="block w-full rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autocomplete="email" 
                            />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <div class="mt-1">
                            <x-text-input 
                                id="password" 
                                class="block w-full rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password" 
                            />
                        </div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Minimum 8 characters with at least one uppercase, one lowercase, and one number.
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <div class="mt-1">
                            <x-text-input 
                                id="password_confirmation" 
                                class="block w-full rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password" 
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded" required>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700 dark:text-gray-300">
                                I agree to the <a href="#" class="text-red-600 hover:text-red-500">Terms of Service</a> and <a href="#" class="text-red-600 hover:text-red-500">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <div>
                        <x-primary-button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('Create Enterprise Account') }}
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                Already have an account?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Sign in to existing account
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>© 2023 Bureau d'Ordre Management System. All rights reserved.</p>
            <p class="mt-1">Need help? <a href="#" class="text-red-600 hover:text-red-500">Contact support</a></p>
        </div>
    </div>
</x-guest-layout>
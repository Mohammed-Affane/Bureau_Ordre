{{-- resources/views/affectations/form.blade.php --}}
<x-app-layout>
    <div x-data="{ step: 1, currentUserRole: '{{ auth()->user()->roles->first()->name ?? 'unknown' }}' }" class="max-w-3xl mx-auto py-8">
        <!-- Progress Bar -->
        <div class="flex items-center justify-between mb-8">
            <template x-for="s in [1,2,3]" :key="s">
                <div class="flex-1">
                    <div :class="step === s ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'"
                        class="w-8 h-8 flex items-center justify-center rounded-full mx-auto">
                        <span x-text="s"></span>
                    </div>
                    <div class="text-center mt-2 text-sm">
                        <template x-if="s === 1">BO → CAB</template>
                        <template x-if="s === 2">CAB → SG/DAI</template>
                        <template x-if="s === 3">Division</template>
                    </div>
                </div>
            </template>
        </div>

        <form action="{{ route('affecter.store', $courrier) }}" method="POST">
            @csrf
            
            <input type="hidden" name="courrier_id" value="{{ $courrier->id }}">
            <input type="hidden" name="statut_affectation" value="en_cours">
            <input type="hidden" name="date_affectation" value="{{ now()->format('Y-m-d') }}">
            <input type="hidden" name="id_affecte_par_utilisateur" value="{{ auth()->id() }}">
            <input type="hidden" name="current_user_role" value="{{ auth()->user()->roles->first()->name ?? 'unknown' }}">

            {{-- Step 1: BO → CAB --}}
            @if(auth()->user()->roles->first()->name === 'bo' || auth()->user()->roles->first()->name === 'admin')
            <div x-show="step === 1" class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Bureau d'Ordre Assignment</h2>
                </div>
                
                <!-- Document Information -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        <i class="fas fa-file-alt mr-2"></i>Document Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <strong class="text-gray-600">Reference:</strong>
                            <span class="text-gray-800">{{ $courrier->reference_arrive ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <strong class="text-gray-600">Subject:</strong>
                            <span class="text-gray-800">{{ $courrier->objet ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <strong class="text-gray-600">Date:</strong>
                            <span class="text-gray-800">{{ $courrier->date_courrier ? $courrier->date_courrier->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Assignment Instructions -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Assignment Instructions
                    </h3>
                    <p class="text-blue-700">As Bureau d'Ordre, you are assigning this document to a CAB (Cabinet) user for initial processing and review.</p>
                </div>
                
                <!-- CAB User Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Select CAB User
                    </label>
                    <!-- Hidden value for submission -->
                    <input type="hidden" name="id_affecte_a_utilisateur" value="{{ $cabUser->id }}">

                    <!-- Disabled, just for display -->
                    <input type="text"
                        value="{{ $cabUser->name }} - {{ $cabUser->roles->first()->name ?? 'CAB' }}"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed"
                        disabled>

                    @error('id_affecte_a_utilisateur')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @endif

            {{-- Step 2: CAB → SG/DAI --}}
            @if(auth()->user()->roles->first()->name === 'cab' || auth()->user()->roles->first()->name === 'admin')
            <div x-show="step === 2" class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">CAB Assignment</h2>
                </div>
                
                <!-- Assignment Instructions -->
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Assignment Instructions
                    </h3>
                    <p class="text-green-700">As CAB, you need to assign this document to either Secretary General (SG) or DAI for further processing.</p>
                </div>
                
                <!-- SG/DAI User Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-shield mr-2"></i>Select SG/DAI User
                    </label>
                    <select name="id_affecte_a_utilisateur" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Choose SG or DAI user...</option>
                       
                            <option value="{{ $sgUser->id }}">
                                {{ $sgUser->name }} - {{ $sgUser->roles->first()->name ?? 'SG' }}
                            </option>
                            <option value="{{$daiUser->id}}">
                                {{$daiUser->name}} - {{$daiUser->roles->first()->name ?? 'DAI'}}
                            </option>
                    </select>
                    @error('id_affecte_a_utilisateur')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- CAB Instructions -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-alt mr-2"></i>CAB Instructions
                    </label>
                    <textarea name="Instruction" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" 
                              placeholder="Provide detailed instructions for the SG/DAI user..." rows="4"></textarea>
                    @error('Instruction')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @endif

            {{-- Step 3: SG → Division --}}
            @if(auth()->user()->roles->first()->name === 'sg' || auth()->user()->roles->first()->name === 'admin')
            <div x-show="step === 3" class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <i class="fas fa-building text-purple-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Division Assignment</h2>
                </div>
                
                <!-- Assignment Instructions -->
                <div class="bg-purple-50 border-l-4 border-purple-400 p-4 mb-6">
                    <h3 class="text-lg font-semibold text-purple-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Assignment Instructions
                    </h3>
                    <p class="text-purple-700">As Secretary General, you are assigning this document to a Division Head for final processing and execution.</p>
                </div>
                
                <!-- Division User Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-cog mr-2"></i>Select Division Head
                    </label>
                    <select name="id_affecte_a_utilisateur" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                        <option value="">Choose a Division Head...</option>
                        @foreach($divisionUsers as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} - {{ $user->division ?? 'Division Head' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_affecte_a_utilisateur')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- SG Instructions -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clipboard-list mr-2"></i>SG Instructions
                    </label>
                    <textarea name="Instruction" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" 
                              placeholder="Provide detailed instructions for the Division Head..." rows="4"></textarea>
                    @error('Instruction')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Priority Level -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-flag mr-2"></i>Priority Level
                    </label>
                    <select name="priority" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                        <option value="normal">Normal</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High Priority</option>
                    </select>
                </div>
                
                <!-- Expected Completion Date -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2"></i>Expected Completion Date
                    </label>
                    <input type="date" name="expected_completion_date" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-between mt-8">
                <button type="button" @click="step > 1 ? step-- : null" 
                        :disabled="step === 1" 
                        :class="step === 1 ? 'bg-gray-300 cursor-not-allowed' : 'bg-gray-600 hover:bg-gray-700'" 
                        class="px-6 py-2 text-white rounded-md transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Previous
                </button>
                
                <template x-if="step < 3">
                    <button type="button" @click="step++" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Next<i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </template>
                
                <template x-if="step === 3">
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>Assign Document
                    </button>
                </template>
            </div>
        </form>
    </div>

    <script>
        // Initialize step based on user role
        document.addEventListener('DOMContentLoaded', function() {
            const userRole = '{{ auth()->user()->roles->first()->name ?? 'unknown' }}';
            const stepElement = document.querySelector('[x-data]');
            
            if (stepElement) {
                const alpineData = Alpine.$data(stepElement);
                
                switch(userRole) {
                    case 'bo':
                        alpineData.step = 1;
                        break;
                    case 'cab':
                        alpineData.step = 2;
                        break;
                    case 'sg':
                        alpineData.step = 3;
                        break;
                    case 'admin':
                        alpineData.step = 1; // Admin can start from any step
                        break;
                    default:
                        alpineData.step = 1;
                }
            }
        });
    </script>
</x-app-layout>
@props(['stats'])

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    @foreach($stats as $stat)
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-{{ $stat['color'] }}-500 rounded-md flex items-center justify-center">
                            @include('components.icons.' . $stat['icon'], ['class' => 'w-5 h-5 text-white'])
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                {{ $stat['title'] }}
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stat['value'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            @if(isset($stat['change']))
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="font-medium text-{{ $stat['change']['positive'] ? 'green' : 'red' }}-600">
                            {{ $stat['change']['positive'] ? '+' : '' }}{{ $stat['change']['value'] }}%
                        </span>
                        <span class="text-gray-500">depuis le mois dernier</span>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
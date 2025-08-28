@props(['label', 'value'])

@if($value)
    <div {{ $attributes->merge(['class' => 'bg-gray-50 p-3 rounded-lg']) }}>
        <span class="block text-sm font-semibold text-gray-600 mb-1">{{ $label }}</span>
        <span class="text-gray-800">{{ $value }}</span>
    </div>
@endif

@props([
    'class' => 'w-5 h-5 text-gray-500'
])

<svg xmlns="http://www.w3.org/2000/svg"
     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M3 3h18M6 6h12v12H6V6zm3 3h6v6H9V9z" />
</svg>
@props([
    'class' => 'w-5 h-5 text-gray-500'
])

<svg xmlns="http://www.w3.org/2000/svg"
     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M3 4.5h18M4.5 7.5h15v10.125c0 .621-.504 1.125-1.125 1.125H5.625c-.621 0-1.125-.504-1.125-1.125V7.5zM9.75 12h4.5" />
</svg>
@props([
    'class' => 'w-5 h-5 text-gray-500'
])

<svg xmlns="http://www.w3.org/2000/svg"
     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 6v12m0-12a3 3 0 110 6 3 3 0 000-6zm0 6a3 3 0 100 6 3 3 0 000-6z" />
</svg>
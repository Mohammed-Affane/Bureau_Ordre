@props([
    'class' => 'w-5 h-5 text-gray-500'
])

<svg xmlns="http://www.w3.org/2000/svg"
     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M9 2.25h6M9 2.25a.75.75 0 00-.75.75V4.5H6.75A2.25 2.25 0 004.5 6.75v12A2.25 2.25 0 006.75 21h10.5a2.25 2.25 0 002.25-2.25v-12A2.25 2.25 0 0017.25 4.5H15.75V3a.75.75 0 00-.75-.75H9zM9 8.25h6m-6 3h6m-6 3h3" />
</svg>
@props([
    'class' => 'w-5 h-5 text-gray-500'
])

<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-7.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25h7.5M9 12h6m-6 3h3m1.5 4.5l4.5-4.5m0 0l-4.5-4.5m4.5 4.5H12" />
</svg>
@props(['class' => 'w-5 h-5'])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M3 3h18M4.5 7.5h15M12 12v6m0 0l3-3m-3 3l-3-3" />
</svg>

@props(['class' => 'w-5 h-5'])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     {{ $attributes->merge(['class' => $class]) }}>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 10.5v3m1.5-1.5h-3M6.75 3h10.5c.414 0 .75.336.75.75v5.379c0 .199-.079.39-.22.53l-5.621 5.621c-.14.14-.331.22-.53.22H6.75A.75.75 0 016 14.25V3.75C6 3.336 6.336 3 6.75 3z" />
</svg>

<<<<<<< HEAD
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
     stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M12 9v3m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
=======
@props(['class' => 'w-6 h-6', 'fill' => 'currentColor'])

{{-- A simple, widely-used Exclamation Mark (e.g., in a triangle) SVG --}}
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="{{ $fill }}" xmlns="http://www.w3.org/2000/svg">
    <path
        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"
        fill-rule="evenodd"
        clip-rule="evenodd"
    />
</svg>
>>>>>>> 7e83adcc57e82f8f1329296a348b78a0cd654d00

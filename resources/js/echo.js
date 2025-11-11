import Echo from 'laravel-echo';

import Pusher from 'pusher-js';  // ✅ CRITICAL: Add this import

window.Pusher = Pusher;  // ✅ CRITICAL: Expose Pusher globally

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ??'127.0.0.1',
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

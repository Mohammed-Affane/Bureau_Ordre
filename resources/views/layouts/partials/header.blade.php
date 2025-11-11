<div class="sticky top-0 z-40 flex h-16 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Mobile menu -->
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" id="mobile-menu-button">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="flex flex-1 justify-end items-center gap-x-4 lg:gap-x-6">
        <!-- 🔔 Notification Bell -->
        <div class="relative">
            <button id="notif-bell" class="relative focus:outline-none">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span id="notif-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1 hidden"></span>
            </button>

            <!-- Dropdown -->
            <div id="notif-dropdown" class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50 hidden">
                <div id="notif-list" class="max-h-96 overflow-y-auto"></div>
            </div>
        </div>

        <!-- 👤 Profile -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="-m-1.5 flex items-center p-1.5">
                <img class="h-8 w-8 rounded-full bg-gray-50"
                     src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF"
                     alt="{{ auth()->user()->name }}">
                <span class="hidden lg:flex lg:items-center">
                    <span class="ml-4 text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                    <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </span>
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 x-transition
                 class="absolute right-0 mt-2.5 w-32 rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left px-3 py-1 text-sm text-gray-900 hover:bg-gray-50">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 🪄 Toast Container -->
<div id="notif-toast-area" class="fixed top-20 right-4 z-50 w-96 max-w-full space-y-2"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo) {
        console.error('❌ Echo not initialized. Check echo.js');
        return;
    }

    const userId = {{ auth()->id() }};
    let notifications = [];

    const notifBell = document.getElementById('notif-bell');
    const notifCount = document.getElementById('notif-count');
    const notifDropdown = document.getElementById('notif-dropdown');
    const notifList = document.getElementById('notif-list');
    const toastArea = document.getElementById('notif-toast-area');

    // 🔔 Listen for private notifications
    console.log(`📡 Listening on: App.Models.User.${userId}`);

    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            console.log('🎯 New notification received:', notification);

            notifications.unshift(notification);
            renderNotifications();
            showToast(notification);
        });

    // 🧩 Render notifications inside dropdown
    function renderNotifications() {
        notifList.innerHTML = notifications.length
            ? notifications.map(n => `
                <div class="p-3 border-b hover:bg-gray-50 transition">
                    <div class="font-semibold text-sm text-gray-900">${n.message || n.data?.message}</div>
                    ${n.data?.courrier_id ? `
                        <div class="text-xs text-gray-500">Courrier Ref: ${n.data.reference || '—'}</div>
                        <button onclick="window.location.href='/courriers/${n.data.courrier_id}'"
                                class="text-indigo-600 text-xs mt-1">Voir courrier</button>` : ''}
                </div>
            `).join('')
            : '<div class="p-3 text-gray-500 text-sm text-center">Aucune notification</div>';

        notifCount.textContent = notifications.length;
        notifCount.classList.toggle('hidden', notifications.length === 0);
    }

    // 🔥 Toast popup for new notification
    function showToast(notification) {
        const toast = document.createElement('div');
        toast.className = 'bg-white border-l-4 border-blue-500 rounded-lg shadow-lg p-4 animate-slideIn';

        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${notification.message || notification.data?.message}</p>
                    ${notification.data?.courrier_id ? `
                        <p class="mt-1 text-xs text-gray-500">Courrier Ref: ${notification.data.reference || '—'}</p>` : ''}
                    <p class="mt-1 text-xs text-gray-400">${new Date().toLocaleTimeString('fr-FR')}</p>
                </div>
                <button class="ml-4 text-gray-400 hover:text-gray-600" onclick="this.closest('div[role=alert]').remove()">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293
                              4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293
                              4.293a1 1 0 01-1.414-1.414L8.586 10
                              4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;
        toast.setAttribute('role', 'alert');

        toastArea.prepend(toast);
        setTimeout(() => toast.remove(), 8000);
    }

    // 🧭 Toggle dropdown
    notifBell.addEventListener('click', () => {
        notifDropdown.classList.toggle('hidden');
    });

    // 🕓 Load existing notifications
    fetch('/user/notifications')
        .then(res => res.json())
        .then(data => {
            notifications = data || [];
            renderNotifications();
        })
        .catch(err => console.warn('⚠️ Cannot load old notifications', err));
});
</script>


<style>
@keyframes slideIn {
  from { transform: translateX(400px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}
.animate-slideIn {
  animation: slideIn 0.3s ease-out;
}
</style>

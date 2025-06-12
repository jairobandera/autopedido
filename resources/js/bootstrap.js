import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

if (import.meta.env.VITE_PUSHER_APP_KEY && document.getElementById('row-pedidos')) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        wsHost: import.meta.env.VITE_PUSHER_APP_HOST || window.location.hostname,
        wsPort: import.meta.env.VITE_PUSHER_APP_PORT || 6001,
        forceTLS: import.meta.env.VITE_PUSHER_APP_SCHEME === 'https',
        encrypted: false,
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
    });
}

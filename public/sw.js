const CACHE_NAME = 'viantryp-v1';
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
    '/favicon.png',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png'
];

// Install: cache static assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(STATIC_ASSETS);
        }).catch(err => {
            console.warn('[SW] Failed to cache some assets:', err);
        })
    );
    self.skipWaiting();
});

// Activate: remove old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(name => name !== CACHE_NAME)
                    .map(name => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Fetch: Network First strategy (always fresh from server, fallback to cache)
self.addEventListener('fetch', event => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') return;

    // Skip browser-sync, hot reload, and external requests
    const url = new URL(event.request.url);
    if (url.hostname !== self.location.hostname) return;

    // Skip API calls and admin routes - always fresh from network
    if (url.pathname.startsWith('/api/') ||
        url.pathname.startsWith('/sanctum/') ||
        url.pathname.startsWith('/telescope/')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Cache successful responses for static assets
                if (response.ok && (
                    url.pathname.startsWith('/css/') ||
                    url.pathname.startsWith('/js/') ||
                    url.pathname.startsWith('/images/') ||
                    url.pathname.startsWith('/icons/') ||
                    url.pathname.startsWith('/fonts/') ||
                    url.pathname.startsWith('/build/')
                )) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Fallback to cache if network fails
                return caches.match(event.request).then(cached => {
                    if (cached) return cached;
                    // Return offline page for navigation requests
                    if (event.request.destination === 'document') {
                        return caches.match('/');
                    }
                });
            })
    );
});

// Push notifications support (for future use)
self.addEventListener('push', event => {
    if (!event.data) return;
    const data = event.data.json();
    const options = {
        body: data.body || 'Nueva notificacion de Viantryp',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
        data: { url: data.url || '/' }
    };
    event.waitUntil(
        self.registration.showNotification(data.title || 'Viantryp', options)
    );
});

// Notification click: open the app
self.addEventListener('notificationclick', event => {
    event.notification.close();
    const targetUrl = event.notification.data?.url || '/dashboard';
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(clientList => {
            for (const client of clientList) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});

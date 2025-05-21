self.addEventListener('install', function (event) {
    console.log('[Service Worker] Installing...');
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    console.log('[Service Worker] Activating...');
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function (response) {
            return response || fetch(event.request);
        })
    );
});

self.addEventListener('push', function(event) {
    const data = event.data?.json() || {};

    console.log('[Service Worker] Получено уведомление:', data);

    const options = {
        body: data.body,
        icon: data.icon || '/icon.png',
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Уведомление', options)
    );
});

self.addEventListener('notificationclick', function(event) {
    const action = event.action;
    if (action) {
        // Пользователь нажал на кнопку
        event.waitUntil(
            clients.openWindow(action)
        );
    } else {
        // Пользователь кликнул по уведомлению, но не по кнопке
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

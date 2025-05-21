import './bootstrap';

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open('my-cache').then(function(cache) {
            return cache.addAll([
                '/',
                '/index.html',
                '/css/app.css',
                '/js/app.js',
            ]);
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request);
        })
    );
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
        .then(function(registration) {
            console.log('Service Worker зарегистрирован с областью:', registration.scope);
        })
        .catch(function(error) {
            console.log('Ошибка регистрации Service Worker:', error);
        });
}
await fetch('/save-subscription', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    credentials: 'include', // обязательно, чтобы куки сессии передавались
    body: JSON.stringify(subscriptionData)
});


if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.register('/service-worker.js')
        .then(function (registration) {
            console.log('Service Worker зарегистрирован: ', registration);

            // Запрашиваем разрешение на уведомления
            Notification.requestPermission().then(function (permission) {
                if (permission === 'granted') {
                    console.log('Permission granted for push notifications');

                    // Создаём подписку
                    registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: 'BOBMaeP5iDVpfogCc8Fs5zkq_yfxWn4vZ0QKWpxz_TgOuJF98xk9pat7ePGnjyYM-Z-7fPEKYlWSfjv8OXTAIqE'
                    }).then(function (subscription) {
                        // Отправляем подписку на сервер для сохранения в БД
                        fetch('/save-subscription', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(subscription)
                        });
                    });
                }
            });
        });
}

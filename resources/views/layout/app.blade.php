<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="{{asset('js/vue.global.js')}}"></script>
    <link rel="manifest" href="{{asset('manifest.json')}}">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="{{asset('icons/icon-mini.png')}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
@include('layout.navbar')
@yield('main')
<script>
    const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(async function (registration) {
                console.log('Service Worker зарегистрирован:', registration);

                const permission = await Notification.requestPermission();
                if (permission !== "granted") {
                    console.warn('Push уведомления не разрешены пользователем');
                    return;
                }

                // Проверка существующей подписки
                const existingSubscription = await registration.pushManager.getSubscription();
                if (existingSubscription) {
                    // Удаляем старую подписку
                    try {
                        await existingSubscription.unsubscribe();
                        console.log('Старая подписка удалена');
                    } catch (e) {
                        console.warn('Не удалось удалить старую подписку:', e);
                    }
                }

                // Создание новой подписки
                registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
                }).then(subscription => {
                    // Отправка новой подписки на сервер
                    fetch('/save-subscription', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(subscription)
                    });
                }).catch(function (error) {
                    console.error('Ошибка подписки на push уведомления:', error);
                });
            })
            .catch(function (error) {
                console.error('Ошибка регистрации Service Worker:', error);
            });
    }
</script>
</body>
</html>
<style>
    @font-face{
        font-family: 'Involve medium';
        src: url("{{asset('fonts/Involve/Involve-Medium.ttf')}}");
    }

    @font-face{
        font-family: 'Involve SemiBold';
        src: url("{{asset('fonts/Involve/Involve-SemiBold.ttf')}}");
    }

    @font-face{
        font-family: 'Involve Bold';
        src: url("{{asset('fonts/Involve/Involve-Bold.ttf')}}");
    }
</style>

<?php

return [
    'channels' => [
        'mail' => \Illuminate\Notifications\Channels\MailChannel::class,
        'database' => \Illuminate\Notifications\Channels\DatabaseChannel::class,
        'broadcast' => \Illuminate\Notifications\Channels\BroadcastChannel::class,
        'webpush' => \NotificationChannels\WebPush\WebPushChannel::class,
    ],
];

<?php

namespace Dartui\SmsGateway\Notification;

use Dartui\SmsGateway\Client;
use Dartui\SmsGateway\Notification\Message;
use Illuminate\Notifications\Notification;

class Channel
{
    /**
     * SMS Gateway client.
     *
     * @var \Dartui\SmsGateway\Client;
     */
    protected $client;

    /**
     * Create a SMS Gateway channel.
     *
     * @param \Dartui\SmsGateway\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSmsGateway($notifiable);

        if (is_string($message)) {
            $message = new Message($message);
        }

        if ($to = $notifiable->routeNotificationForSmsGateway()) {
            $message->number($to);
        }

        if (!$message->number) {
            return;
        }

        $this->client->sendMessage($message);
    }
}

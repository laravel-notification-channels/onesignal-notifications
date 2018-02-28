<?php

namespace NotificationChannels\OneSignal;

use Berkayk\OneSignal\OneSignalClient;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\Exceptions\CouldNotSendNotification;

class OneSignalChannel
{
    /** @var OneSignalClient */
    protected $oneSignal;

    public function __construct(OneSignalClient $oneSignal)
    {
        $this->oneSignal = $oneSignal;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \NotificationChannels\OneSignal\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $userIds = $notifiable->routeNotificationFor('OneSignal')) {
            return;
        }

        $payload = $notification->toOneSignal($notifiable)->toArray();

        if (is_array($userIds)) {
            if (array_key_exists('email', $userIds)) {
                $payload['filters'] = collect([['field' => 'email', 'value' => $userIds['email']]]);
            } elseif (array_key_exists('tags', $userIds)) {
                $payload['tags'] = collect([$userIds['tags']]);
            }
        } else {
            $payload['include_player_ids'] = collect($userIds);
        }

        /** @var ResponseInterface $response */
        $response = $this->oneSignal->sendNotificationCustom(
            $this->payload($notifiable, $notification, $userIds)
        );

        if ($response->getStatusCode() !== 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $response;
    }

    /**
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param mixed $targeting
     *
     * @return array
     */
    protected function payload($notifiable, $notification, $userIds)
    {
        return OneSignalPayloadFactory::make($notifiable, $notification, $userIds);
    }
}

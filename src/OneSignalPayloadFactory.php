<?php

namespace NotificationChannels\OneSignal;

use Illuminate\Notifications\Notification;

class OneSignalPayloadFactory
{
    /**
     * Make a one signal notification payload.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param mixed $targeting
     *
     * @return array
     */
    public static function make($notifiable, Notification $notification, $targeting): array
    {
        $payload = $notification->toOneSignal($notifiable)->toArray();

        if (static::isTargetingEmail($targeting)) {
            $payload['filters'] = collect([['field' => 'email', 'value' => $targeting['email']]]);
        } elseif (static::isTargetingTags($targeting)) {
            $array = $targeting['tags'];
            $res = count($array) == count($array, COUNT_RECURSIVE);
            if ($res) {
                $payload['tags'] = collect([$targeting['tags']]);
            } else {
                $payload['tags'] = collect($targeting['tags']);
            }
        } elseif (static::isTargetingIncludedSegments($targeting)) {
            $payload['included_segments'] = collect($targeting['included_segments']);
        } elseif (static::isTargetingExcludedSegments($targeting)) {
            $payload['excluded_segments'] = collect($targeting['excluded_segments']);
        } elseif (static::isTargetingExternalUserIds($targeting)) {
            $payload['include_external_user_ids'] = collect($targeting['include_external_user_ids']);
            $payload['channel_for_external_user_ids'] = 'push';
        } else {
            $payload['include_player_ids'] = collect($targeting);
        }

        return $payload;
    }

    /**
     * @param mixed $targeting
     *
     * @return bool
     */
    protected static function isTargetingIncludedSegments($targeting)
    {
        return is_array($targeting) && array_key_exists('included_segments', $targeting);
    }

    /**
     * @param mixed $targeting
     *
     * @return bool
     */
    protected static function isTargetingExternalUserIds($targeting)
    {
        return is_array($targeting) && array_key_exists('include_external_user_ids', $targeting);
    }

    /**
     * @param mixed $targeting
     *
     * @return bool
     */
    protected static function isTargetingExcludedSegments($targeting)
    {
        return is_array($targeting) && array_key_exists('excluded_segments', $targeting);
    }

    /**
     * @param mixed $targeting
     *
     * @return bool
     */
    protected static function isTargetingEmail($targeting)
    {
        return is_array($targeting) && array_key_exists('email', $targeting);
    }

    /**
     * @param mixed $targeting
     *
     * @return bool
     */
    protected static function isTargetingTags($targeting)
    {
        return is_array($targeting) && array_key_exists('tags', $targeting);
    }
}

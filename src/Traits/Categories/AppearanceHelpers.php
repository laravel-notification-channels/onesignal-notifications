<?php

namespace NotificationChannels\OneSignal\Traits\Categories;

trait AppearanceHelpers
{
    /**
     * Set the iOS badge increment count.
     *
     * @param int $count
     *
     * @return $this
     */
    public function incrementIosBadgeCount(int $count = 1)
    {
        return $this->setParameter('ios_badgeType', 'Increase')
            ->setParameter('ios_badgeCount', $count);
    }

    /**
     * Set the iOS badge decrement count.
     *
     * @param int $count
     *
     * @return $this
     */
    public function decrementIosBadgeCount(int $count = 1)
    {
        return $this->setParameter('ios_badgeType', 'Increase')
            ->setParameter('ios_badgeCount', -1 * $count);
    }

    /**
     * Set the iOS badge count.
     *
     * @param int $count
     *
     * @return $this
     */
    public function setIosBadgeCount(int $count)
    {
        return $this->setParameter('ios_badgeType', 'SetTo')
            ->setParameter('ios_badgeCount', $count);
    }

    /**
     * Set the iOS Sound.
     *
     * @param string $soundUrl
     *
     * @return $this
     */
    public function setIosSound(string $soundUrl)
    {
        return $this->setParameter('ios_sound', $soundUrl);
    }

    /**
     * Set the Android Sound.
     *
     * @param string $soundUrl
     *
     * @return $this
     */
    public function setAndroidSound(string $soundUrl)
    {
        return $this->setParameter('android_sound', $soundUrl);
    }

    /**
     * Set the Windows Sound.
     *
     * @param string $soundUrl
     *
     * @return $this
     */
    public function setWindowsSound(string $soundUrl)
    {
        return $this->setParameter('wp_sound', $soundUrl)
            ->setParameter('wp_wns_sound', $soundUrl);
    }

    /**
     * Set the Sound for all Systems.
     *
     * @param string $soundUrl
     *
     * @return $this
     */
    public function setSound(string $soundUrl)
    {
        return $this->setAndroidSound($soundUrl)
            ->setIosSound($soundUrl)
            ->setWindowsSound($soundUrl);
    }

    /**
     * Set the message icon.
     *
     * @param string $iconPath
     *
     * @deprecated use setIcon instead
     *
     * @return $this
     */
    public function icon(string $iconPath)
    {
        return $this->setIcon($iconPath);
    }

    /**
     * Set the message icon.
     *
     * @param string $iconPath
     *
     * @return $this
     */
    public function setIcon(string $iconPath)
    {
        return $this->setParameter('chrome_web_icon', $iconPath)
            ->setParameter('chrome_icon', $iconPath)
            ->setParameter('adm_small_icon', $iconPath)
            ->setParameter('small_icon', $iconPath);
    }
    
    /**
     * Set the message large icon.
     *
     * @param string $iconPath
     *
     * @return $this
     */
    public function setLargeIcon(string $iconPath)
    {
        return $this->setParameter('large_icon', $iconPath)
            ->setParameter('huawei_large_icon', $iconPath)
            ->setParameter('adm_large_icon', $iconPath);
    }
}

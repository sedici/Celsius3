<?php

namespace Celsius3\NotificationBundle\Entity;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationSettings.
 *
 * @ORM\Table(name="notification_settings")
 * @ORM\Entity(repositoryClass="Celsius3\NotificationBundle\Repository\BaseRepository")
 */
class NotificationSettings
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var BaseUser
     *
     * @ORM\ManyToOne(targetEntity="\Celsius3\Entity\BaseUser", inversedBy="notificationSettings")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Instance
     *
     * @ORM\ManyToOne(targetEntity="\Celsius3\Entity\Instance")
     * @ORM\JoinColumn(name="instance", referencedColumnName="id")
     */
    private $instance;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="subscribedToInterfaceNotifications", type="boolean")
     */
    private $subscribedToInterfaceNotifications;

    /**
     * @var bool
     *
     * @ORM\Column(name="subscribedToEmailNotifications", type="boolean")
     */
    private $subscribedToEmailNotifications;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return NotificationSettings
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set subscribedToInterfaceNotifications.
     *
     * @param bool $subscribedToInterfaceNotifications
     *
     * @return NotificationSettings
     */
    public function setSubscribedToInterfaceNotifications($subscribedToInterfaceNotifications)
    {
        $this->subscribedToInterfaceNotifications = $subscribedToInterfaceNotifications;

        return $this;
    }

    /**
     * Get subscribedToInterfaceNotifications.
     *
     * @return bool
     */
    public function getSubscribedToInterfaceNotifications()
    {
        return $this->subscribedToInterfaceNotifications;
    }

    /**
     * Set subscribedToEmailNotifiations.
     *
     * @param bool $subscribedToEmailNotifiations
     *
     * @return NotificationSettings
     */
    public function setSubscribedToEmailNotifications($subscribedToEmailNotifications)
    {
        $this->subscribedToEmailNotifications = $subscribedToEmailNotifications;

        return $this;
    }

    /**
     * Get subscribedToEmailNotifiations.
     *
     * @return bool
     */
    public function getSubscribedToEmailNotifications()
    {
        return $this->subscribedToEmailNotifications;
    }

    /**
     * Set user.
     *
     * @param BaseUser $user
     *
     * @return NotificationSettings
     */
    public function setUser(BaseUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return NotificationSettings
     */
    public function setInstance(Instance $instance = null)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance
     */
    public function getInstance()
    {
        return $this->instance;
    }
}

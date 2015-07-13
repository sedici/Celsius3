<?php

namespace Celsius3\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationSettings
 *
 * @ORM\Table(name="notification_settings")
 * @ORM\Entity()
 */
class NotificationSettings
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Celsius3\CoreBundle\Entity\BaseUser
     *
     * @ORM\ManyToOne(targetEntity="\Celsius3\CoreBundle\Entity\BaseUser", inversedBy="notificationSettings")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \Celsius3\CoreBundle\Entity\Instance
     *
     * @ORM\ManyToOne(targetEntity="\Celsius3\CoreBundle\Entity\Instance")
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
     * @var boolean
     *
     * @ORM\Column(name="subscribedToInterfaceNotifications", type="boolean")
     */
    private $subscribedToInterfaceNotifications;

    /**
     * @var boolean
     *
     * @ORM\Column(name="subscribedToEmailNotifications", type="boolean")
     */
    private $subscribedToEmailNotifications;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set subscribedToInterfaceNotifications
     *
     * @param boolean $subscribedToInterfaceNotifications
     *
     * @return NotificationSettings
     */
    public function setSubscribedToInterfaceNotifications($subscribedToInterfaceNotifications)
    {
        $this->subscribedToInterfaceNotifications = $subscribedToInterfaceNotifications;

        return $this;
    }

    /**
     * Get subscribedToInterfaceNotifications
     *
     * @return boolean
     */
    public function getSubscribedToInterfaceNotifications()
    {
        return $this->subscribedToInterfaceNotifications;
    }

    /**
     * Set subscribedToEmailNotifiations
     *
     * @param boolean $subscribedToEmailNotifiations
     *
     * @return NotificationSettings
     */
    public function setSubscribedToEmailNotifications($subscribedToEmailNotifications)
    {
        $this->subscribedToEmailNotifications = $subscribedToEmailNotifications;

        return $this;
    }

    /**
     * Get subscribedToEmailNotifiations
     *
     * @return boolean
     */
    public function getSubscribedToEmailNotifications()
    {
        return $this->subscribedToEmailNotifications;
    }

    /**
     * Set user
     *
     * @param \Celsius3\CoreBundle\Entity\BaseUser $user
     *
     * @return NotificationSettings
     */
    public function setUser(\Celsius3\CoreBundle\Entity\BaseUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Celsius3\CoreBundle\Entity\BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set instance
     *
     * @param \Celsius3\CoreBundle\Entity\Instance $instance
     *
     * @return NotificationSettings
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance = null)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return \Celsius3\CoreBundle\Entity\Instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

}

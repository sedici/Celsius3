<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\Entity;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Template;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\NotificationRepository")
 * @ORM\Table(name="notification", indexes={
 *   @ORM\Index(name="idx_viewed", columns={"viewed"}),
 *   @ORM\Index(name="idx_template", columns={"template_id"}),
 *   @ORM\Index(name="idx_object_user", columns={"base_user_notification_id"}),
 *   @ORM\Index(name="idx_object_message", columns={"message_notification_id"}),
 *   @ORM\Index(name="idx_object_event", columns={"event_notification_id"})
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "message"="MessageNotification",
 *   "baseuser"="BaseUserNotification",
 *   "event"="EventNotification",
 * })
 */
abstract class Notification
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $cause;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $viewed = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field="viewed", value="true")
     */
    private $viewedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\NotificationTemplate")
     * @ORM\JoinColumn(name="template_id", referencedColumnName="id", nullable=false)
     */
    private $template;

    /**
     * @ORM\ManyToMany(targetEntity="Celsius3\Entity\BaseUser")
     * @ORM\JoinTable(name="notification_receiver",
     *      joinColumns={@ORM\JoinColumn(name="notification_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="receiver_id", referencedColumnName="id")}
     *      )
     */
    private $receivers;

    /**
     * @ORM\ManyToMany(targetEntity="Celsius3\Entity\BaseUser")
     * @ORM\JoinTable(name="notification_viewer",
     *      joinColumns={@ORM\JoinColumn(name="notification_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="receiver_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $viewer;

    public function __construct()
    {
        $this->receivers = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cause.
     *
     * @param string $cause
     *
     * @return self
     */
    public function setCause($cause)
    {
        $this->cause = $cause;

        return $this;
    }

    /**
     * Get cause.
     *
     * @return string $cause
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * Set viewed.
     *
     * @param bool $viewed
     *
     * @return self
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * Get viewed.
     *
     * @return bool $viewed
     */
    public function isViewed()
    {
        return $this->viewed;
    }

    /**
     * Set viewedAt.
     *
     * @param date $viewedAt
     *
     * @return self
     */
    public function setViewedAt($viewedAt)
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }

    /**
     * Get viewedAt.
     *
     * @return date $viewedAt
     */
    public function getViewedAt()
    {
        return $this->viewedAt;
    }

    /**
     * Set object.
     *
     * @param $object
     *
     * @return self
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object.
     *
     * @return $object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set source.
     *
     * @return self
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set template.
     *
     * @param Template $template
     *
     * @return self
     */
    public function setTemplate(Template $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template.
     *
     * @return Template $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Add receivers.
     *
     * @param BaseUser $receivers
     */
    public function addReceiver(BaseUser $receivers)
    {
        $this->receivers[] = $receivers;
    }

    /**
     * Remove receivers.
     *
     * @param BaseUser $receivers
     */
    public function removeReceiver(BaseUser $receivers)
    {
        $this->receivers->removeElement($receivers);
    }

    /**
     * Get receivers.
     *
     * @return Collection $receivers
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * Set viewer.
     *
     * @param BaseUser $viewer
     *
     * @return self
     */
    public function setViewer(BaseUser $viewer)
    {
        $this->viewer = $viewer;

        return $this;
    }

    /**
     * Get viewer.
     *
     * @return BaseUser $viewer
     */
    public function getViewer()
    {
        return $this->viewer;
    }

    /**
     * Get viewed.
     *
     * @return bool
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Add viewer.
     *
     * @param BaseUser $viewer
     *
     * @return Notification
     */
    public function addViewer(BaseUser $viewer)
    {
        $this->viewer[] = $viewer;

        return $this;
    }

    /**
     * Remove viewer.
     *
     * @param BaseUser $viewer
     */
    public function removeViewer(BaseUser $viewer)
    {
        $this->viewer->removeElement($viewer);
    }
}

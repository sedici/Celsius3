<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\NotificationBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableDocument;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="Celsius3\NotificationBundle\Repository\NotificationRepository")
 */
class Notification
{

    use TimestampableDocument;

    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $cause;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @ODM\Boolean
     */
    private $isViewed = false;

    /**
     * @ODM\Date
     * @Gedmo\Timestampable(on="change", field="isViewed", value="true")
     */
    private $viewedAt;

    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne
     */
    private $object;

    /**
     * @ODM\ReferenceOne
     */
    private $source;

    /**
     * @ODM\ReferenceOne(targetDocument="NotificationTemplate")
     */
    private $template;

    /**
     * @ODM\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    private $receivers;

    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    private $viewer;

    public function __construct()
    {
        $this->receivers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cause
     *
     * @param  string $cause
     * @return self
     */
    public function setCause($cause)
    {
        $this->cause = $cause;

        return $this;
    }

    /**
     * Get cause
     *
     * @return string $cause
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * Set isViewed
     *
     * @param  boolean $isViewed
     * @return self
     */
    public function setIsViewed($isViewed)
    {
        $this->isViewed = $isViewed;

        return $this;
    }

    /**
     * Get isViewed
     *
     * @return boolean $isViewed
     */
    public function getIsViewed()
    {
        return $this->isViewed;
    }

    /**
     * Set viewedAt
     *
     * @param  date $viewedAt
     * @return self
     */
    public function setViewedAt($viewedAt)
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }

    /**
     * Get viewedAt
     *
     * @return date $viewedAt
     */
    public function getViewedAt()
    {
        return $this->viewedAt;
    }

    /**
     * Set object
     *
     * @param $object
     * @return self
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return $object
     */
    public function getObject()
    {
        return $this->object;
    }
    
    /**
     * Set source
     *
     * @return self
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set template
     *
     * @param  Celsius3\CoreBundle\Document\Template $template
     * @return self
     */
    public function setTemplate(\Celsius3\CoreBundle\Document\Template $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return Celsius3\CoreBundle\Document\Template $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Add receivers
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $receivers
     */
    public function addReceiver(\Celsius3\CoreBundle\Document\BaseUser $receivers)
    {
        $this->receivers[] = $receivers;
    }

    /**
     * Remove receivers
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $receivers
     */
    public function removeReceiver(\Celsius3\CoreBundle\Document\BaseUser $receivers)
    {
        $this->receivers->removeElement($receivers);
    }

    /**
     * Get receivers
     *
     * @return Doctrine\Common\Collections\Collection $receivers
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * Set viewer
     *
     * @param  Celsius3\CoreBundle\Document\BaseUser $viewer
     * @return self
     */
    public function setViewer(\Celsius3\CoreBundle\Document\BaseUser $viewer)
    {
        $this->viewer = $viewer;

        return $this;
    }

    /**
     * Get viewer
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $viewer
     */
    public function getViewer()
    {
        return $this->viewer;
    }

}
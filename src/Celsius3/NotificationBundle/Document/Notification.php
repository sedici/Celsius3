<?php

namespace Celsius3\NotificationBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableDocument;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\NotificationBundle\Repository\NotificationRepository")
 */
class Notification
{

    use TimestampableDocument;

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $cause;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isViewed = false;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="change", field="isViewed", value="true")
     */
    private $viewedAt;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne
     */
    private $object;

    /**
     * @MongoDB\ReferenceOne
     */
    private $source;

    /**
     * @MongoDB\ReferenceOne(targetDocument="NotificationTemplate")
     */
    private $template;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    private $receivers;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
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

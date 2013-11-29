<?php

namespace Celsius3\ChatBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableDocument;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\NotificationBundle\Repository\NotificationRepository")
 */
class ChatMessage
{

    use TimestampableDocument;

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $message;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    private $sender;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Hive")
     */
    private $hive;

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
     * Set message
     *
     * @param string $message
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return string $message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set sender
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $sender
     * @return self
     */
    public function setSender(\Celsius3\CoreBundle\Document\BaseUser $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get sender
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set hive
     *
     * @param Celsius3\CoreBundle\Document\Hive $hive
     * @return self
     */
    public function setHive(\Celsius3\CoreBundle\Document\Hive $hive)
    {
        $this->hive = $hive;
        return $this;
    }

    /**
     * Get hive
     *
     * @return Celsius3\CoreBundle\Document\Hive $hive
     */
    public function getHive()
    {
        return $this->hive;
    }

}

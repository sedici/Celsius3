<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Document\Instance;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\OrderRepository")
 */
class Order
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\Type(type="integer", groups={"newOrder"})
     * @MongoDB\Int
     */
    private $code;
    /**
     * @Assert\NotBlank(groups={"newOrder"})
     * @MongoDB\String
     */
    private $type;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $created;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $searched;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $requested;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $approval_pending;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $received;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $delivered;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $cancelled;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $annulled;

    /**
     * @MongoDB\String
     */
    private $comments;

    /**
     * @MongoDB\EmbedOne(targetDocument="MaterialType")
     */
    private $materialData;

    /**
     * @Assert\NotNull(groups={"Default", "newOrder"})
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $owner;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $operator;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $librarian;

    /**
     * @Assert\NotNull(groups={"Default", "newOrder"})
     * @MongoDB\ReferenceOne(targetDocument="Instance")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceMany(targetDocument="File", mappedBy="order")
     */
    private $files;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Event", mappedBy="order")
     */
    private $events;

    /**
     * @MongoDB\ReferenceMany(targetDocument="State", mappedBy="order")
     */
    private $states;
    
     /**
     * @Assert\NotNull()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isLiblink = false;

    public function __toString()
    {
        return strval($this->getCode());
    }

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getFilesForEvent($event)
    {
        return $this->getFiles()
                ->filter(
                        function ($entry) use ($event)
                        {
                            return ($entry->getEvent()->getId()
                                    == $event->getId());
                        });
    }

    public function getFilesForDownload()
    {
        $instance = $this->getInstance();
        return $this->getFiles()
                ->filter(
                        function ($entry) use ($instance)
                        {
                            return ($entry->getEvent()->getInstance()->getId()
                                    == $instance->getId());
                        });
    }

    public function getNotDownloadedFiles()
    {
        $instance = $this->getInstance();
        return $this->getFiles()
                ->filter(
                        function ($entry) use ($instance)
                        {
                            return ($entry->getEvent()->getInstance()->getId()
                                    == $instance->getId()
                                    && !$entry->getIsDownloaded());
                        });
    }

    public function hasState($name, Instance $instance)
    {
        return ($this->getStates()
                ->filter(
                        function ($entry) use ($name, $instance)
                        {
                            return ($entry->getType()->getName() == $name
                                    && $entry->getInstance()->getId()
                                            == $instance->getId());
                        })->count() > 0);
    }

    public function getState($name, Instance $instance)
    {
        $result = $this->getStates()
                ->filter(
                        function ($entry) use ($name, $instance)
                        {
                            return ($entry->getType()->getName() == $name
                                    && $entry->getInstance()->getId()
                                            == $instance->getId());
                        })->first();
        return false !== $result ? $result : null;
    }

    public function getCurrentState(Instance $instance)
    {
        $result = $this->getStates()
                ->filter(
                        function ($entry) use ($instance)
                        {
                            return ($entry->getIsCurrent()
                                    && $entry->getInstance()->getId()
                                            == $instance->getId());
                        })->first();
        return false !== $result ? $result : null;
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
     * Set code
     *
     * @param int $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return int $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set created
     *
     * @param date $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return date $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set searched
     *
     * @param date $searched
     * @return self
     */
    public function setSearched($searched)
    {
        $this->searched = $searched;
        return $this;
    }

    /**
     * Get searched
     *
     * @return date $searched
     */
    public function getSearched()
    {
        return $this->searched;
    }

    /**
     * Set requested
     *
     * @param date $requested
     * @return self
     */
    public function setRequested($requested)
    {
        $this->requested = $requested;
        return $this;
    }

    /**
     * Get requested
     *
     * @return date $requested
     */
    public function getRequested()
    {
        return $this->requested;
    }

    /**
     * Set approval_pending
     *
     * @param date $approvalPending
     * @return self
     */
    public function setApprovalPending($approvalPending)
    {
        $this->approval_pending = $approvalPending;
        return $this;
    }

    /**
     * Get approval_pending
     *
     * @return date $approvalPending
     */
    public function getApprovalPending()
    {
        return $this->approval_pending;
    }

    /**
     * Set received
     *
     * @param date $received
     * @return self
     */
    public function setReceived($received)
    {
        $this->received = $received;
        return $this;
    }

    /**
     * Get received
     *
     * @return date $received
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * Set delivered
     *
     * @param date $delivered
     * @return self
     */
    public function setDelivered($delivered)
    {
        $this->delivered = $delivered;
        return $this;
    }

    /**
     * Get delivered
     *
     * @return date $delivered
     */
    public function getDelivered()
    {
        return $this->delivered;
    }

    /**
     * Set cancelled
     *
     * @param date $cancelled
     * @return self
     */
    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;
        return $this;
    }

    /**
     * Get cancelled
     *
     * @return date $cancelled
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * Set annulled
     *
     * @param date $annulled
     * @return self
     */
    public function setAnnulled($annulled)
    {
        $this->annulled = $annulled;
        return $this;
    }

    /**
     * Get annulled
     *
     * @return date $annulled
     */
    public function getAnnulled()
    {
        return $this->annulled;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get comments
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set materialData
     *
     * @param Celsius3\CoreBundle\Document\MaterialType $materialData
     * @return self
     */
    public function setMaterialData(\Celsius3\CoreBundle\Document\MaterialType $materialData = null)
    {
        $this->materialData = $materialData;
        return $this;
    }

    /**
     * Get materialData
     *
     * @return Celsius3\CoreBundle\Document\MaterialType $materialData
     */
    public function getMaterialData()
    {
        return $this->materialData;
    }

    /**
     * Set owner
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $owner
     * @return self
     */
    public function setOwner(\Celsius3\CoreBundle\Document\BaseUser $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get owner
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set operator
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Document\BaseUser $operator = null)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set librarian
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $librarian
     * @return self
     */
    public function setLibrarian(\Celsius3\CoreBundle\Document\BaseUser $librarian)
    {
        $this->librarian = $librarian;
        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Set instance
     *
     * @param Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function addFile(\Celsius3\CoreBundle\Document\File $files)
    {
        $this->files[] = $files;
    }

    /**
     * Remove files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function removeFile(\Celsius3\CoreBundle\Document\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add events
     *
     * @param Celsius3\CoreBundle\Document\Event $events
     */
    public function addEvent(\Celsius3\CoreBundle\Document\Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events
     *
     * @param Celsius3\CoreBundle\Document\Event $events
     */
    public function removeEvent(\Celsius3\CoreBundle\Document\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return Doctrine\Common\Collections\Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add states
     *
     * @param Celsius3\CoreBundle\Document\State $states
     */
    public function addState(\Celsius3\CoreBundle\Document\State $states)
    {
        $this->states[] = $states;
    }

    /**
     * Remove states
     *
     * @param Celsius3\CoreBundle\Document\State $states
     */
    public function removeState(\Celsius3\CoreBundle\Document\State $states)
    {
        $this->states->removeElement($states);
    }

    /**
     * Get states
     *
     * @return Doctrine\Common\Collections\Collection $states
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Set isLiblink
     *
     * @param boolean $isLiblink
     * @return self
     */
    public function setIsLiblink($isLiblink)
    {
        $this->isLiblink = $isLiblink;
        return $this;
    }

    /**
     * Get isLiblink
     *
     * @return boolean $isLiblink
     */
    public function getIsLiblink()
    {
        return $this->isLiblink;
    }
}

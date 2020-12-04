<?php

namespace Celsius3\TicketBundle\Entity;

use Celsius3\CoreBundle\Entity\BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity("")
 * @ORM\Table(name="ticket")
 */
class Ticket
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
    private $subject;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var Baseuser
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\BaseUser")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @var BaseUser
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\BaseUser")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    protected $updatedBy;

    /**
     * @var Baseuser
     *
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\BaseUser")
     * @ORM\JoinColumn(name="user_assigned_id", referencedColumnName="id")
     */
    protected $userAssigned;

    /**
     * @ORM\OneToMany(targetEntity="Celsius3\TicketBundle\Entity\TicketState", mappedBy="tickets")
     */
    protected $statusHistory;

    /**
     * @var statusCurrent
     * @ORM\ManyToOne(targetEntity="Celsius3\TicketBundle\Entity\TicketState")
     * @ORM\JoinColumn(name="status_current_id", referencedColumnName="id", nullable=true)
     */
    protected $statusCurrent;

    /**
     * @var category
     * @ORM\ManyToOne(targetEntity="Celsius3\TicketBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    protected $category;

    /**
     * @var priority
     * @ORM\ManyToOne(targetEntity="Celsius3\TicketBundle\Entity\Priority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    protected $priority;

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
     * Set subject.
     *
     * @param string $subject
     *
     * @return Ticket
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set text.
     *
     * @param string $text
     *
     * @return Ticket
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdBy.
     *
     * @param BaseUser $createdBy
     *
     * @return Ticket
     */
    public function setCreatedBy(BaseUser $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return BaseUser
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy.
     *
     * @param BaseUser $updatedBy
     *
     * @return Ticket
     */
    public function setUpdatedBy(BaseUser $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy.
     *
     * @return BaseUser
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->statusHistory = new ArrayCollection();
    }

    /**
     * Set statusCurrent.
     *
     * @param string $statusCurrent
     *
     * @return Ticket
     */
    public function setStatusCurrent($statusCurrent)
    {
        $this->statusCurrent = $statusCurrent;

        return $this;
    }

    /**
     * Get statusCurrent.
     *
     * @return string
     */
    public function getStatusCurrent()
    {
        return $this->statusCurrent;
    }

    /**
     * Set priority.
     *
     * @param string $priority
     *
     * @return Ticket
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Add statusHistory.
     *
     * @param TicketState $statusHistory
     *
     * @return Ticket
     */
    public function addStatusHistory(TicketState $statusHistory)
    {
        $this->statusHistory[] = $statusHistory;

        return $this;
    }

    /**
     * Remove statusHistory.
     *
     * @param TicketState $statusHistory
     */
    public function removeStatusHistory(TicketState $statusHistory)
    {
        $this->statusHistory->removeElement($statusHistory);
    }

    /**
     * Get statusHistory.
     *
     * @return Collection
     */
    public function getStatusHistory()
    {
        return $this->statusHistory;
    }

    /**
     * Set category.
     *
     * @param Category $category
     *
     * @return Ticket
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set created_at.
     *
     * @param \DateTime $created_at
     *
     * @return Ticket
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get created_at.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at.
     *
     * @param \DateTime $updated_at
     *
     * @return Ticket
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get updated_at.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set userAssigned.
     *
     * @param BaseUser $userAssigned
     *
     * @return Ticket
     */
    public function setUserAssigned(BaseUser $userAssigned = null)
    {
        $this->userAssigned = $userAssigned;

        return $this;
    }

    /**
     * Get userAssigned.
     *
     * @return BaseUser
     */
    public function getUserAssigned()
    {
        return $this->userAssigned;
    }
}

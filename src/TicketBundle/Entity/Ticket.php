<?php

namespace Celsius3\TicketBundle\Entity;

use Celsius3\Entity\BaseUser;
use Celsius3\Repository\BaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(BaseRepository::class)]
#[ORM\Table(name: 'ticket')]
class Ticket
{

    use TimestampableEntity;


    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    private string $subject;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'text')]
    private string $text;


    #[Gedmo\Blameable(on: 'create')]
    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    protected ?BaseUser $createdBy = null;


    #[Gedmo\Blameable(on: 'update')]
    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'id')]
    protected ?BaseUser $updatedBy = null;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: 'user_assigned_id', referencedColumnName: 'id')]
    protected ?BaseUser $userAssigned = null;


    #[ORM\OneToMany(targetEntity: TicketState::class, mappedBy: 'tickets')]
    protected Collection $statusHistory;


    #[ORM\ManyToOne(targetEntity: TicketState::class)]
    #[ORM\JoinColumn(name: 'status_current_id', referencedColumnName: 'id', nullable: true)]
    protected TicketState $statusCurrent;


    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: true)]
    protected Category $category;


    #[ORM\ManyToOne(targetEntity: Priority::class)]
    #[ORM\JoinColumn(name: 'priority_id', referencedColumnName: 'id')]
    protected Priority $priority;


    public function getId()
    {
        return $this->id;
    }


    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }


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
    public function setCreatedBy(?BaseUser $createdBy = null)
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
    public function setUpdatedBy(?BaseUser $updatedBy = null)
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


    public function setStatusCurrent(TicketState $statusCurrent): static
    {
        $this->statusCurrent = $statusCurrent;

        return $this;
    }


    public function getStatusCurrent(): TicketState
    {
        return $this->statusCurrent;
    }


    public function setPriority(Priority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }


    public function getPriority(): string
    {
        return $this->priority;
    }


    public function addStatusHistory(TicketState $statusHistory): static
    {
        $this->statusHistory[] = $statusHistory;

        return $this;
    }


    public function removeStatusHistory(TicketState $statusHistory): void
    {
        $this->statusHistory->removeElement($statusHistory);
    }


    public function getStatusHistory(): ArrayCollection
    {
        return $this->statusHistory;
    }


    public function setCategory(?Category $category = null): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }


    public function setUserAssigned(?BaseUser $userAssigned = null): static
    {
        $this->userAssigned = $userAssigned;

        return $this;
    }


    public function getUserAssigned(): BaseUser
    {
        return $this->userAssigned;
    }
}

<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 * @ODM\Indexes({
 *   @ODM\Index(keys={"name"="asc"}),
 *   @ODM\Index(keys={"position"="asc"}),
 * })
 * @ODM\Document(repositoryClass="Celsius3\CoreBundle\Repository\StateTypeRepository")
 */
class StateType
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
    private $name;
    /**
     * @Assert\NotBlank()
     * @ODM\Int
     */
    private $position;
    /**
     * @ODM\ReferenceMany(targetDocument="State", mappedBy="type")
     */
    private $states;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set position
     *
     * @param  int  $position
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int $position
     */
    public function getPosition()
    {
        return $this->position;
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
}
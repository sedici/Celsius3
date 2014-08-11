<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 */
class Hive
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @ODM\String
     */
    private $name;
    /**
     * @ODM\ReferenceMany(targetDocument="LegacyInstance", mappedBy="hive")
     */
    private $instances;
    /**
     * @ODM\ReferenceMany(targetDocument="Institution", mappedBy="hive")
     */
    private $institutions;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->instances = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add instance
     *
     * @param Celsius3\CoreBundle\Document\LegacyInstance $instance
     */
    public function addInstance(\Celsius3\CoreBundle\Document\LegacyInstance $instance)
    {
        $this->instances[] = $instance;
    }

    /**
     * Remove instance
     *
     * @param Celsius3\CoreBundle\Document\LegacyInstance $instance
     */
    public function removeInstance(\Celsius3\CoreBundle\Document\LegacyInstance $instance)
    {
        $this->instances->removeElement($instance);
    }

    /**
     * Get instances
     *
     * @return Doctrine\Common\Collections\Collection $instances
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * Add institution
     *
     * @param Celsius3\CoreBundle\Document\Institution $institution
     */
    public function addInstitution(\Celsius3\CoreBundle\Document\Institution $institution)
    {
        $this->institutions[] = $institution;
    }

    /**
     * Remove institution
     *
     * @param Celsius3\CoreBundle\Document\Institution $institution
     */
    public function removeInstitution(\Celsius3\CoreBundle\Document\Institution $institution)
    {
        $this->institutions->removeElement($institution);
    }

    /**
     * Get institutions
     *
     * @return Doctrine\Common\Collections\Collection $institutions
     */
    public function getInstitutions()
    {
        return $this->institutions;
    }
}
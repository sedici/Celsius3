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

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(collection="Instance")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({
 *   "legacy"="LegacyInstance",
 *   "current"="Instance"
 * })
 * @ODM\Indexes({
 *   @ODM\Index(keys={"hive.id"="asc"}),
 * })
 */
class LegacyInstance
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    protected $id;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    protected $name;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    protected $abbreviation;
    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     * @ODM\String
     */
    protected $website;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ODM\String
     */
    protected $email;
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @ODM\Boolean
     */
    protected $enabled = true;
    /**
     * @ODM\ReferenceMany(targetDocument="Institution", mappedBy="celsiusInstance")
     */
    protected $ownerInstitutions;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Hive", inversedBy="instances")
     */
    protected $hive;
    
    public function __construct()
    {
        $this->ownerInstitutions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function isCurrent()
    {
        return false;
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
     * Set abbreviation
     *
     * @param  string $abbreviation
     * @return self
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set website
     *
     * @param  string $website
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set enabled
     *
     * @param  boolean $enabled
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set hive
     *
     * @param  Celsius3\CoreBundle\Document\Hive $hive
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
    
        /**
     * Add ownerInstitutions
     *
     * @param Celsius3\CoreBundle\Document\Institution $ownerInstitutions
     */
    public function addOwnerInstitution(\Celsius3\CoreBundle\Document\Institution $ownerInstitutions)
    {
        $this->ownerInstitutions[] = $ownerInstitutions;
    }

    /**
     * Remove ownerInstitutions
     *
     * @param Celsius3\CoreBundle\Document\Institution $ownerInstitutions
     */
    public function removeOwnerInstitution(\Celsius3\CoreBundle\Document\Institution $ownerInstitutions)
    {
        $this->ownerInstitutions->removeElement($ownerInstitutions);
    }

    /**
     * Get ownerInstitutions
     *
     * @return Doctrine\Common\Collections\ArrayCollection $ownerInstitutions
     */
    public function getOwnerInstitutions()
    {
        return $this->ownerInstitutions;
    }
}
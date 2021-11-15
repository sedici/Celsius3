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

namespace Celsius3\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\LegacyInstanceRepository")
 * @ORM\Table(name="instance", indexes={
 *   @ORM\Index(name="idx_name", columns={"name"}),
 *   @ORM\Index(name="idx_website", columns={"website"}),
 *   @ORM\Index(name="idx_hive", columns={"hive_id"}),
 *   @ORM\Index(name="idx_url", columns={"url"}),
 *   @ORM\Index(name="idx_type", columns={"type"})
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "legacy"="LegacyInstance",
 *   "current"="Instance"
 * })
 *
 * @UniqueEntity("email")
 */
class LegacyInstance
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    protected $abbreviation;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     * @ORM\Column(type="string", length=255)
     */
    protected $website;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    protected $enabled = true;

    /**
     * @ORM\OneToMany(targetEntity="Institution", mappedBy="celsiusInstance")
     */
    protected $ownerInstitutions;

    /**
     * @ORM\ManyToOne(targetEntity="Hive", inversedBy="instances")
     * @ORM\JoinColumn(name="hive_id", referencedColumnName="id")
     */
    protected $hive;

    public function __construct()
    {
        $this->ownerInstitutions = new ArrayCollection();
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
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set abbreviation.
     *
     * @param string $abbreviation
     *
     * @return self
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set website.
     *
     * @param string $website
     *
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website.
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     *
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set hive.
     *
     * @param Hive $hive
     *
     * @return self
     */
    public function setHive(Hive $hive)
    {
        $this->hive = $hive;

        return $this;
    }

    /**
     * Get hive.
     *
     * @return Hive $hive
     */
    public function getHive()
    {
        return $this->hive;
    }

    /**
     * Add ownerInstitutions.
     *
     * @param Institution $ownerInstitutions
     */
    public function addOwnerInstitution(Institution $ownerInstitutions)
    {
        $this->ownerInstitutions[] = $ownerInstitutions;
    }

    /**
     * Remove ownerInstitutions.
     *
     * @param Institution $ownerInstitutions
     */
    public function removeOwnerInstitution(Institution $ownerInstitutions)
    {
        $this->ownerInstitutions->removeElement($ownerInstitutions);
    }

    /**
     * Get ownerInstitutions.
     *
     * @return ArrayCollection $ownerInstitutions
     */
    public function getOwnerInstitutions()
    {
        return $this->ownerInstitutions;
    }
}

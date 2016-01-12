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

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\CatalogRepository")
 * @ORM\Table(name="catalog", indexes={
 *   @ORM\Index(name="idx_name", columns={"name"}),
 *   @ORM\Index(name="idx_url", columns={"url"}),
 *   @ORM\Index(name="idx_institution", columns={"institution_id"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"})
 * })
 */
class Catalog
{

    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @Assert\Url
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="Institution", inversedBy="catalogs")
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id")
     */
    private $institution;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="catalogs")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;

    /**
     * @ORM\OneToMany(targetEntity="CatalogPosition", mappedBy="catalog", cascade={"persist"})
     */
    private $positions;

    public function __toString()
    {
        return $this->name;
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
     * Set url
     *
     * @param  string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set comments
     *
     * @param  string $comments
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
     * Set institution
     *
     * @param  Celsius3\CoreBundle\Entity\Institution $institution
     * @return self
     */
    public function setInstitution(\Celsius3\CoreBundle\Entity\Institution $institution = null)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return Celsius3\CoreBundle\Entity\Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    public function __construct()
    {
        $this->positions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add position
     *
     * @param Celsius3\CoreBundle\Entity\CatalogPosition $position
     */
    public function addPosition(\Celsius3\CoreBundle\Entity\CatalogPosition $position)
    {
        $this->positions[] = $position;
    }

    /**
     * Remove position
     *
     * @param Celsius3\CoreBundle\Entity\CatalogPosition $position
     */
    public function removePosition(\Celsius3\CoreBundle\Entity\CatalogPosition $position)
    {
        $this->positions->removeElement($position);
    }

    /**
     * Get positions
     *
     * @return Doctrine\Common\Collections\Collection $positions
     */
    public function getPositions()
    {
        return $this->positions;
    }

    public function getPosition(Instance $instance)
    {
        $result = $this->getPositions()
                        ->filter(
                                function (CatalogPosition $entry) use ($instance) {
                            return $entry->getInstance()->getId() == $instance->getId();
                        })->first();

        return false !== $result ? $result : null;
    }

}

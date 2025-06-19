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

namespace Celsius3\Entity;

use Celsius3\Repository\CatalogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ORM\Table(name: 'catalog'),
    ORM\Entity(repositoryClass: CatalogRepository::class),

    ORM\Index(name: 'idx_name', columns: ['name']),
    ORM\Index(name: 'idx_url', columns: ['url']),
    ORM\Index(name: 'idx_institution', columns: ['institution_id']),
    ORM\Index(name: 'idx_instance', columns: ['instance_id'])
]
class Catalog implements \Stringable
{

    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;


    #[Assert\NotBlank]
    #[Assert\Url]
    #[ORM\Column(type: 'string', length: 255)]
    private string $url;


    #[ORM\Column(type: 'text', nullable: true)]
    private string $comments;


    #[ORM\ManyToOne(targetEntity: Institution::class, inversedBy: 'catalogs')]
    #[ORM\JoinColumn(name: 'institution_id', referencedColumnName: 'id')]
    private $institution;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'catalogs')]
    #[ORM\JoinColumn(name: 'instance_id', referencedColumnName: 'id', nullable: false)]
    private Instance $instance;


    #[ORM\OneToMany(targetEntity: CatalogPosition::class, mappedBy: 'catalog', cascade: ['persist'])]
    private Collection $positions;


    public function __toString(): string
    { return $this->name; }

    public function getId(): ?int
    { return $this->id; }

    public function getFullName(): ?string
    { return $this->getName(); }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    { return $this->name; }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl(): ?string
    { return $this->url; }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function getComments(): ?string
    { return $this->comments; }

    public function setInstitution(?Institution $institution = null): self
    {
        $this->institution = $institution;
        return $this;
    }

    public function getInstitution(): ?Institution
    { return $this->institution; }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    public function getInstance(): ?Instance
    { return $this->instance; }

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function addPosition(CatalogPosition $position): void
    {
        $this->positions[] = $position;
    }

    public function removePosition(CatalogPosition $position): void
    {
        $this->positions->removeElement($position);
    }

    public function getPositions(): ArrayCollection
    { return $this->positions; }

    public function getPosition(Instance $instance): ?CatalogPosition
    {
        $result = $this->getPositions()
            ->filter(
                function (CatalogPosition $entry) use ($instance) {
                    return $entry->getInstance()->getId() == $instance->getId();
                }
            )->first();

        return false !== $result ? $result : null;
    }
}

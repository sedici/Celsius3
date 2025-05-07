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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
// use Celsius3\Entity\Mixin\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: \Celsius3\Repository\CatalogRepository::class)]
#[ORM\Table(name: 'catalog', indexes: [
    new ORM\Index(name: 'idx_name', columns: ['name']),
    new ORM\Index(name: 'idx_url', columns: ['url']),
    new ORM\Index(name: 'idx_institution', columns: ['institution_id']),
    new ORM\Index(name: 'idx_instance', columns: ['instance_id'])
])]
class Catalog
{
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups([
        'administration_order_show'
    ])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        'administration_order_show'
    ])]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Url]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        'administration_order_show'
    ])]
    private ?string $url = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comments = null;

    #[ORM\ManyToOne(targetEntity: Institution::class, inversedBy: 'catalogs')]
    #[ORM\JoinColumn(name: 'institution_id', referencedColumnName: 'id')]
    #[Groups([
        'administration_order_show'
    ])]
    private $institution;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'catalogs')]
    #[ORM\JoinColumn(name: 'instance_id', referencedColumnName: 'id', nullable: false)]
    private $instance;

    #[ORM\OneToMany(targetEntity: CatalogPosition::class, mappedBy: 'catalog', cascade: ['persist'], fetch: "EXTRA_LAZY")]
    private $positions;

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

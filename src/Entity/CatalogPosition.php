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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ORM\Table(name: 'catalog_position'),
    ORM\Entity(repositoryClass: \Celsius3\Repository\BaseRepository::class),
    
    ORM\Index(name: 'idx_position', columns: ['position']),
    ORM\Index(name: 'idx_catalog', columns: ['catalog_id']),
    ORM\Index(name: 'idx_instance', columns: ['instance_id'])
]
class CatalogPosition
{
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;


    #[Assert\Type(type: 'integer')]
    #[ORM\Column(type: 'integer')]
    private int $position;


    #[Assert\NotBlank]
    #[Assert\Type(type: 'boolean')]
    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $enabled;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Catalog::class, inversedBy: 'positions')]
    #[ORM\JoinColumn(name: 'catalog_id', referencedColumnName: 'id', nullable: false)]
    private Catalog $catalog;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(name: 'instance_id', referencedColumnName: 'id', nullable: false)]
    private Instance $instance;


    public function getId(): int|null
    { return $this->id; }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): int|null
    { return $this->position; }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEnabled(): bool|null
    { return $this->enabled; }

    public function setCatalog(Catalog $catalog): static
    {
        $this->catalog = $catalog;

        return $this;
    }

    public function getCatalog(): Catalog
    { return $this->catalog; }

    public function setInstance(Instance $instance): static
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): Instance
    { return $this->instance; }
}

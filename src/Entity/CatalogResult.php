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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Entity\TimestampableEntity;

#[ORM\Entity(repositoryClass: \Celsius3\Repository\CatalogResultRepository::class)]
#[ORM\Table(name: 'catalog_result', indexes: [
    new ORM\Index(name: 'idx_title', columns: ['title']),
    new ORM\Index(name: 'idx_catalog', columns: ['catalog_id']),
    new ORM\Index(name: 'idx_title_catalog', columns: ['catalog_id', 'title'])
])]
class CatalogResult
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
    #[Assert\NotNull]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        'administration_order_show'
    ])]
    private ?string $title = null;


    #[Assert\Type(type: 'integer')]
    #[ORM\Column(type: 'integer')]
    #[Groups([
        'administration_order_show'
    ])]
    private ?int $searches = 0;


    #[Assert\Type(type: 'integer')]
    #[ORM\Column(type: 'integer')]
    #[Groups([
        'administration_order_show'
    ])]
    private ?int $matches = 0;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Catalog::class, inversedBy: 'positions')]
    #[ORM\JoinColumn(name: 'catalog_id', referencedColumnName: 'id', nullable: false)]
    #[Groups([
        'administration_order_show'
    ])]
    private $catalog;


    /**
     * Get id.
     */
    public function getId(): int|null
    {
        return $this->id;
    }


    /**
     * Set title.
     */
    public function setTitle($title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     */
    public function getTitle(): string|null
    {
        return $this->title;
    }

    /**
     * Set searches.
     */
    public function setSearches($searches): static
    {
        $this->searches = $searches;

        return $this;
    }

    /**
     * Get searches.
     */
    public function getSearches(): int|null
    {
        return $this->searches;
    }

    /**
     * Set matches.
     */
    public function setMatches($matches): static
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * Get matches.
     */
    public function getMatches(): int|null
    {
        return $this->matches;
    }

    /**
     * Set catalog.
     */
    public function setCatalog(Catalog $catalog): static
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog.
     */
    public function getCatalog(): Catalog
    {
        return $this->catalog;
    }
}

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

use Celsius3\Entity\Mixin\SoftDeleteableEntity;
use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: BaseRepository::class)]
#[ORM\Table(name: "material_type", indexes: [
    new ORM\Index(name: "idx_type", columns: ["type"]),
    new ORM\Index(name: "idx_title", columns: ["title"])
])]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "journal" => JournalType::class,
    "book" => BookType::class,
    "congress" => CongressType::class,
    "thesis" => ThesisType::class,
    "patent" => PatentType::class,
    "newspaper" => NewspaperType::class
])]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
abstract class MaterialType
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list",
        "email_template"
    ])]
    protected ?string $title = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?string $authors = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: "integer")]
    #[ORM\Column(type: "integer")]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?int $year = null;

    #[ORM\Column(name: "start_page", type: "integer", nullable: true)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?int $startPage = null;

    #[ORM\Column(name: "end_page", type: "integer", nullable: true)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?int $endPage = null;

    #[ORM\OneToOne(targetEntity: Order::class, mappedBy: "materialData")]
    protected $order;

    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    abstract public function getMaterialType(): string;

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function __clone()
    {
        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthors(): ?string
    {
        return $this->authors;
    }

    public function setAuthors(string $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getStartPage(): ?int
    {
        return $this->startPage;
    }

    public function setStartPage(int $startPage): self
    {
        $this->startPage = $startPage;

        return $this;
    }

    public function getEndPage(): ?int
    {
        return $this->endPage;
    }

    public function setEndPage(int $endPage): self
    {
        $this->endPage = $endPage;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }
}

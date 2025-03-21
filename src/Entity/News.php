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

use Celsius3\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ORM\Table(name: "news", indexes: [
    new ORM\Index(name: "idx_title", columns: ["title"]),
    new ORM\Index(name: "idx_date", columns: ["date"]),
    new ORM\Index(name: "idx_instance", columns: ["instance_id"])
])]
class News
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $title = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "text")]
    private ?string $text = null;


    #[Assert\NotBlank]
    #[Assert\Date]
    #[ORM\Column(type: \DateTime::class)]
    private ?\DateTime $date = null;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: "news")]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id", nullable: false)]
    private ?Instance $instance = null;


    #[Assert\NotNull]
    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    protected bool $active = true;


    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }
}

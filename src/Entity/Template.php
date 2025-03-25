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

use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: BaseRepository::class)]
#[ORM\Table(name: "template", indexes: [
    new ORM\Index(name: "idx_code", columns: ["code"]),
    new ORM\Index(name: "idx_title", columns: ["title"]),
    new ORM\Index(name: "idx_instance", columns: ["instance_id"]),
    new ORM\Index(name: "idx_type", columns: ["type"])
])]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "mail" => EmailTemplate::class,
    "notification" => NotificationTemplate::class
])]
abstract class Template
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups([
        "api",
        "administration"
    ])]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "api",
        "administration"
    ])]
    private ?string $code = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "text")]
    #[Groups([
        "api",
        "administration"
    ])]
    private ?string $text = null;


    public function getId(): ?int
    { return $this->id; }


    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }


    public function getCode(): ?string
    { return $this->code; }


    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }


    public function getText(): ?string
    { return $this->text; }
}

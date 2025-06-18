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

declare(strict_types=1);

namespace Celsius3\Entity;

// use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ORM\Table(name: "custom_value"),
    ORM\Entity(repositoryClass: BaseRepository::class),

    ORM\InheritanceType("SINGLE_TABLE"),
    ORM\DiscriminatorColumn(name: "type", type: "string"),
    ORM\DiscriminatorMap([
        "user_value" => CustomUserValue::class,
        "contact_value" => CustomContactValue::class
    ]),

    ORM\Index(name: "idx_field", columns: ["field_id"]),
    ORM\Index(name: "idx_user", columns: ["user_id"]),
    ORM\Index(name: "idx_contact", columns: ["contact_id"])
]
abstract class CustomValue
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;


    #[ORM\Column(type: "text", nullable: true)]
    private ?string $value = null;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: CustomField::class, inversedBy: "values")]
    #[ORM\JoinColumn(name: "field_id", referencedColumnName: "id", nullable: false)]
    private CustomField $field;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getField(): CustomField
    {
        return $this->field;
    }

    public function setField(CustomField $field): self
    {
        $this->field = $field;

        return $this;
    }
}

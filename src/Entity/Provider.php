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
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;


#[Entity(repositoryClass: BaseRepository::class)]
#[Table(name: "provider", indexes: [
    new Index(name: "idx_name", columns: ["name"]),
    new Index(name: "idx_city", columns: ["city_id"]),
    new Index(name: "idx_country", columns: ["country_id"]),
    new Index(name: "idx_instance", columns: ["instance_id"]),
    new Index(name: "idx_celsius_instance", columns: ["celsius_instance_id"]),
    new Index(name: "idx_parent", columns: ["parent_id"]),
    new Index(name: "idx_hive", columns: ["hive_id"]),
    new Index(name: "idx_type", columns: ["type"])
])]
#[InheritanceType("SINGLE_TABLE")]
#[DiscriminatorColumn(name: "type", type: "string")]
#[DiscriminatorMap([
    "author" => Author::class,
    "institution" => Institution::class,
    "web" => Web::class,
])]
abstract class Provider
{
    use TimestampableEntity;

    #[Column(type: "integer")]
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "institution_show"
    ])]
    private int $id;


    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show"
    ])]
    abstract public function getProviderType();


    /**
     * Get id.
     */
    public function getId(): int
    { return $this->id; }


    public function __toString(): string
    { return $this->getProviderName(); }


    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show"
    ])]
    public function getProviderName(): string
    { return ''; }
}

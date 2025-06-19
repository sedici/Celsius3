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
// use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\BaseRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[
    ORM\Table(name: 'counter'),
    ORM\Entity(repositoryClass: BaseRepository::class),
    ORM\Index(name: 'idx_name', columns: ['name']),

    ORM\HasLifecycleCallbacks
]
class Counter
{
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;


    #[ORM\Column(type: 'integer')]
    protected int $value;


    public function getId(): int
    { return $this->id; }

    public function setName(string $name): void
    { $this->name = $name; }


    public function getName(): string
    { return $this->name; }

    public function setValue(int $value): void
    { $this->value = $value; }


    public function getValue(): int
    { return $this->value; }
}

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

use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BaseRepository::class)]
class CustomUserValue extends CustomValue
{

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: BaseUser::class, inversedBy: 'customValues')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?BaseUser $user;


    public function getId(): int
    { return $this->id; }

    public function getUser(): ?BaseUser
    { return $this->user; }

    public function setUser(BaseUser $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getValue(): string
    { return parent::getValue(); }

    public function getField(): CustomField
    { return parent::getField(); }
}

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

use Celsius3\Repository\JournalTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JournalTypeRepository::class)]
class JournalType extends MaterialType
{
    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["administration_list", "administration_order_show", "administration_user_show", "user_list"])]
    protected ?string $volume;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["administration_list", "administration_order_show", "administration_user_show", "user_list"])]
    protected ?string $number;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["administration_list", "administration_order_show", "administration_user_show", "user_list"])]
    protected ?string $other;


    #[ORM\ManyToOne(targetEntity: Journal::class, inversedBy: "materials", fetch: "EAGER")]
    #[ORM\JoinColumn(name: "journal_id", referencedColumnName: "id")]
    #[Groups(["administration_list", "administration_order_show", "administration_user_show", "user_list"])]
    protected ?Journal $journal;


    public function getMaterialType(): string
    {
        return 'journal';
    }

    public function setVolume(string $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setJournal(?Journal $journal = null): self
    {
        $this->journal = $journal;

        return $this;
    }

    public function getJournal(): ?Journal
    {
        return $this->journal;
    }

    public function setOther(string $other): self
    {
        $this->other = $other;

        return $this;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }
}

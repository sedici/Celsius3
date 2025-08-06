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

use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Repository\ThesisTypeRepository;


#[ORM\Entity(repositoryClass: ThesisTypeRepository::class)]
class ThesisType extends MaterialType
{

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    // #[Groups([
    //     "administration_list",
    //     "administration_order_show",
    //     "administration_user_show",
    //     "user_list"
    // ])]
    protected ?string $director = null;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    // #[Groups([
    //     "administration_list",
    //     "administration_order_show",
    //     "administration_user_show",
    //     "user_list"
    // ])]
    protected ?string $degree = null;


    public function getMaterialType(): string
    { return 'thesis'; }


    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }


    public function getDirector(): ?string
    { return $this->director; }


    public function setDegree(string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }


    public function getDegree(): ?string
    { return $this->degree; }
}

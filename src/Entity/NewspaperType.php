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
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BaseRepository::class)]
class NewspaperType extends MaterialType
{

    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?string $article = null;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?string $month = null;


    #[ORM\Column(type: "integer", length: 2, nullable: true)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?int $day = null;


    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected ?string $place = null;


    public function getMaterialType(): string
    {
        return 'newspaper';
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;
        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(?int $day): self
    {
        $this->day = $day;
        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;
        return $this;
    }
}

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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseRepository")
 */
class NewspaperType extends MaterialType
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $article;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $month;

    /**
     * @ORM\Column(type="integer", length=2, nullable=true)
     */
    protected $day;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $place;

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

    public function setDay($day): self
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

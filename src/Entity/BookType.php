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
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BaseRepository::class)]
class BookType extends MaterialType
{

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $editor = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $chapter = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $ISBN = null;


    #[Assert\NotNull()]
    #[Assert\Type(type: 'boolean')]
    #[ORM\Column(type: 'boolean')]
    protected ?bool $withIndex = false;


    public function getMaterialType(): string
    { return 'book'; }

    public function setEditor(string $editor): void
    { $this->editor = $editor; }

    public function getEditor(): string
    { return $this->editor; }

    public function setChapter(?string $chapter): void
    { $this->chapter = $chapter; }

    public function getChapter(): ?string
    { return $this->chapter; }

    public function setISBN(?string $ISBN): void
    { $this->ISBN = $ISBN; }

    public function getISBN(): ?string
    { return $this->ISBN; }

    public function setWithIndex(?bool $withIndex): void
    { $this->withIndex = $withIndex; }

    public function getWithIndex(): ?bool
    { return $this->withIndex; }
}

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

namespace Celsius3\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BookType extends MaterialType
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $editor;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $chapter;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $ISBN;
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    protected $withIndex = false;

    public function getMaterialType() {
        return 'book';
    }

    /**
     * Set editor
     *
     * @param string $editor
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;
    }

    /**
     * Get editor
     *
     * @return string $editor
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set chapter
     *
     * @param string $chapter
     */
    public function setChapter($chapter)
    {
        $this->chapter = $chapter;
    }

    /**
     * Get chapter
     *
     * @return string $chapter
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * Set ISBN
     *
     * @param string $ISBN
     */
    public function setISBN($ISBN)
    {
        $this->ISBN = $ISBN;
    }

    /**
     * Get ISBN
     *
     * @return string $ISBN
     */
    public function getISBN()
    {
        return $this->ISBN;
    }

    /**
     * Set withIndex
     *
     * @param boolean $withIndex
     */
    public function setWithIndex($withIndex)
    {
        $this->withIndex = $withIndex;
    }

    /**
     * Get withIndex
     *
     * @return boolean $withIndex
     */
    public function getWithIndex()
    {
        return $this->withIndex;
    }
}

<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\EmbeddedDocument
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({"journal"="JournalType", "book"="BookType", "congress"="CongressType", "thesis"="ThesisType", "patent"="PatentType"})
 */
abstract class MaterialType
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    protected $id;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    protected $title;
    /**
     * @ODM\String
     */
    protected $authors;
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @ODM\Int
     */
    protected $year;
    /**
     * @Assert\Type(type="integer")
     * @ODM\Int
     */
    protected $startPage;
    /**
     * @Assert\Type(type="integer")
     * @ODM\Int
     */
    protected $endPage;

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set authors
     *
     * @param  string $authors
     * @return self
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Get authors
     *
     * @return string $authors
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set year
     *
     * @param  int  $year
     * @return self
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int $year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set startPage
     *
     * @param  int  $startPage
     * @return self
     */
    public function setStartPage($startPage)
    {
        $this->startPage = $startPage;

        return $this;
    }

    /**
     * Get startPage
     *
     * @return int $startPage
     */
    public function getStartPage()
    {
        return $this->startPage;
    }

    /**
     * Set endPage
     *
     * @param  int  $endPage
     * @return self
     */
    public function setEndPage($endPage)
    {
        $this->endPage = $endPage;

        return $this;
    }

    /**
     * Get endPage
     *
     * @return int $endPage
     */
    public function getEndPage()
    {
        return $this->endPage;
    }
}
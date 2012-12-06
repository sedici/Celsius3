<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="type")
 * @MongoDB\DiscriminatorMap({"journal"="JournalType", "book"="BookType", "congress"="CongressType", "thesis"="ThesisType", "patent"="PatentType"})
 */
abstract class MaterialType
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $title;

    /**
     * @MongoDB\String
     */
    protected $authors;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @MongoDB\Int
     */
    protected $year;

    /**
     * @Assert\Type(type="integer")
     * @MongoDB\Int
     */
    protected $startPage;

    /**
     * @Assert\Type(type="integer")
     * @MongoDB\Int
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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @param string $authors
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;
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
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
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
     * @param int $startPage
     */
    public function setStartPage($startPage)
    {
        $this->startPage = $startPage;
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
     * @param int $endPage
     */
    public function setEndPage($endPage)
    {
        $this->endPage = $endPage;
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

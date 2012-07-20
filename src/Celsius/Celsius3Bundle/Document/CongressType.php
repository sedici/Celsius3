<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class CongressType extends MaterialType
{

    /**
     * @MongoDB\String
     */
    protected $place;

    /**
     * @MongoDB\String
     */
    protected $communication;

    /**
     * @var $id
     */
    protected $id;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $authors
     */
    protected $authors;

    /**
     * @var int $year
     */
    protected $year;

    /**
     * @var int $startPage
     */
    protected $startPage;

    /**
     * @var int $endPage
     */
    protected $endPage;

    /**
     * Set place
     *
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * Get place
     *
     * @return string $place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set communication
     *
     * @param string $communication
     */
    public function setCommunication($communication)
    {
        $this->communication = $communication;
    }

    /**
     * Get communication
     *
     * @return string $communication
     */
    public function getCommunication()
    {
        return $this->communication;
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

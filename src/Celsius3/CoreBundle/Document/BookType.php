<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class BookType extends MaterialType
{

    /**
     * @MongoDB\String
     */
    protected $editor;

    /**
     * @MongoDB\String
     */
    protected $chapter;

    /**
     * @MongoDB\String
     */
    protected $ISBN;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    protected $withIndex;

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

<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class BookType extends MaterialType
{
    /**
     * @ODM\String
     */
    protected $editor;
    /**
     * @ODM\String
     */
    protected $chapter;
    /**
     * @ODM\String
     */
    protected $ISBN;
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="boolean")
     * @ODM\Boolean
     */
    protected $withIndex = false;

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
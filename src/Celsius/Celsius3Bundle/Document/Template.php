<?php
namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Template {
    
    /**
     * @MongoDB\Id
     */
    private $id;
    
    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $code;
    
    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $title;
    
    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $text;
    
    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $idiom;
    
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
     * Set code
     *
     * @param string $title
     * @return Notification
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string $code
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Set title
     *
     * @param string $title
     * @return Notification
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
     * Set text
     *
     * @param string $text
     * @return Notification
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Set idiom
     *
     * @param string $idiom
     * @return Notification
     */
    public function setIdiom($idiom)
    {
        $this->idiom = $idiom;
        return $this;
    }

    /**
     * Get idiom
     *
     * @return string $idiom
     */
    public function getIdiom()
    {
        return $this->idiom;
    }
    
}

?>
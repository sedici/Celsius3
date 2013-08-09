<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Catalog
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @MongoDB\String
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @Assert\Url
     * @Assert\NotNull
     * @MongoDB\String
     */
    private $url;

    /**
     * @MongoDB\String
     */
    private $comments;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Institution", inversedBy="catalogs")
     */
    private $institution;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="catalogs")
     */
    private $instance;

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get comments
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set institution
     *
     * @param Celsius3\CoreBundle\Document\Institution $institution
     * @return self
     */
    public function setInstitution(\Celsius3\CoreBundle\Document\Institution $institution)
    {
        $this->institution = $institution;
        return $this;
    }

    /**
     * Get institution
     *
     * @return Celsius3\CoreBundle\Document\Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set instance
     *
     * @param Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }
}

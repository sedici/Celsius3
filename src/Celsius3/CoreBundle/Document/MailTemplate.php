<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\MailTemplateRepository")
 */
class MailTemplate extends Template
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $enabled = true;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $title;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Instance")
     */
    private $instance;

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set title
     *
     * @param string $title
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
     * Set instance
     *
     * @param Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(
            \Celsius3\CoreBundle\Document\Instance $instance)
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

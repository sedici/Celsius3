<?php

namespace Celsius3\CoreBundle\Document\Mixin;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait CancellableTrait
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isCancelled = false;

    /**
     * Set isCancelled
     *
     * @param boolean $isCancelled
     * @return self
     */
    public function setIsCancelled($isCancelled)
    {
        $this->isCancelled = $isCancelled;
        return $this;
    }

    /**
     * Get isCancelled
     *
     * @return boolean $isCancelled
     */
    public function getIsCancelled()
    {
        return $this->isCancelled;
    }
}
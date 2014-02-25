<?php

namespace Celsius3\CoreBundle\Document\Mixin;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait ReclaimableTrait
{

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isReclaimed = false;

    /**
     * Set isReclaimed
     *
     * @param boolean $isReclaimed
     * @return self
     */
    public function setIsReclaimed($isReclaimed)
    {
        $this->isReclaimed = $isReclaimed;
        return $this;
    }

    /**
     * Get isReclaimed
     *
     * @return boolean $isReclaimed
     */
    public function getIsReclaimed()
    {
        return $this->isReclaimed;
    }

}

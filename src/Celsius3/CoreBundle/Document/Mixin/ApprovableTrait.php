<?php

namespace Celsius3\CoreBundle\Document\Mixin;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait ApprovableTrait
{

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isApproved = false;

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     * @return self
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean $isApproved
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

}

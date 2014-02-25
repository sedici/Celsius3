<?php

namespace Celsius3\CoreBundle\Document\Mixin;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait AnnullableTrait
{

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isAnnulled = false;

    /**
     * Set isAnnulled
     *
     * @param boolean $isAnnulled
     * @return self
     */
    public function setIsAnnulled($isAnnulled)
    {
        $this->isAnnulled = $isAnnulled;
        return $this;
    }

    /**
     * Get isAnnulled
     *
     * @return boolean $isAnnulled
     */
    public function getIsAnnulled()
    {
        return $this->isAnnulled;
    }

}

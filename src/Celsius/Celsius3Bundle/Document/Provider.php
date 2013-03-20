<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="type")
 * @MongoDB\DiscriminatorMap({
 *   "author"="Author",
 *   "institution"="Institution",
 *   "web"="Web"
 * })
 */
class Provider
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function __toString()
    {
        return $this->getProviderName();
    }

    public function getProviderName()
    {
        return 'hola';
    }
}
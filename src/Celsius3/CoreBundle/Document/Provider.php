<?php

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 * @ODM\InheritanceType("COLLECTION_PER_CLASS")
 */
class Provider
{
    use TimestampableDocument;
    /**
     * @ODM\Id
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
        return '';
    }
}
<?php

namespace Celsius3\CoreBundle\Document\Analytics;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(db="celsius_analytics")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({
 *   "order"="OrderAnalytics",
 *   "user"="UserAnalytics"
 * })
 * @ODM\Indexes({
 *   @ODM\Index(keys={"instance.id"="asc"}),
 * })
 */
abstract class Analytics
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Instance")
     */
    private $instance;

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

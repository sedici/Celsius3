<?php

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class CongressType extends MaterialType
{
    /**
     * @ODM\String
     */
    protected $place;
    /**
     * @ODM\String
     */
    protected $communication;

    /**
     * Set place
     *
     * @param  string $place
     * @return self
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string $place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set communication
     *
     * @param  string $communication
     * @return self
     */
    public function setCommunication($communication)
    {
        $this->communication = $communication;

        return $this;
    }

    /**
     * Get communication
     *
     * @return string $communication
     */
    public function getCommunication()
    {
        return $this->communication;
    }
}
<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class CongressType extends MaterialType
{

    /**
     * @MongoDB\String
     */
    protected $place;

    /**
     * @MongoDB\String
     */
    protected $communication;

    /**
     * Set place
     *
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
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
     * @param string $communication
     */
    public function setCommunication($communication)
    {
        $this->communication = $communication;
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

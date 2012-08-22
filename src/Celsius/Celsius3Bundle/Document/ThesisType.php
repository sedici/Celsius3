<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class ThesisType extends MaterialType
{

    /**
     * @MongoDB\String
     */
    protected $director;

    /**
     * @MongoDB\String
     */
    protected $degree;

    /**
     * Set director
     *
     * @param string $director
     */
    public function setDirector($director)
    {
        $this->director = $director;
    }

    /**
     * Get director
     *
     * @return string $director
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set degree
     *
     * @param string $degree
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
    }

    /**
     * Get degree
     *
     * @return string $degree
     */
    public function getDegree()
    {
        return $this->degree;
    }

}

<?php

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class ThesisType extends MaterialType
{
    /**
     * @ODM\String
     */
    protected $director;
    /**
     * @ODM\String
     */
    protected $degree;

    /**
     * Set director
     *
     * @param  string $director
     * @return self
     */
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
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
     * @param  string $degree
     * @return self
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
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
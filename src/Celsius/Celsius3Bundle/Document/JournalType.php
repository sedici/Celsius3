<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class JournalType extends MaterialType
{

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $volume;

    /**
     * @MongoDB\String
     */
    protected $number;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Journal") 
     */
    protected $journal;

    /**
     * Set volume
     *
     * @param string $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * Get volume
     *
     * @return string $volume
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set number
     *
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set journal
     *
     * @param Celsius\Celsius3Bundle\Document\Journal $journal
     * @return JournalType
     */
    public function setJournal(\Celsius\Celsius3Bundle\Document\Journal $journal)
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     * Get journal
     *
     * @return Celsius\Celsius3Bundle\Document\Journal $journal
     */
    public function getJournal()
    {
        return $this->journal;
    }
}

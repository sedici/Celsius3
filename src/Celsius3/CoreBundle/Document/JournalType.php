<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class JournalType extends MaterialType
{
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    protected $volume;
    /**
     * @ODM\String
     */
    protected $number;
    /**
     * @ODM\String
     */
    protected $other;
    /**
     * @ODM\ReferenceOne(targetDocument="Journal")
     */
    protected $journal;

    /**
     * Set volume
     *
     * @param  string $volume
     * @return self
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
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
     * @param  string $number
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
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
     * @param  Celsius3\CoreBundle\Document\Journal $journal
     * @return self
     */
    public function setJournal(\Celsius3\CoreBundle\Document\Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return Celsius3\CoreBundle\Document\Journal $journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set other
     *
     * @param  string $other
     * @return self
     */
    public function setOther($other)
    {
        $this->other = $other;

        return $this;
    }

    /**
     * Get other
     *
     * @return string $other
     */
    public function getOther()
    {
        return $this->other;
    }
}
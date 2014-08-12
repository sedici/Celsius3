<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

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
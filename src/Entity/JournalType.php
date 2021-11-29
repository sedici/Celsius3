<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\JournalTypeRepository")
 */
class JournalType extends MaterialType
{
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    protected $volume;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $number;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $other;
    /**
     * @ORM\ManyToOne(targetEntity="Journal", inversedBy="materials", fetch="EAGER")
     * @ORM\JoinColumn(name="journal_id", referencedColumnName="id")
     */
    protected $journal;

    public function getMaterialType()
    {
        return 'journal';
    }

    /**
     * Set volume.
     *
     * @param string $volume
     *
     * @return self
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume.
     *
     * @return string $volume
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set number.
     *
     * @param string $number
     *
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set journal.
     *
     * @param Journal $journal
     *
     * @return self
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal.
     *
     * @return Journal $journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set other.
     *
     * @param string $other
     *
     * @return self
     */
    public function setOther($other)
    {
        $this->other = $other;

        return $this;
    }

    /**
     * Get other.
     *
     * @return string $other
     */
    public function getOther()
    {
        return $this->other;
    }
}

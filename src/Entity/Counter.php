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
use Celsius3\Entity\TimestampableEntity;
use Celsius3\Repository\BaseRepository;

#[ORM\Entity(repositoryClass: BaseRepository::class)]
#[ORM\Table(
    name: 'counter',
    indexes: [
        new ORM\Index(name: 'idx_name', columns: ['name'])
    ]
)]
class Counter
{
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    protected ?string $name = null;


    #[ORM\Column(type: 'integer')]
    protected ?int $value = null;


    /**
     * Get id.
     */
    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value.
     *
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value.
     *
     * @return int $value
     */
    public function getValue()
    {
        return $this->value;
    }
}

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

namespace Celsius3\CoreBundle\Entity\Mixin;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait AnnullableTrait
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $annulled = false;

    /**
     * Set annulled.
     *
     * @param bool $annulled
     *
     * @return self
     */
    public function setAnnulled($annulled)
    {
        $this->annulled = $annulled;

        return $this;
    }

    /**
     * Get annulled.
     *
     * @return bool $annulled
     */
    public function isAnnulled()
    {
        return $this->annulled;
    }
}

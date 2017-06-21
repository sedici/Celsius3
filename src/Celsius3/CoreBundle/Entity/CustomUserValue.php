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

namespace Celsius3\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseRepository")
 * @ORM\Table(name="custom_user_value", indexes={
 *   @ORM\Index(name="idx_field", columns={"field_id"}),
 *   @ORM\Index(name="idx_user", columns={"user_id"})
 * })
 */
class CustomUserValue
{
    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="CustomUserField", inversedBy="values")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id", nullable=false)
     */
    private $field;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="BaseUser", inversedBy="customValues")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set field.
     *
     * @param CustomUserField $field
     *
     * @return self
     */
    public function setField(CustomUserField $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field.
     *
     * @return CustomUserField $field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set user.
     *
     * @param BaseUser $user
     *
     * @return self
     */
    public function setUser(BaseUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }
}

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
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 */
class Configuration
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $key;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $name;
    /**
     * @ODM\String
     */
    private $value;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $type;
    /**
     * @ODM\ReferenceOne(targetDocument="Instance")
     */
    private $instance;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set key
     *
     * @param  string $key
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param  string $value
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set type
     *
     * @param  string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }
}
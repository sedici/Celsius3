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

namespace Celsius3\MigrationBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Association
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $original_id;

    /**
     * @MongoDB\String
     */
    private $name;

    /**
     * @MongoDB\String
     */
    private $table;

    /**
     * @MongoDB\ReferenceOne
     */
    private $document;

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
     * Set original_id
     *
     * @param  string       $originalId
     * @return \Association
     */
    public function setOriginalId($originalId)
    {
        $this->original_id = $originalId;

        return $this;
    }

    /**
     * Get original_id
     *
     * @return string $originalId
     */
    public function getOriginalId()
    {
        return $this->original_id;
    }

    /**
     * Set name
     *
     * @param  string       $name
     * @return \Association
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
     * Set table
     *
     * @param  string       $table
     * @return \Association
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table
     *
     * @return string $table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set document
     *
     * @param $document
     * @return \Association
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return $document
     */
    public function getDocument()
    {
        return $this->document;
    }
}
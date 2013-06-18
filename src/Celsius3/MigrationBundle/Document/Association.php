<?php

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
     * @param string $originalId
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
     * @param string $name
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
     * @param string $table
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

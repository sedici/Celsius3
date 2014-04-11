<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class CatalogSearch
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $result;

    /**
     * @Assert\NotNull
     * @MongoDB\Date
     */
    private $date;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Catalog")
     */
    private $catalog;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Request", inversedBy="searches")
     */
    private $request;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $operator;

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
     * Set result
     *
     * @param string $result
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Get result
     *
     * @return string $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set catalog
     *
     * @param Celsius3\CoreBundle\Document\Catalog $catalog
     * @return self
     */
    public function setCatalog(\Celsius3\CoreBundle\Document\Catalog $catalog)
    {
        $this->catalog = $catalog;
        return $this;
    }

    /**
     * Get catalog
     *
     * @return Celsius3\CoreBundle\Document\Catalog $catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set operator
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Document\BaseUser $operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set request
     *
     * @param Celsius3\CoreBundle\Document\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Document\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Document\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

}

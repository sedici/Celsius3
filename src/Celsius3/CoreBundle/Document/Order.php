<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\OrderRepository")
 */
class Order
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\Type(type="integer")
     * @MongoDB\Int
     */
    private $code;

    /**
     * @MongoDB\EmbedOne(targetDocument="MaterialType")
     */
    private $materialData;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Request")
     */
    private $originalRequest;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Request", mappedBy="Order")
     */
    private $requests;

    public function __toString()
    {
        return strval($this->getCode());
    }

    public function __construct()
    {
        $this->requests = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set code
     *
     * @param int $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return int $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set materialData
     *
     * @param Celsius3\CoreBundle\Document\MaterialType $materialData
     * @return self
     */
    public function setMaterialData(\Celsius3\CoreBundle\Document\MaterialType $materialData = null)
    {
        $this->materialData = $materialData;
        return $this;
    }

    /**
     * Get materialData
     *
     * @return Celsius3\CoreBundle\Document\MaterialType $materialData
     */
    public function getMaterialData()
    {
        return $this->materialData;
    }

    /**
     * Set originalRequest
     *
     * @param Celsius3\CoreBundle\Document\Request $originalRequest
     * @return self
     */
    public function setOriginalRequest(\Celsius3\CoreBundle\Document\Request $originalRequest)
    {
        $this->originalRequest = $originalRequest;
        return $this;
    }

    /**
     * Get originalRequest
     *
     * @return Celsius3\CoreBundle\Document\Request $originalRequest
     */
    public function getOriginalRequest()
    {
        return $this->originalRequest;
    }

    /**
     * Add request
     *
     * @param Celsius3\CoreBundle\Document\Request $request
     */
    public function addRequest(\Celsius3\CoreBundle\Document\Request $request)
    {
        $this->requests[] = $request;
    }

    /**
     * Remove request
     *
     * @param Celsius3\CoreBundle\Document\Request $request
     */
    public function removeRequest(\Celsius3\CoreBundle\Document\Request $request)
    {
        $this->requests->removeElement($request);
    }

    /**
     * Get requests
     *
     * @return Doctrine\Common\Collections\Collection $requests
     */
    public function getRequests()
    {
        return $this->requests;
    }

}

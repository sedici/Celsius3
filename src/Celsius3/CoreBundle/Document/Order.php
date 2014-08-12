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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableDocument;

/**
 * @ODM\Document(repositoryClass="Celsius3\CoreBundle\Repository\OrderRepository")
 */
class Order
{

    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\Type(type="integer")
     * @ODM\Int
     */
    private $code;
    /**
     * @ODM\EmbedOne(targetDocument="MaterialType")
     */
    private $materialData;
    /**
     * @ODM\ReferenceOne(targetDocument="Request", cascade={"persist"})
     */
    private $originalRequest;
    /**
     * @ODM\ReferenceMany(targetDocument="Request", mappedBy="order")
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
     * @param  int  $code
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
     * @param  Celsius3\CoreBundle\Document\MaterialType $materialData
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
     * @param  Celsius3\CoreBundle\Document\Request $originalRequest
     * @return self
     */
    public function setOriginalRequest(\Celsius3\CoreBundle\Document\Request $originalRequest = null)
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

    /**
     * Retorna si existe o no un request para $instance
     */
    public function hasRequest(Instance $instance)
    {
        return ($this->getRequests()
                        ->filter(
                                function ($entry) use ($instance) {
                            return $entry->getInstance()->getId() == $instance->getId();
                        })->count() > 0);
    }

    /**
     * Retorna el Request para la instancia $instance para el Order actual.
     * Antes debería verificarse su existencia con hasRequest.
     */
    public function getRequest(Instance $instance)
    {
        $result = $this->getRequests()
                        ->filter(
                                function ($entry) use ($instance) {
                            return $entry->getInstance()->getId() == $instance->getId();
                        })->first();

        return false !== $result ? $result : null;
    }
}
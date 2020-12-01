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

use Celsius3\CoreBundle\Manager\StateManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\OrderRepository")
 * @ORM\Table(name="`order`", indexes={
 *   @ORM\Index(name="idx_code", columns={"`code`"}),
 *   @ORM\Index(name="idx_created_at", columns={"createdAt"}),
 *   @ORM\Index(name="idx_material_data", columns={"material_data_id"}),
 *   @ORM\Index(name="idx_original_request", columns={"original_request_id"})
 * })
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Order
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\Type(type="integer")
     * @ORM\Column(type="integer", name="`code`")
     */
    private $code;

    /**
     * @ORM\OneToOne(targetEntity="MaterialType", inversedBy="order", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="material_data_id", referencedColumnName="id", nullable=true)
     */
    private $materialData;

    /**
     * @Assert\NotNull
     * @ORM\OneToOne(targetEntity="Request", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="original_request_id", referencedColumnName="id")
     */
    private $originalRequest;

    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="order", fetch="EAGER")
     */
    private $requests;

    public function __toString()
    {
        return strval($this->getCode());
    }

    public function __construct()
    {
        $this->requests = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->materialData = null;
        $this->requests = new ArrayCollection();
        $this->originalRequest = null;
    }

    public function getPages()
    {
        $files = $this->getOriginalRequest()->getFiles();
        $pages = 0;
        foreach ($files as $file) {
            if ($file->getEnabled()) {
                $pages += $file->getPages();
            }
        }

        return $pages;
    }

    public function getReceivedAt()
    {
        $states = $this->getOriginalRequest()->getStates();

        $receivedState = null;
        foreach ($states as $state) {
            if ($state->getType() === StateManager::STATE__RECEIVED) {
                $receivedState = $state;
                break 1;
            }
        }

        $receivedDate = null;
        if (!is_null($receivedState)) {
            $receivedDate = $receivedState->getCreatedAt();
        }

        return $receivedDate;
    }

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
     * Set code.
     *
     * @param int $code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return int $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set materialData.
     *
     * @param MaterialType $materialData
     *
     * @return self
     */
    public function setMaterialData(MaterialType $materialData = null)
    {
        $this->materialData = $materialData;

        return $this;
    }

    /**
     * Get materialData.
     *
     * @return MaterialType $materialData
     */
    public function getMaterialData()
    {
        return $this->materialData;
    }

    /**
     * Set originalRequest.
     *
     * @param Request $originalRequest
     *
     * @return self
     */
    public function setOriginalRequest(Request $originalRequest = null)
    {
        $this->originalRequest = $originalRequest;

        return $this;
    }

    /**
     * Get originalRequest.
     *
     * @return Request $originalRequest
     */
    public function getOriginalRequest()
    {
        return $this->originalRequest;
    }

    /**
     * Add request.
     *
     * @param Request $request
     */
    public function addRequest(Request $request)
    {
        $this->requests[] = $request;
    }

    /**
     * Remove request.
     *
     * @param Request $request
     */
    public function removeRequest(Request $request)
    {
        $this->requests->removeElement($request);
    }

    /**
     * Get requests.
     *
     * @return Collection $requests
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * Retorna si existe o no un request para $instance.
     */
    public function hasRequest(Instance $instance)
    {
        return $this->getRequests()
                        ->filter(
                                function (Request $entry) use ($instance) {
                                    return $entry->getInstance()->getId() == $instance->getId();
                                })->count() > 0;
    }

    /**
     * Retorna el Request para la instancia $instance para el Order actual.
     * Antes debería verificarse su existencia con hasRequest.
     */
    public function getRequest(Instance $instance)
    {
        $result = $this->getRequests()
                        ->filter(
                                function (Request $entry) use ($instance) {
                                    return $entry->getInstance()->getId() == $instance->getId();
                                })->first();

        return false !== $result ? $result : null;
    }
}

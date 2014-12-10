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

namespace Celsius3\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\RequestRepository")
 * @ORM\Table(name="request")
 */
class Request
{

    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback = {"\Celsius3\CoreBundle\Manager\OrderManager", "getTypes"}, message = "Choose a valid type.")
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="BaseUser", inversedBy="orders")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */
    private $owner;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="BaseUser", inversedBy="createdOrders")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", nullable=false)
     */
    private $creator;
    /**
     * @ORM\ManyToOne(targetEntity="BaseUser")
     * @ORM\JoinColumn(name="librarian_id", referencedColumnName="id")
     */
    private $librarian;
    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="request")
     */
    private $files;
    /**
     * @ORM\OneToMany(targetEntity="Celsius3\CoreBundle\Entity\Event\Event", mappedBy="request")
     */
    private $events;
    /**
     * @ORM\OneToMany(targetEntity="State", mappedBy="request")
     */
    private $states;
    /**
     * @Assert\NotNull(groups={"Default", "newOrder"})
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="orders")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;
    /**
     * @ORM\ManyToOne(targetEntity="BaseUser", inversedBy="operatedOrders")
     * @ORM\JoinColumn(name="operator_id", referencedColumnName="id")
     */
    private $operator;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="requests")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private $order;
    /**
     * @ORM\ManyToOne(targetEntity="Request", inversedBy="requests")
     * @ORM\JoinColumn(name="previous_request_id", referencedColumnName="id")
     */
    private $previousRequest;
    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="previousRequest")
     */
    private $requests;

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set comments
     *
     * @param  string $comments
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set owner
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $owner
     * @return self
     */
    public function setOwner(\Celsius3\CoreBundle\Entity\BaseUser $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set creator
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $creator
     * @return self
     */
    public function setCreator(\Celsius3\CoreBundle\Entity\BaseUser $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $creator
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set librarian
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $librarian
     * @return self
     */
    public function setLibrarian(\Celsius3\CoreBundle\Entity\BaseUser $librarian)
    {
        $this->librarian = $librarian;

        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Add file
     *
     * @param Celsius3\CoreBundle\Entity\File $file
     */
    public function addFile(\Celsius3\CoreBundle\Entity\File $file)
    {
        $this->files[] = $file;
    }

    /**
     * Remove file
     *
     * @param Celsius3\CoreBundle\Entity\File $file
     */
    public function removeFile(\Celsius3\CoreBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add event
     *
     * @param Celsius3\CoreBundle\Entity\Event\Event $event
     */
    public function addEvent(\Celsius3\CoreBundle\Entity\Event\Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Remove event
     *
     * @param Celsius3\CoreBundle\Entity\Event\Event $event
     */
    public function removeEvent(\Celsius3\CoreBundle\Entity\Event\Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return Doctrine\Common\Collections\Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add state
     *
     * @param Celsius3\CoreBundle\Entity\State $state
     */
    public function addState(\Celsius3\CoreBundle\Entity\State $state)
    {
        $this->states[] = $state;
    }

    /**
     * Remove state
     *
     * @param Celsius3\CoreBundle\Entity\State $state
     */
    public function removeState(\Celsius3\CoreBundle\Entity\State $state)
    {
        $this->states->removeElement($state);
    }

    /**
     * Get states
     *
     * @return Doctrine\Common\Collections\Collection $states
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set operator
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Entity\BaseUser $operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set order
     *
     * @param  Celsius3\CoreBundle\Entity\Order $order
     * @return self
     */
    public function setOrder(\Celsius3\CoreBundle\Entity\Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius3\CoreBundle\Entity\Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Se buscan los archivos para un determinado evento
     */
    public function getFilesForEvent($event)
    {
        return $this->getFiles()
                        ->filter(
                                function ($entry) use ($event) {
                            return ($entry->getEvent()->getId() == $event->getId());
                        });
    }

    /**
     * Se buscan los archivos para descargar por el usuario
     */
    public function getFilesForDownload()
    {
        $instance = $this->getOrder()->getOriginalRequest()->getInstance();

        return $this->getFiles()
                        ->filter(
                                function ($entry) use ($instance) {
                            return ($entry->getEvent()->getInstance()->getId() == $instance->getId());
                        });
    }

    /**
     * Se buscan los archivos que aún no han sido descargados
     */
    public function getNotDownloadedFiles()
    {
        $instance = $this->getOrder()->getOriginalRequest()->getInstance();

        return $this->getFiles()
                        ->filter(
                                function ($entry) use ($instance) {
                            return ($entry->getEvent()->getInstance()->getId() == $instance->getId() && !$entry->getIsDownloaded());
                        });
    }

    /**
     * Retorna si el Request actual ha alcanzado un determinado estado o estados
     */
    public function hasState($names)
    {
        if (!is_array($names)) {
            $names = array($names);
        }

        return ($this->getStates()
                        ->filter(
                                function ($entry) use ($names) {
                            return in_array($entry->getType(), $names);
                        })->count() > 0);
    }

    /**
     * Retorna el estado con nombre $name para el Request actual.
     * Antes debería verificarse su existencia con hasState.
     */
    public function getState($name)
    {
        $result = $this->getStates()
                        ->filter(
                                function ($entry) use ($name) {
                            return $entry->getType() === $name;
                        })->first();

        return false !== $result ? $result : null;
    }

    /**
     * Retorna el estado actual para el presente Request
     */
    public function getCurrentState()
    {
        $result = $this->getStates()
                        ->filter(
                                function ($entry) {
                            return $entry->getIsCurrent();
                        })->first();

        return false !== $result ? $result : null;
    }

    /**
     * Set previousRequest
     *
     * @param  Celsius3\CoreBundle\Entity\Request $previousRequest
     * @return self
     */
    public function setPreviousRequest(\Celsius3\CoreBundle\Entity\Request $previousRequest)
    {
        $this->previousRequest = $previousRequest;

        return $this;
    }

    /**
     * Get previousRequest
     *
     * @return Celsius3\CoreBundle\Entity\Request $previousRequest
     */
    public function getPreviousRequest()
    {
        return $this->previousRequest;
    }

    /**
     * Add request
     *
     * @param Celsius3\CoreBundle\Entity\Request $request
     */
    public function addRequest(\Celsius3\CoreBundle\Entity\Request $request)
    {
        $this->requests[] = $request;
    }

    /**
     * Remove request
     *
     * @param Celsius3\CoreBundle\Entity\Request $request
     */
    public function removeRequest(\Celsius3\CoreBundle\Entity\Request $request)
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

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
 * @ODM\Document
 */
class Request
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback = {"\Celsius3\CoreBundle\Manager\OrderManager", "getTypes"}, message = "Choose a valid type.")
     * @ODM\String
     */
    private $type;
    /**
     * @ODM\String
     */
    private $comments;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $owner;
    /**
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $librarian;
    /**
     * @ODM\ReferenceMany(targetDocument="File", mappedBy="order")
     */
    private $files;
    /**
     * @ODM\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\Event\Event", mappedBy="request")
     */
    private $events;
    /**
     * @ODM\ReferenceMany(targetDocument="State", mappedBy="request")
     */
    private $states;
    /**
     * @Assert\NotNull(groups={"Default", "newOrder"})
     * @ODM\ReferenceOne(targetDocument="Instance")
     */
    private $instance;
    /**
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $operator;
    /**
     * @ODM\ReferenceOne(targetDocument="Order", inversedBy="requests")
     */
    private $order;
    /**
     * @ODM\ReferenceOne(targetDocument="Request", inversedBy="requests")
     */
    private $previousRequest;
    /**
     * @ODM\ReferenceMany(targetDocument="Request", mappedBy="previousRequest")
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
     * @param  Celsius3\CoreBundle\Document\BaseUser $owner
     * @return self
     */
    public function setOwner(\Celsius3\CoreBundle\Document\BaseUser $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set librarian
     *
     * @param  Celsius3\CoreBundle\Document\BaseUser $librarian
     * @return self
     */
    public function setLibrarian(\Celsius3\CoreBundle\Document\BaseUser $librarian)
    {
        $this->librarian = $librarian;

        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Add file
     *
     * @param Celsius3\CoreBundle\Document\File $file
     */
    public function addFile(\Celsius3\CoreBundle\Document\File $file)
    {
        $this->files[] = $file;
    }

    /**
     * Remove file
     *
     * @param Celsius3\CoreBundle\Document\File $file
     */
    public function removeFile(\Celsius3\CoreBundle\Document\File $file)
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
     * @param Celsius3\CoreBundle\Document\Event\Event $event
     */
    public function addEvent(\Celsius3\CoreBundle\Document\Event\Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Remove event
     *
     * @param Celsius3\CoreBundle\Document\Event\Event $event
     */
    public function removeEvent(\Celsius3\CoreBundle\Document\Event\Event $event)
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
     * @param Celsius3\CoreBundle\Document\State $state
     */
    public function addState(\Celsius3\CoreBundle\Document\State $state)
    {
        $this->states[] = $state;
    }

    /**
     * Remove state
     *
     * @param Celsius3\CoreBundle\Document\State $state
     */
    public function removeState(\Celsius3\CoreBundle\Document\State $state)
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

    /**
     * Set operator
     *
     * @param  Celsius3\CoreBundle\Document\BaseUser $operator
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
     * Set order
     *
     * @param  Celsius3\CoreBundle\Document\Order $order
     * @return self
     */
    public function setOrder(\Celsius3\CoreBundle\Document\Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius3\CoreBundle\Document\Order $order
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
                            return in_array($entry->getType()->getName(), $names);
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
                            return $entry->getType()->getName() == $name;
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
     * @param  Celsius3\CoreBundle\Document\Request $previousRequest
     * @return self
     */
    public function setPreviousRequest(\Celsius3\CoreBundle\Document\Request $previousRequest)
    {
        $this->previousRequest = $previousRequest;

        return $this;
    }

    /**
     * Get previousRequest
     *
     * @return Celsius3\CoreBundle\Document\Request $previousRequest
     */
    public function getPreviousRequest()
    {
        return $this->previousRequest;
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
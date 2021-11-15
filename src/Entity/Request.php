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

use Celsius3\CoreBundle\Entity\Event\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\RequestRepository")
 * @ORM\Table(name="request", indexes={
 *   @ORM\Index(name="idx_type", columns={"type"}),
 *   @ORM\Index(name="idx_owner", columns={"owner_id"}),
 *   @ORM\Index(name="idx_creator", columns={"creator_id"}),
 *   @ORM\Index(name="idx_librarian", columns={"librarian_id"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"}),
 *   @ORM\Index(name="idx_operator", columns={"operator_id"}),
 *   @ORM\Index(name="idx_order", columns={"order_id"}),
 *   @ORM\Index(name="idx_previous_request", columns={"previous_request_id"}),
 * }, uniqueConstraints={
 *   @ORM\UniqueConstraint(name="idx_order_instance", columns={"instance_id", "order_id"}),
 * })
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Request
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
     * @Assert\NotBlank
     * @Assert\Choice(callback = {"\Celsius3\Manager\OrderManager", "getTypes"}, message = "Choose a valid type.")
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="BaseUser", inversedBy="orders")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */
    private $owner;

    /**
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
     * @ORM\OneToMany(targetEntity="Celsius3\CoreBundle\Entity\Event\Event", mappedBy="request", fetch="EAGER")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="State", mappedBy="request", fetch="EAGER")
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
        $this->files = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->requests = new ArrayCollection();
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
     * Set type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set comments.
     *
     * @param string $comments
     *
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments.
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set owner.
     *
     * @param BaseUser $owner
     *
     * @return self
     */
    public function setOwner(BaseUser $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner.
     *
     * @return BaseUser $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set creator.
     *
     * @param BaseUser $creator
     *
     * @return self
     */
    public function setCreator(BaseUser $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator.
     *
     * @return BaseUser $creator
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set librarian.
     *
     * @param BaseUser $librarian
     *
     * @return self
     */
    public function setLibrarian(BaseUser $librarian)
    {
        $this->librarian = $librarian;

        return $this;
    }

    /**
     * Get librarian.
     *
     * @return BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Add file.
     *
     * @param File $file
     */
    public function addFile(File $file)
    {
        $this->files[] = $file;
    }

    /**
     * Remove file.
     *
     * @param File $file
     */
    public function removeFile(File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files.
     *
     * @return Collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add event.
     *
     * @param Event $event
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Remove event.
     *
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events.
     *
     * @return Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add state.
     *
     * @param State $state
     */
    public function addState(State $state)
    {
        $this->states[] = $state;
    }

    /**
     * Remove state.
     *
     * @param State $state
     */
    public function removeState(State $state)
    {
        $this->states->removeElement($state);
    }

    /**
     * Get states.
     *
     * @return Collection $states
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set operator.
     *
     * @param BaseUser $operator
     *
     * @return self
     */
    public function setOperator(BaseUser $operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator.
     *
     * @return BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set order.
     *
     * @param Order $order
     *
     * @return self
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Se buscan los archivos para un determinado evento.
     */
    public function getFilesForEvent($event)
    {
        return $this->getFiles()
                        ->filter(
                                function (File $entry) use ($event) {
                                    return $entry->getEvent()->getId() == $event->getId();
                                });
    }

    /**
     * Se buscan los archivos para descargar por el usuario.
     */
    public function getFilesForDownload()
    {
        $instance = $this->getOrder()->getOriginalRequest()->getInstance();

        return $this->getFiles()
                        ->filter(
                                function (File $entry) use ($instance) {
                                    return $entry->getEvent()->getInstance()->getId() == $instance->getId();
                                });
    }

    /**
     * Se buscan los archivos que aún no han sido descargados.
     */
    public function getNotDownloadedFiles()
    {
        $instance = $this->getOrder()->getOriginalRequest()->getInstance();

        return $this->getFiles()
                        ->filter(
                                function (File $entry) use ($instance) {
                                    return $entry->getEvent()->getInstance()->getId() == $instance->getId() && !$entry->isDownloaded();
                                });
    }

    /**
     * Retorna si el Request actual ha alcanzado un determinado estado o estados.
     */
    public function hasState($names)
    {
        if (!is_array($names)) {
            $names = array($names);
        }

        return $this->getStates()
                        ->filter(
                                function (State $entry) use ($names) {
                                    return in_array($entry->getType(), $names);
                                })->count() > 0;
    }

    /**
     * Retorna el estado con nombre $name para el Request actual.
     * Antes debería verificarse su existencia con hasState.
     */
    public function getState($name)
    {
        $result = $this->getStates()
                        ->filter(
                                function (State $entry) use ($name) {
                                    return $entry->getType() === $name;
                                })->first();

        return false !== $result ? $result : null;
    }

    /**
     * Retorna el estado actual para el presente Request.
     */
    public function getCurrentState()
    {
        $result = $this->getStates()
                        ->filter(
                                function (State $entry) {
                                    return $entry->isCurrent();
                                })->first();

        return false !== $result ? $result : null;
    }

    /**
     * Set previousRequest.
     *
     * @param Request $previousRequest
     *
     * @return self
     */
    public function setPreviousRequest(Request $previousRequest)
    {
        $this->previousRequest = $previousRequest;

        return $this;
    }

    /**
     * Get previousRequest.
     *
     * @return Request $previousRequest
     */
    public function getPreviousRequest()
    {
        return $this->previousRequest;
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

    public function hasDownloadableFiles()
    {
        $files = $this->getFiles()->filter(function (File $f) {
            return !$f->isDownloaded() || $f->hasDownloadTime();
        });

        return $files->count() > 0;
    }
}

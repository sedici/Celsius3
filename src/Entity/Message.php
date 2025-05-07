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

namespace Celsius3\Entity;

use Celsius3\Entity\Notifiable;
use Celsius3\Manager\NotificationManager;
use Celsius3\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ORM\Table(name: "message", indexes: [
    new ORM\Index(name: "idx_thread", columns: ["thread_id"]),
    new ORM\Index(name: "idx_sender", columns: ["sender_id"])
])]
class Message implements Notifiable
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected ?int $id = null;


    #[ORM\ManyToOne(targetEntity: Thread::class, inversedBy: "messages")]
    #[ORM\JoinColumn(name: "thread_id", referencedColumnName: "id", nullable: false)]
    protected Thread $thread;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: "sender_id", referencedColumnName: "id", nullable: false)]
    protected BaseUser $sender;


    // --- campos inversos (no se guardan en esta tabla) ---


    #[ORM\OneToMany(targetEntity: MessageMetadata::class, mappedBy: "message", cascade: ["all"], fetch: "EXTRA_LAZY")]
    protected Collection $metadata;


    public function __toString(): string
    {
        // return $this->getSender().' - '.$this->thread->getSubject();
        return $this->getSender().' - Thread: ' . $this->thread->getId();
    }


    public function getId(): ?int
    { return $this->id; }


    public function notify(NotificationManager $manager): void
    { $manager->notifyNewMessage($this); }


    public function addMetadatum(MessageMetadata $metadatum): self
    { $this->metadata[] = $metadatum; return $this; }


    public function removeMetadatum(MessageMetadata $metadatum): void
    { $this->metadata->removeElement($metadatum); }


    public function getMetadata(): Collection
    { return $this->metadata; }

    public function getSender(): BaseUser
    { return $this->sender; }


    public function getThread(): Thread
    { return $this->thread; }
}

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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Celsius3\Repository\ThreadRepository;


#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ORM\Table(name: "thread", indexes: [new ORM\Index(name: "idx_created_at", columns: ["created_at"])])]
class Thread
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected ?int $id = null;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\Column(name: "created_by_id", type: "integer")]
    protected $createdBy;


    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: "thread")]
    protected $messages;


    #[ORM\OneToMany(targetEntity: ThreadMetadata::class,mappedBy: "thread",cascade: ["all"])]
    protected $metadata;


    #[ORM\Column(name: "created_at", type: "datetime")]
    protected $createdAt;


    protected array $participants;


    public function removeMessage(Message $message): void
    { $this->messages->removeElement($message); }


    public function addMetadatum(ThreadMetadata $metadatum): static
    {
        $this->metadata[] = $metadatum;

        return $this;
    }


    public function removeMetadatum(ThreadMetadata $metadatum): void
    { $this->metadata->removeElement($metadatum); }


    public function getMetadata(): array|ArrayCollection
    { return $this->metadata; }


    public function getId(): int|null
    { return $this->id; }


    public function getMessages(): array|ArrayCollection
    { return $this->messages; }


    public function getCreatedBy(): BaseUser
    { return $this->createdBy; }
}

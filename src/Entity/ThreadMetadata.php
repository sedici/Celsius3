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

use Celsius3\Repository\BaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Celsius3\Repository\ThreadMetadataRepository;


#[
    ORM\Table(name: "thread_metadata"),
    ORM\Entity(repositoryClass: ThreadMetadataRepository::class),

    ORM\Index(name: "idx_thread", columns: ["thread_id"]),
    ORM\Index(name: "idx_participant", columns: ["participant_id"]),
    ORM\Index(name: "idx_last_message_date", columns: ["last_message_date"])
]
class ThreadMetadata
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected ?int $id = null;


    #[ORM\ManyToOne(targetEntity: Thread::class, inversedBy: "metadata")]
    #[ORM\JoinColumn(name: "thread_id", referencedColumnName: "id")]
    protected Thread $thread;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: "participant_id", referencedColumnName: "id")]
    protected BaseUser $participant;


    #[ORM\Column(
        type: Types::DATETIME_MUTABLE,
        name: "last_message_date",
        nullable: true
    )]
    protected ?\DateTime $lastMessageDate = null;


    #[ORM\Column(
        type: Types::DATETIME_MUTABLE,
        name: "last_participant_message_date",
        nullable: true
    )]
    protected ?\DateTime $lastParticipantMessageDate = null;


    #[ORM\Column(name: 'is_deleted', type: 'boolean')]
    private bool $isDeleted;


    public function getLastMessageDate(): \DateTime
    { return $this->lastMessageDate; }

    public function getThread(): Thread
    { return $this->thread; }

    public function getParticipant(): BaseUser
    { return $this->participant; }

    public function setThread(Thread $thread): void
    { $this->thread = $thread; }
}

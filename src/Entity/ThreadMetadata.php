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

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: \Celsius3\Repository\ThreadRepository::class)]
#[ORM\Table(name: "thread_metadata", indexes: [
    new ORM\Index(name: "idx_thread", columns: ["thread_id"]),
    new ORM\Index(name: "idx_participant", columns: ["participant_id"]),
    new ORM\Index(name: "idx_last_message_date", columns: ["last_message_date"])
])]
class ThreadMetadata
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected ?int $id = null;


    #[ORM\ManyToOne(targetEntity: Thread::class, inversedBy: "metadata")]
    protected Thread $thread;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    protected $participant;


    #[ORM\Column(type: "date", name: "last_message_date")]
    protected \DateTime $lastMessageDate;


    public function getLastMessageDate(): \DateTime
    { return $this->lastMessageDate; }
}

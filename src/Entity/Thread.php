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
use Celsius3\Repository\ThreadRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ORM\Table(
    name: "thread",
    indexes: [
        new ORM\Index(name: "idx_created_at",
        columns: ["created_at"])
    ]
)]
class Thread
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected ?int $id = null;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: "created_by_id", referencedColumnName: "id", nullable: false)]
    protected BaseUser $createdBy;
    
    
    #[ORM\Column(name: "created_at", type: "datetime")]
    protected \DateTime $createdAt;


    #[ORM\Column(name: "subject", type: "string", length: 255)]
    protected string $subject;

    // --- campos inversos (no se guardan en esta tabla) ---

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: "thread", fetch: "EXTRA_LAZY")]
    protected Collection $messages;


    #[ORM\OneToMany(targetEntity: ThreadMetadata::class, mappedBy: "thread", cascade: ["all"], fetch: "EXTRA_LAZY")]
    protected Collection $metadata;


    protected ?Collection $participants = null;
    

    public function removeMessage(Message $message): void
    { $this->messages->removeElement($message); }


    public function addMetadatum(ThreadMetadata $metadatum): static
    {
        $this->metadata[] = $metadatum;

        return $this;
    }


    public function removeMetadatum(ThreadMetadata $metadatum): void
    { $this->metadata->removeElement($metadatum); }


    public function getMetadata(): Collection
    { return $this->metadata; }


    public function getId(): int|null
    { return $this->id; }


    public function getMessages(): Collection
    { return $this->messages; }


    public function getCreatedBy(): BaseUser
    { return $this->createdBy; }


    public function getSubject(): string
    { return $this->subject; }


    public function getLastMessage(): ?Message
    {
        return ($this->getId() !== null) // Ensure thread is persisted and has an ID
            ? $this->messages->reduce(fn (?Message $carry, Message $message) =>
                ($carry === null)
                    ? $message
                    : ( ($message->getCreatedAt() > $carry->getCreatedAt()) // Explicitly group the nested ternary
                        ? $message
                        : $carry
                    )
            )
            : null;
    }


    public function isReadByParticipant(BaseUser $user): bool
    {
        foreach ($this->getMessages() as $message) {
            if (!$message->isReadByParticipant($user)) {
                return false;
            }
        }

        return true;
    }


    public function getParticipants(): Collection
    {
        return $this->messages->map(
            fn (Message $message): BaseUser =>
                $message->getSender()
        );
    }


    protected function getParticipantsCollection(): Collection
    {
        if (null === $this->participants) {
            $this->participants = new ArrayCollection();

            foreach ($this->metadata as $data) {
                $this->participants->add($data->getParticipant());
            }
        }

        return $this->participants;
    }


    public function addParticipant(BaseUser $participant): static
    {
        if (!$this->isParticipant($participant)) {
            $this->getParticipantsCollection()->add($participant);
        }

        return $this;
    }


    public function addParticipants(array $users): static
    {
        foreach ($users as $participant) {
            if (!$participant instanceof BaseUser) {
                throw new \InvalidArgumentException('Participant must be an instance of BaseUser');
            }
            $this->addParticipant($participant);
        }

        return $this;
    }


    public function isParticipant(BaseUser $user): bool
    { return $this->getParticipantsCollection()->contains($user); }


    public function addMetadata(ThreadMetadata $meta): static
    {
        $meta->setThread($this);
        $this->metadata->add($meta);
        
        return $this;
    }
}

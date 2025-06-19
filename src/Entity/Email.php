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

use Celsius3\Repository\EmailRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ORM\Entity(repositoryClass: EmailRepository::class),
    ORM\Table(name: "email")
]
class Email
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id;


    #[Assert\NotBlank()]
    #[ORM\Column(type: "string", length: 255)]
    private string $address;


    #[Assert\NotBlank()]
    #[ORM\Column(type: "string", length: 255)]
    private string $subject;


    #[Assert\NotBlank()]
    #[ORM\Column(type: "text")]
    private string $text;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: "sender_id", referencedColumnName: "id", nullable: false)]
    private BaseUser $sender;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id", nullable: false)]
    private Instance $instance;


    #[ORM\Column(name: "sent", type: "boolean")]
    private bool $sent;


    #[ORM\Column(name: "attempts", type: "integer")]
    private int $attempts = 0;


    #[ORM\Column(name: "error", type: "boolean")]
    private bool $error = false;


    public function getId(): int
    { return $this->id; }


    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }


    public function incrementAttempts(): static
    {
        $this->attempts++;
        return $this;
    }


    public function getAddress(): string
    { return $this->address; }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSubject(): string
    { return $this->subject; }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getText(): string
    { return $this->text; }

    public function setSender(BaseUser $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getSender(): BaseUser
    { return $this->sender; }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    public function getInstance(): Instance
    { return $this->instance; }

    public function setSent(bool $sent): self
    {
        $this->sent = $sent;
        return $this;
    }

    public function getSent(): bool
    { return $this->sent; }

    public function getAttempts(): int
    { return $this->attempts; }

    public function addAttempt(): void
    { $this->attempts++; }

    public function getError(): bool
    { return $this->error; }

    public function setError(bool $error): static
    {
        $this->error = $error;
        return $this;
    }
}

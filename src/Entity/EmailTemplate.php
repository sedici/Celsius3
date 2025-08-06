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

use Celsius3\Repository\EmailTemplateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: EmailTemplateRepository::class)]
class EmailTemplate extends Template
{

    #[ORM\Column(type: "boolean")]
    private bool $enabled = true;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    private string $title;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: "templates")]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id")]
    private ?Instance $instance = null;


    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEnabled(): bool
    { return $this->enabled; }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    { return $this->title; }

    public function setInstance(?Instance $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    public function getInstance(): ?Instance
    { return $this->instance; }
}

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

// use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Celsius3\Repository\LoginRepository;


#[
    ORM\Table(name: "login"),
    ORM\Entity(repositoryClass: LoginRepository::class),

    ORM\Index(name: "idx_category", columns: ["category"])
]
class Login
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $category = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "datetime")]
    private ?\DateTime $date = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "text")]
    private ?string $message = null;


    /**
     * Get id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set category.
     *
     * @param string $category
     *
     * @return self
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string $category
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * Set date.
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string $message
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}

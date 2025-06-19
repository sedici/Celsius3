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

use Celsius3\Repository\DataRequestRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ORM\Table(name: "data_request"),
    ORM\Entity(repositoryClass: DataRequestRepository::class),

    ORM\InheritanceType("SINGLE_TABLE"),
    ORM\DiscriminatorColumn(name: "type", type: "string"),
    ORM\DiscriminatorMap([
        "users_data_request" => UsersDataRequest::class,
        "orders_data_request" => OrdersDataRequest::class,
    ])
]
abstract class DataRequest
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected ?int $id = null;


    #[ORM\Column(type: "string")]
    #[Assert\NotBlank()]
    protected string $name;


    #[ORM\Column(name: "start_date", type: "date")]
    #[Assert\Date()]
    protected DateTime $startDate;


    #[ORM\Column(name: "end_date", type: "date")]
    #[Assert\Date()]
    protected DateTime $endDate;


    #[ORM\Column(type: "text", nullable: false)]
    #[Assert\NotNull()]
    protected string $data;


    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: "dataRequests")]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id")]
    private Instance $instance;


    #[ORM\Column(type: "boolean")]
    private bool $exported = false;


    #[ORM\Column(type: "boolean")]
    private bool $downloaded = false;


    #[ORM\Column(type: "boolean")]
    private bool $visible = true;


    #[ORM\Column(type: "string", nullable: true)]
    private ?string $file = null;


    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function getArrayData(): mixed
    {
        return unserialize($this->data);
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): void
    {
        $this->instance = $instance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getExported(): bool
    {
        return $this->exported;
    }

    public function setExported(bool $exported): self
    {
        $this->exported = $exported;

        return $this;
    }

    public function getDownloaded(): bool
    {
        return $this->downloaded;
    }

    public function setDownloaded(bool $downloaded): self
    {
        $this->downloaded = $downloaded;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): void
    {
        $this->file = $file;
    }

    public function getVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}

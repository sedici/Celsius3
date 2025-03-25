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

use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\BaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BaseRepository::class)]
#[ORM\Table(name: "file_download", indexes: [
    new ORM\Index(name: "idx_request", columns: ["request_id"]),
    new ORM\Index(name: "idx_user", columns: ["user_id"]),
    new ORM\Index(name: "idx_ip", columns: ["ip"]),
    new ORM\Index(name: "idx_instance", columns: ["instance_id"])
])]
class FileDownload
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[Assert\Ip]
    #[ORM\Column(type: "string", length: 255)]
    private string $ip;


    #[ORM\Column(type: "text", nullable: true)]
    private ?string $userAgent = null;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private BaseUser $user;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: File::class, inversedBy: "downloads")]
    #[ORM\JoinColumn(name: "file_id", referencedColumnName: "id", nullable: false)]
    private File $file;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Request::class)]
    #[ORM\JoinColumn(name: "request_id", referencedColumnName: "id", nullable: false)]
    private Request $request;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id", nullable: false)]
    private Instance $instance;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUser(BaseUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): BaseUser
    {
        return $this->user;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }
}

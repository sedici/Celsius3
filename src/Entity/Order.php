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

// use Celsius3\Entity\Mixin\SoftDeleteableEntity;
// use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Manager\StateManager;
use Celsius3\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[
    ORM\Table(name: '`order`'),
    ORM\Entity(repositoryClass: OrderRepository::class),

    ORM\Index(name: 'idx_code', columns: ['code']),
    ORM\Index(name: 'idx_created_at', columns: ['created_at']),
    ORM\Index(name: 'idx_material_data', columns: ['material_data_id']),
    ORM\Index(name: 'idx_original_request', columns: ['original_request_id']),

    Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false),

    ORM\HasLifecycleCallbacks
]
class Order implements \Stringable
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;


    #[ORM\Column(type: 'integer')]
    private int $code;


    #[ORM\OneToOne(
        targetEntity: MaterialType::class, inversedBy: 'order', cascade: ['persist']
    )]
    #[ORM\JoinColumn(
        name: 'material_data_id', referencedColumnName: 'id', nullable: true
    )]
    private ?MaterialType $materialData = null;


    #[Assert\NotNull]
    #[ORM\OneToOne(targetEntity: Request::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'original_request_id', referencedColumnName: 'id')]
    private ?Request $originalRequest = null;


    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'order', fetch: 'EXTRA_LAZY')]
    private Collection $requests;


    // ----


    public function __toString(): string
    {
        return strval($this->getCode());
    }

    public function __construct()
    {
        $this->requests = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->materialData = null;
        $this->requests = new ArrayCollection();
        $this->originalRequest = null;
    }

    public function getPages(): int
    {
        $files = $this->getOriginalRequest()->getFiles();
        $pages = 0;
        foreach ($files as $file) {
            if ($file->getEnabled()) {
                $pages += $file->getPages();
            }
        }

        return $pages;
    }

    public function getReceivedAt(): ?\DateTime
    {
        $states = $this->getOriginalRequest()->getStates();

        $receivedState = null;
        foreach ($states as $state) {
            if ($state->getType() === StateManager::STATE__RECEIVED) {
                $receivedState = $state;
                break 1;
            }
        }

        $receivedDate = null;
        if ($receivedState !== null) {
            $receivedDate = $receivedState->getCreatedAt();
        }

        return $receivedDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setMaterialData(?MaterialType $materialData = null): self
    {
        $this->materialData = $materialData;

        return $this;
    }

    public function getMaterialData(): ?MaterialType
    {
        return $this->materialData;
    }

    public function setOriginalRequest(?Request $originalRequest = null): self
    {
        $this->originalRequest = $originalRequest;

        return $this;
    }

    public function getOriginalRequest(): ?Request
    {
        return $this->originalRequest;
    }

    public function addRequest(Request $request): void
    {
        $this->requests[] = $request;
    }

    public function removeRequest(Request $request): void
    {
        $this->requests->removeElement($request);
    }

    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function hasRequest(Instance $instance): bool
    {
        return $this->getRequests()
            ->filter(
                fn(Request $entry): bool => $entry->getInstance()->getId() == $instance->getId()
            )->count() > 0;
    }

    public function getRequest(Instance $instance): ?Request
    {
        $result = $this->getRequests()
            ->filter(
                fn(Request $entry): bool => $entry->getInstance()->getId() == $instance->getId()
            )->first();

        return false !== $result ? $result : null;
    }
}

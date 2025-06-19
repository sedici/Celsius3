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

use Celsius3\Entity\Event\Event;
// use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[
    ORM\Table(name: "file"),
    ORM\Entity(repositoryClass: FileRepository::class),

    ORM\Index(name: "idx_event", columns: ["event_id"]),
    ORM\Index(name: "idx_request", columns: ["request_id"]),
    ORM\Index(name: "idx_instance", columns: ["instance_id"])
]
class File
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id;


    #[ORM\Column(type: "string", length: 255)]
    private string $name;


    #[ORM\Column(type: "string", length: 255)]
    private string $path;
    private string $temp;


    #[ORM\Column(type: "text", nullable: true)]
    private ?string $comments;


    #[Assert\File(maxSize: "8M", mimeTypes: ["application/pdf", "application/x-pdf"])]
    private ?UploadedFile $file;


    #[ORM\Column(type: "boolean")]
    private bool $enabled = true;


    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: "files")]
    private ?Request $request;


    #[ORM\ManyToOne(targetEntity: Instance::class)]
    private ?Instance $instance;


    #[ORM\ManyToOne(targetEntity: Event::class)]
    private ?Event $event;


    #[ORM\Column(type: "boolean")]
    private bool $downloaded = false;


    #[ORM\Column(type: "integer")]
    private int $pages = 0;


    #[ORM\OneToMany(targetEntity: FileDownload::class, mappedBy: "file")]
    private Collection $downloads;


    public function __construct()
    { $this->downloads = new ArrayCollection(); }

    public function getId(): int
    { return $this->id; }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    { return $this->name; }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getPath(): string
    { return $this->path; }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function getComments(): string
    { return $this->comments; }

    public function setFile(?UploadedFile $file = null): self
    {
        $this->file = $file;
        if (isset($this->path)) {
            $this->temp = $this->path;
            $this->path = '';
        } else {
            $this->path = 'initial';
        }

        return $this;
    }

    public function getFile(): ?UploadedFile
    { return $this->file; }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEnabled(): bool
    { return $this->enabled; }

    public function setEvent(?Event $event = null): self
    {
        $this->event = $event;
        return $this;
    }

    public function getEvent(): ?Event
    { return $this->event; }

    public function setDownloaded(bool $downloaded): self
    {
        $this->downloaded = $downloaded;
        return $this;
    }

    public function isDownloaded(): bool
    { return $this->downloaded; }

    public function setRequest(?Request $request = null): self
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest(): ?Request
    { return $this->request; }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;
        return $this;
    }

    public function getPages(): int
    { return $this->pages; }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    public function getInstance(): ?Instance
    { return $this->instance; }

    public function getTemp()
    { return $this->temp; }

    public function setTemp($temp)
    { $this->temp = $temp; }

    public function getDownloads(): Collection
    { return $this->downloads; }

    public function hasDownloadTime(): bool
    {
        if (!$this->isDownloaded()) {
            return true;
        }

        if ($this->getDownloads()) {
            $downloads = $this->getDownloads()->toArray();
            usort($downloads, function (FileDownload $a, FileDownload $b) {
                if ($a->getCreatedAt() === $b->getCreatedAt()) {
                    return 0;
                }

                return ($a->getCreatedAt() > $b->getCreatedAt()) ? -1 : 1;
            });

            $lastDownload = (!empty($downloads)) ? $downloads[0] : null;

            $downloadTimeConfig = $this->getInstance()->get('download_time');
            $value = (!empty($downloadTimeConfig->getValue())) ? $downloadTimeConfig->getValue() : '24';
            if (!is_null($lastDownload) && ($lastDownload->getCreatedAt()->add(new \DateInterval('PT'.$value.'H')) > new \DateTime())) {
                return true;
            }
        }

        return false;
    }

    public function getDownloaded(): bool
    { return $this->downloaded; }

    public function addDownload(FileDownload $download): self
    {
        $this->downloads[] = $download;
        return $this;
    }

    public function removeDownload(FileDownload $download): void
    { $this->downloads->removeElement($download); }
}

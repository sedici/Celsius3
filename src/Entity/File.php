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

use Celsius3\CoreBundle\Entity\Event\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\FileRepository")
 * @ORM\Table(name="file", indexes={
 *   @ORM\Index(name="idx_event", columns={"event_id"}),
 *   @ORM\Index(name="idx_request", columns={"request_id"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"})
 * })
 */
class File
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @Assert\File(maxSize="8M", mimeTypes = {"application/pdf", "application/x-pdf"})
     */
    private $file;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\ManyToOne(targetEntity="Request", inversedBy="files")
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="Instance")
     */
    private $instance;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
     */
    private $event;

    /**
     * @ORM\Column(type="boolean")
     */
    private $downloaded = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $pages = 0;
    private $temp;

    /**
     * @ORM\OneToMany(targetEntity="FileDownload", mappedBy="file")
     */
    private $downloads;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->downloads = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set comments.
     *
     * @param string $comments
     *
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments.
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set file.
     *
     * @param file $file
     *
     * @return self
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return file $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     *
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set event.
     *
     * @param Event $event
     *
     * @return self
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event.
     *
     * @return Event $event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set downloaded.
     *
     * @param bool $downloaded
     *
     * @return self
     */
    public function setDownloaded($downloaded)
    {
        $this->downloaded = $downloaded;

        return $this;
    }

    /**
     * Get downloaded.
     *
     * @return bool $downloaded
     */
    public function isDownloaded()
    {
        return $this->downloaded;
    }

    /**
     * Set request.
     *
     * @param Request $request
     *
     * @return self
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request.
     *
     * @return Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set pages.
     *
     * @param int $pages
     *
     * @return self
     */
    public function setPages($pages)
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * Get pages.
     *
     * @return int $pages
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    public function getTemp()
    {
        return $this->temp;
    }

    public function setTemp($temp)
    {
        $this->temp = $temp;
    }

    public function getDownloads()
    {
        return $this->downloads;
    }

    public function hasDownloadTime()
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

    /**
     * Get downloaded.
     *
     * @return bool
     */
    public function getDownloaded()
    {
        return $this->downloaded;
    }

    /**
     * Add download.
     *
     * @param FileDownload $download
     *
     * @return File
     */
    public function addDownload(FileDownload $download)
    {
        $this->downloads[] = $download;

        return $this;
    }

    /**
     * Remove download.
     *
     * @param FileDownload $download
     */
    public function removeDownload(FileDownload $download)
    {
        $this->downloads->removeElement($download);
    }
}

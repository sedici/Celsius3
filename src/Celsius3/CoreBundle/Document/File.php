<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\MongoDB\GridFSFile;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class File
{
    /**
     * @MongoDB\Id
     */
    private $id;
    /**
     * @MongoDB\String
     */
    private $name;
    /**
     * @MongoDB\String
     */
    private $path;
    /**
     * @MongoDB\String
     */
    private $comments;
    /**
     * @MongoDB\String
     */
    private $mime;
    /**
     * @MongoDB\File
     */
    private $file;
    /**
     * @MongoDB\Date
     */
    private $uploaded;
    /**
     * @MongoDB\Boolean
     */
    private $enabled;
    /**
     * @MongoDB\ReferenceOne(targetDocument="Request", inversedBy="files")
     */
    private $request;
    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event", inversedBy="files")
     */
    private $event;
    /**
     * @MongoDB\Boolean
     */
    private $isDownloaded = false;
    /**
     * @MongoDB\Int
     */
    private $pages = 0;

    public function getUploadDir()
    {
        // the absolute directory path where uploaded files should be saved
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'uploads' .
                DIRECTORY_SEPARATOR . 'temp';
    }

    /**
     * @MongoDB\PrePersist()
     * @MongoDB\PreUpdate()
     */
    public function prePersist()
    {
        if (!($this->file instanceof GridFSFile)) {
            $this->setName($this->file->getClientOriginalName());
            $this->setMime($this->file->getMimeType());
            $this->setPath(md5(rand(0, 999999)) . '.' . $this->getFile()->guessExtension());
            $this->getFile()->move($this->getUploadDir(), $this->getPath());
            $this->setFile($this->getUploadDir() . DIRECTORY_SEPARATOR . $this->getPath());
            $this->setUploaded(date('Y-m-d H:i:s'));
        }
    }

    /**
     * @MongoDB\PostPersist()
     * @MongoDB\PostUpdate()
     */
    public function postPersist()
    {
        if (file_exists($this->getUploadDir() . '/' . $this->getPath())) {
            unlink($this->getUploadDir() . '/' . $this->getPath());
        }
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param  string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set comments
     *
     * @param  string $comments
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set mime
     *
     * @param  string $mime
     * @return self
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string $mime
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set file
     *
     * @param  file $file
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return file $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set uploaded
     *
     * @param  date $uploaded
     * @return self
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Get uploaded
     *
     * @return date $uploaded
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set enabled
     *
     * @param  boolean $enabled
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set event
     *
     * @param  Celsius3\CoreBundle\Document\Event\Event $event
     * @return self
     */
    public function setEvent(\Celsius3\CoreBundle\Document\Event\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Celsius3\CoreBundle\Document\Event\Event $event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set isDownloaded
     *
     * @param  boolean $isDownloaded
     * @return self
     */
    public function setIsDownloaded($isDownloaded)
    {
        $this->isDownloaded = $isDownloaded;

        return $this;
    }

    /**
     * Get isDownloaded
     *
     * @return boolean $isDownloaded
     */
    public function getIsDownloaded()
    {
        return $this->isDownloaded;
    }

    /**
     * Set request
     *
     * @param  Celsius3\CoreBundle\Document\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Document\Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Document\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set pages
     *
     * @param  integer $pages
     * @return self
     */
    public function setPages($pages)
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * Get pages
     *
     * @return integer $pages
     */
    public function getPages()
    {
        return $this->pages;
    }
}
<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 */
class FileDownload
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\NotBlank
     * @Assert\Ip
     * @ODM\String
     */
    private $ip;
    /**
     * @Assert\NotBlank
     * @ODM\String
     */
    private $userAgent;
    /**
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $user;
    /**
     * @ODM\ReferenceOne(targetDocument="File")
     */
    private $file;
    /**
     * @ODM\ReferenceOne(targetDocument="Request")
     */
    private $request;

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
     * Set ip
     *
     * @param  string $ip
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userAgent
     *
     * @param  string $userAgent
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string $userAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set user
     *
     * @param  Celsius3\CoreBundle\Document\BaseUser $user
     * @return self
     */
    public function setUser(\Celsius3\CoreBundle\Document\BaseUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set file
     *
     * @param  Celsius3\CoreBundle\Document\File $file
     * @return self
     */
    public function setFile(\Celsius3\CoreBundle\Document\File $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return Celsius3\CoreBundle\Document\File $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set request
     *
     * @param  Celsius3\CoreBundle\Document\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Document\Request $request)
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
}
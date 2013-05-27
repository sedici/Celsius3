<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation\ReferenceOne;

/**
 * @MongoDB\Document
 */
class FileDownload
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Ip
     * @MongoDB\String
     */
    private $ip;

    /**
     * @Assert\NotBlank
     * @MongoDB\Timestamp
     */
    private $date;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $userAgent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $user;

    /**
     * @MongoDB\ReferenceOne(targetDocument="File")
     */
    private $file;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Order")
     */
    private $order;

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
     * @param string $ip
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
     * Set date
     *
     * @param timestamp $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return timestamp $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
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
     * @param Celsius\Celsius3Bundle\Document\BaseUser $user
     * @return self
     */
    public function setUser(\Celsius\Celsius3Bundle\Document\BaseUser $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set file
     *
     * @param Celsius\Celsius3Bundle\Document\File $file
     * @return self
     */
    public function setFile(\Celsius\Celsius3Bundle\Document\File $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return Celsius\Celsius3Bundle\Document\File $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set order
     *
     * @param Celsius\Celsius3Bundle\Document\Order $order
     * @return self
     */
    public function setOrder(\Celsius\Celsius3Bundle\Document\Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius\Celsius3Bundle\Document\Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }
}
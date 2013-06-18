<?php

namespace Celsius3\MessageBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\Thread as BaseThread;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\MessageBundle\Repository\ThreadRepository")
 */
class Thread extends BaseThread
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Celsius3\MessageBundle\Document\Message")
     */
    protected $messages;

    /**
     * @MongoDB\EmbedMany(targetDocument="Celsius3\MessageBundle\Document\ThreadMetadata")
     */
    protected $metadata;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    protected $participants;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    protected $createdBy;

}

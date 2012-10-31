<?php
// src/Acme/MessageBundle/Document/Message.php
namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\Message as BaseMessage;

/**
 * @MongoDB\Document
 */
class Message extends BaseMessage
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\EmbedMany(targetDocument="Celsius\Celsius3Bundle\Document\MessageMetadata")
     */
    protected $metadata;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius\Celsius3Bundle\Document\Thread")
     */
    protected $thread;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius\Celsius3Bundle\Document\BaseUser")
     */
    protected $sender;
}
?>

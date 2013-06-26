<?php
namespace Celsius3\MessageBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\Message as BaseMessage;
use Celsius3\NotificationBundle\Document\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;

/**
 * @MongoDB\Document
 */
class Message extends BaseMessage implements Notifiable
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\EmbedMany(targetDocument="Celsius3\MessageBundle\Document\MessageMetadata")
     */
    protected $metadata;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\MessageBundle\Document\Thread")
     */
    protected $thread;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    protected $sender;

    public function notify(NotificationManager $manager)
    {
        $manager->notify(NotificationManager::CAUSE__NEW_MESSAGE, $this);
    }
}

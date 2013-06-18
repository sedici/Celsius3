<?php

namespace Celsius3\MessageBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\MessageMetadata as BaseMessageMetadata;

/**
 * @MongoDB\EmbeddedDocument
 */
class MessageMetadata extends BaseMessageMetadata
{

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    protected $participant;

}

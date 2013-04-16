<?php

namespace Celsius\Celsius3MessageBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\MessageMetadata as BaseMessageMetadata;

/**
 * @MongoDB\EmbeddedDocument
 */
class MessageMetadata extends BaseMessageMetadata
{

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius\Celsius3Bundle\Document\BaseUser")
     */
    protected $participant;

}
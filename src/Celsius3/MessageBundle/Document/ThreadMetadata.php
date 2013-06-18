<?php
namespace Celsius3\MessageBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\ThreadMetadata as BaseThreadMetadata;

/**
 * @MongoDB\EmbeddedDocument
 */
class ThreadMetadata extends BaseThreadMetadata
{

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser")
     */
    protected $participant;

}

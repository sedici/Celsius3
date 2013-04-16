<?php
namespace Celsius\Celsius3MessageBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Document\ThreadMetadata as BaseThreadMetadata;

/**
 * @MongoDB\EmbeddedDocument
 */
class ThreadMetadata extends BaseThreadMetadata
{

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius\Celsius3Bundle\Document\BaseUser")
     */
    protected $participant;

}
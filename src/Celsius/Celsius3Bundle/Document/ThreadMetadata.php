<?php
namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use FOS\MessageBundle\Document\ThreadMetadata as BaseThreadMetadata;

/**
 * @ODM\EmbeddedDocument
 */
class ThreadMetadata extends BaseThreadMetadata
{
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius\Celsius3Bundle\Document\BaseUser")
     */
    protected $participant;
}
?>

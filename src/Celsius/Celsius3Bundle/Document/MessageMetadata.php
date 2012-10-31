<?php
namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use FOS\MessageBundle\Document\MessageMetadata as BaseMessageMetadata;

/**
 * @ODM\EmbeddedDocument
 */
class MessageMetadata extends BaseMessageMetadata
{
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius\Celsius3Bundle\Document\BaseUser")
     */
    protected $participant;
}
?>

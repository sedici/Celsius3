<?php

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 */
class Web extends Provider
{
    use TimestampableDocument;

    public function getProviderName()
    {
        return 'Found on the Web';
    }
}
<?php

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Web extends Provider
{

    public function getProviderName()
    {
        return 'Found on the Web';
    }

}

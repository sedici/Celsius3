<?php

namespace Celsius3\CoreBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Author extends Provider
{

    public function getProviderName()
    {
        return 'Provided by the author';
    }

}

<?php

namespace Celsius3\CoreBundle\Document\Event;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class UndoEvent extends SingleInstanceEvent
{
    
}

<?php

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;

class InstanceManager
{

    const INSTANCE__DIRECTORY = 'directory';

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function getDirectory()
    {
        return $this->dm->getRepository('Celsius3CoreBundle:Instance')
                        ->findOneBy(array('url' => self::INSTANCE__DIRECTORY));
    }

}

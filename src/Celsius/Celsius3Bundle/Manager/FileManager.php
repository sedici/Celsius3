<?php

namespace Celsius\Celsius3Bundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Receive;
use Celsius\Celsius3Bundle\Document\File;

class FileManager
{

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function uploadFiles(Receive $event, array $files = array())
    {
        foreach ($files as $uploadedFile)
        {
            $file = new File();
            $file->setFile($uploadedFile);
            $file->setEvent($event);
            $this->dm->persist($file);
            $this->dm->flush();
        }
    }

}
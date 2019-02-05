<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\EventListener;

use Celsius3\CoreBundle\Entity\File;
use Celsius3\CoreBundle\Manager\FileManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class FileUploadListener
{
    private $fileManager;

    /**
     * Set file manager.
     *
     * @param FileManager $fileManager
     */
    public function setFileManager(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->upload($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->upload($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preUpload($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->preUpload($args);
    }

    public function upload(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof File) {
            if (is_null($entity->getFile())) {
                return;
            }

            $entity->getFile()->move($this->fileManager->getUploadRootDir($entity), $entity->getPath());

            if (!is_null($entity->getTemp())) {
                unlink($this->fileManager->getUploadRootDir($entity).'/'.$entity->getTemp());
                $entity->setTemp(null);
            }


        }
    }

    public function preUpload(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof File) {
            if (!is_null($entity->getFile())) {
                $filename = sha1(uniqid(mt_rand(), true));
                $entity->setPath($filename.'.'.$entity->getFile()->guessExtension());
            }
        }
    }
}

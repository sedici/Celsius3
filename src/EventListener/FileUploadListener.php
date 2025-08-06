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

namespace Celsius3\EventListener;

use Celsius3\Entity\File;
use Celsius3\Manager\FileManager;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;


#[AsDoctrineListener(Events::prePersist)]
#[AsDoctrineListener(Events::postPersist)]
#[AsDoctrineListener(Events::preUpdate)]
#[AsDoctrineListener(Events::postUpdate)]
class FileUploadListener
{

    public function __construct(
        protected FileManager $fileManager
    )
    { }


    public function postPersist(PostPersistEventArgs $args): void
    { $this->upload($args); }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->upload($args);
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->preUpload($args);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->preUpload($args);
    }

    public function upload(\Doctrine\Persistence\Event\LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof File) {
            if ($entity->getFile() == null) {
                return;
            }

            $entity->getFile()->move(
                $this->fileManager->getUploadRootDir($entity),
                $entity->getPath()
            );

            if ($entity->getTemp() != null) {
                unlink($this->fileManager->getUploadRootDir($entity).'/'.$entity->getTemp());
                $entity->setTemp(null);
            }
        }
    }

    public function preUpload(\Doctrine\Persistence\Event\LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof File) {
            if ($entity->getFile() !== null) {
                $filename = sha1(
                    uniqid(mt_rand(), true)
                );
                $entity->setPath(
                    $filename.'.'.$entity->getFile()->guessExtension()
                );
            }
        }
    }
}

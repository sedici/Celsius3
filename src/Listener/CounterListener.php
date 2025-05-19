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

namespace Celsius3\Listener;

use Celsius3\Entity\Counter;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Order;
use Celsius3\Exception\Exception;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;


#[AsDoctrineListener(Events::prePersist)]
#[AsDoctrineListener(Events::postPersist)]
class CounterListener
{
    public function __construct
    (
        private EntityManagerInterface $entityManager
    ) {}


    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // throw new \Exception((string) var_dump($entity));

        if ($entity instanceof Order) {
            $this->entityManager->getConnection()->beginTransaction();
            try {
                $code = $this->entityManager->getRepository(Counter::class)
                    ->findOneBy([
                        'name' => $entity->getOriginalRequest()->getInstance()->getId(),
                    ]);
                $entity->setCode($code->getValue());

                $code->setValue($code->getValue() + 1);
                $this->entityManager->persist($code);

                $this->entityManager->getConnection()->commit();
            } catch (\Exception $e) {
                $this->entityManager->getConnection()->rollback();
                throw $e;
            }
        }
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $this->entityManager;

        if ($entity instanceof Instance) {
            $counter = new Counter();
            $counter->setName($entity->getId());
            $counter->setValue(1);
            $em->persist($counter);
            $em->flush();
        }
    }
}

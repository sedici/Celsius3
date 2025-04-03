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

use Doctrine\ORM\Event\LifecycleEventArgs;
use Celsius3\Entity\Order;
use Celsius3\Entity\Mixin\CodeGenerator;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

class OrderListener implements EventSubscriber
{
    public function __construct(private CodeGenerator $codeGenerator) {}

    public function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof Order && !$entity->getCode()) {
            $entity->setCode(
                $this->codeGenerator->generateCode(
                    Order::class,
                    'code'
                )
            );
        }
    }
}
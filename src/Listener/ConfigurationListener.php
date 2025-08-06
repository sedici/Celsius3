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
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Entity\Configuration;
use Celsius3\Entity\Instance;
use Celsius3\Helper\InstanceHelper;

class ConfigurationListener
{
    public function __construct(private readonly ConfigurationHelper $configuration_helper)
    {
    }

    public function postPersist(\Doctrine\ORM\Event\PostPersistEventArgs $args)
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();

        if ($entity instanceof Instance) {
            $default = $em->getRepository(Configuration::class)
                    ->findInstanceConfigurationByUrl(InstanceHelper::INSTANCE__DIRECTORY);

            foreach ($default as $configuration) {
                $new = $this->configuration_helper->duplicate($configuration);
                $new->setInstance($entity);

                if ($new->getKey() == 'api_key') {
                    $new->setValue(sha1($entity->getUrl().$entity->getName()));
                }

                if ($new->getKey() == 'instance_title') {
                    $new->setValue($entity->getName());
                }

                $em->persist($new);
            }
            $em->flush();
        } elseif ($entity instanceof Configuration) {
            if (!$entity->getInstance()) {
                $instances = $em->getRepository(Instance::class)
                        ->findAllInstancesExceptByUrl(InstanceHelper::INSTANCE__DIRECTORY);

                foreach ($instances as $instance) {
                    $new = $this->configuration_helper->duplicate($entity);
                    $new->setInstance($instance);
                    $em->persist($new);
                }
                $em->flush();
            }
        }
    }
}

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

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Entity\Configuration;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\InstanceManager;

class ConfigurationListener
{
    private $configuration_helper;

    public function __construct(ConfigurationHelper $configuration_helper)
    {
        $this->configuration_helper = $configuration_helper;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof Instance) {
            $default = $em
                    ->getRepository('Celsius3CoreBundle:Configuration')
                    ->createQueryBuilder('c')
                    ->join('c.instance', 'i')
                    ->where('i.url = :url')
                    ->setParameter(':url', InstanceManager::INSTANCE__DIRECTORY)
                    ->getQuery()
                    ->getResult();

            foreach ($default as $configuration) {
                $new = $this->configuration_helper->duplicate($configuration);
                $new->setInstance($entity);

                if ($new->getKey() == 'api_key') {
                    $new->setValue(sha1($entity->getUrl() . $entity->getName()));
                }

                $em->persist($new);
            }
            $em->flush();
        } elseif ($entity instanceof Configuration) {
            if (!$entity->getInstance()) {
                $instances = $em
                        ->getRepository('Celsius3CoreBundle:Instance')
                        ->createQueryBuilder()
                        ->field('url')->notEqual(InstanceManager::INSTANCE__DIRECTORY)
                        ->getQuery()
                        ->execute();

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

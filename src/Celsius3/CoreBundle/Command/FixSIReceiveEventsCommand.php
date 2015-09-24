<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Entity\CatalogResult;
use Celsius3\CoreBundle\Manager\CatalogManager;

class FixSIReceiveEventsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3:fix-receives')
            ->setDescription('Corrige un error en la recepción de algunos pedidos.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $events = $em->getRepository('Celsius3CoreBundle:Event\\SingleInstanceReceiveEvent')
                ->createQueryBuilder('s')
                ->where('s.deliveryType IS NULL')
                ->getQuery()
                ->execute();

        foreach ($events as $event) {
            echo 'Updating Event ' . $event->getId() . "\n";
            $owner = $event->getRequest()->getOwner();
            $event->setDeliveryType($owner->getPdf() ? 'PDF' : 'Printed');
            $em->persist($event);
            $em->flush($event);
        }
        $em->clear();
    }
}

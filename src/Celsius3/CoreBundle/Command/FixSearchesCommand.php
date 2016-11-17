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

namespace Celsius3\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Entity\CatalogResult;
use Celsius3\CoreBundle\Manager\CatalogManager;

class FixSearchesCommand extends ContainerAwareCommand
{
    private $positive = array(
        CatalogManager::CATALOG__FOUND,
        CatalogManager::CATALOG__PARTIALLY_FOUND,
    );

    protected function configure()
    {
        $this->setName('celsius3:fix-searches')
            ->setDescription('Actualiza la información de títulos buscados.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conn = $this->getContainer()->get('doctrine.dbal.default_connection');

        $limit = 1000;
        $offset = 0;

        $event_count = $em->getRepository('Celsius3CoreBundle:Event\\SearchEvent')
            ->getSearchEventCount()->getQuery()->getSingleScalarResult();

        while ($offset < $event_count) {
            $events = $em->getRepository('Celsius3CoreBundle:Event\\SearchEvent')
                ->getOffsetAndLimitTo($offset, $limit)->getQuery()->execute();

            $sql = 'SELECT m.tuple FROM metadata m WHERE m.table LIKE :entity AND m.entityId = :id';
            foreach ($events as $event) {
                echo 'Updating search '.$event->getId()."\n";
                $query = $conn->prepare($sql);
                $id = $event->getId();
                $entity = 'busquedas';
                $query->bindParam('id', $id);
                $query->bindParam('entity', $entity, \PDO::PARAM_STR);
                $query->execute();

                $t = $query->fetch();
                $data = unserialize(base64_decode($t['tuple']));
                if ($data) {
                    if ($event->getRequest()->getOrder()->getMaterialData() instanceof JournalType) {
                        if (!is_null($event->getRequest()->getOrder()->getMaterialData()->getJournal())) {
                            $title = $event->getRequest()->getOrder()->getMaterialData()->getJournal()->getName();
                        } else {
                            $title = $event->getRequest()->getOrder()->getMaterialData()->getOther();
                        }
                    } else {
                        $title = $event->getRequest()->getOrder()->getMaterialData()->getTitle();
                    }

                    $result = $em->getRepository('Celsius3CoreBundle:CatalogResult')
                            ->getCatalogResultByTitle($event->getCatalog()->getId(), $title)
                            ->getQuery()->getOneOrNullResult();

                    if (!$result) {
                        $result = new CatalogResult();
                        $result->setCatalog($event->getCatalog());
                        $result->setTitle($title);
                    }
                    if ($event->getResult() !== CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() + 1);
                    }
                    if (in_array($event->getResult(), $this->positive)) {
                        $result->setMatches($result->getMatches() + 1);
                    }

                    $em->persist($result);
                    $em->flush($result);
                }

                unset($query, $t, $data, $event, $result, $title, $id, $entity);
            }
            $em->clear();

            unset($sql, $events);
            gc_collect_cycles();

            $offset += $limit;
        }
    }
}

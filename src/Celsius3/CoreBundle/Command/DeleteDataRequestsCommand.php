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

use Celsius3\CoreBundle\Entity\DataRequest;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteDataRequestsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3:delete-data-requests')
            ->setDescription('Elimina todos los archivos de solicitud de datos descargados o con un tiempo superior y oculta sus referencias.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var DataRequest $dr */
        $dataRequests = $em->getRepository(DataRequest::class)->findAllDownloadedOrExpired();

        $directory = $this->getContainer()->getParameter('data_requests_directory');
        foreach ($dataRequests as $dr) {
            if ($filename = $dr->getFile()) {
                if (file_exists($file = $directory . $filename)) {
                    unlink($file);
                }
            }
            $em->persist($dr->setVisible(false));
        }
        $em->flush();
    }

}

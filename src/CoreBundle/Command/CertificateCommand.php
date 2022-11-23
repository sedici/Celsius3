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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Command;

use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CertificateCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('celsius3:certificate:update')
            ->setDescription('Update certificates for enabled instances.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $public_path = $this->getContainer()->get('kernel')->getRootDir().'/../web';

        # Se toma la ip actual del servidor
        $server_ip = gethostbyname('servicio.prebi.unlp.edu.ar');

        $domains = $em->getRepository(Instance::class)->findAllDomains();
        $directory = $em->getRepository(Instance::class)->findOneBy(['url' => 'directory']);

        # Se descartan aquellos que no apunten a nuestro servidor
        $valid_domains = [];
        foreach ($domains as $domain) {
            if (gethostbyname($domain['host']) === $server_ip) {
                $valid_domains[] = $domain['host'];
            }
        }

        $command = 'certbot --non-interactive '.
            '--agree-tos -m soporte.celsius@prebi.unlp.edu.ar '.
            '-q '.
            '--expand certonly '.
            '--webroot -w '.$public_path.' '.
            '-d '.implode(' -d ', $valid_domains).' -d '.$directory->getHost();

        # Solicitud de certificado
        $output->writeln(shell_exec($command));
    }
}

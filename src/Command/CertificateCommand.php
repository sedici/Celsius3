<?php

declare(strict_types=1);

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


namespace Celsius3\Command;

use Celsius3\Repository\InstanceRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CertificateCommand extends Command
{
    private $instanceRepository;
    private $kernel;

    public function __construct(InstanceRepositoryInterface $instanceRepository, KernelInterface $kernel)
    {
        parent::__construct();

        $this->instanceRepository = $instanceRepository;
        $this->kernel = $kernel;
    }

    protected function configure(): void
    {
        $this
            ->setName('celsius3:certificate:update')
            ->setDescription('Update certificates for enabled instances.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $publicPath = $this->kernel->getRootDir() . '/../web';

        # Se toma la ip actual del servidor
        $serverIp = gethostbyname('servicio.prebi.unlp.edu.ar');

        $domains = $this->instanceRepository->findAllDomains();
        $directory = $this->instanceRepository->findOneBy(['url' => 'directory']);

        # Se descartan aquellos que no apunten a nuestro servidor
        $validDomains = [];
        foreach ($domains as $domain) {
            if (gethostbyname($domain['host']) === $serverIp) {
                $validDomains[] = $domain['host'];
            }
        }

        $command = 'certbot --non-interactive ' .
            '--agree-tos -m soporte.celsius@prebi.unlp.edu.ar ' .
            '--expand certonly ' .
            '--webroot -w ' . $publicPath . ' ' .
            '-d ' . implode(' -d ', $validDomains) . ' -d ' . $directory->getHost();

        # Solicitud de certificado
        $output->writeln(shell_exec($command));

        return 0;
    }
}

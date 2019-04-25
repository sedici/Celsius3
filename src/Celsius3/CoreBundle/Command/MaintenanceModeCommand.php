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
use Symfony\Component\Console\Input\InputArgument;

class MaintenanceModeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3:mode:maintenance')
                ->setDescription('Enabled/Disabled maintenance mode.')
                ->addArgument('status', InputArgument::REQUIRED, 'Mode status.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $status = $input->getArgument('status');

        $projectDir = $this->getContainer()->getParameter('kernel.project_dir');
        $configFile = $this->getContainer()->getParameter('virtual_host_config_file');

        $directoryIndex = 'DirectoryIndex app.php';
        $maintenanceDirectoryIndex = 'DirectoryIndex index.html';
        $documentRoot = $projectDir .'/web';
        $maintenanceDocumentRoot = $documentRoot . '/maintenance';

        if ($status === 'enabled') {
            $file = $projectDir . '/web/maintenance/index.html';
            $data = $this->getContainer()->get('twig')->render('Celsius3CoreBundle:Mode:maintenance.html.twig');

            file_put_contents($file, $data);

            shell_exec("sudo sed -i 's%$documentRoot\"%$maintenanceDocumentRoot\"%g' $configFile");
            shell_exec("sudo sed -i 's%$directoryIndex%$maintenanceDirectoryIndex%g' $configFile");

            $output->writeln('The maintenance mode has been enabled.');
        } elseif ($status === 'disabled') {
            shell_exec("sudo sed -i 's%$maintenanceDocumentRoot\"%$documentRoot\"%g' $configFile");
            shell_exec("sudo sed -i 's%$maintenanceDirectoryIndex%$directoryIndex%g' $configFile");

            $output->writeln('The maintenance mode has been disabled.');
        } else {
            $output->writeln('The argument ' . $status . ' is not supported.');
            return;
        }

        shell_exec('sudo service apache2 reload');
    }

}

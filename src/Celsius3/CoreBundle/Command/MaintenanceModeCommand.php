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

        $class = new \ReflectionClass($this);
        $basedir = dirname($class->getFileName()) . '/../../../..';
        $modedir = $basedir . $this->getContainer()->getParameter('maintenance_mode_dir');
        $filename = $modedir . $this->getContainer()->getParameter('maintenance_mode_file');

        if ($status === 'enabled') {
            if (!file_exists($filename)) {
                $file = fopen($filename, 'w');
                fclose($file);
            }
            $output->writeln('The maintenance mode has been enabled.');
        }

        if ($status === 'disabled') {
            if (file_exists($filename)) {
                unlink($filename);
            }
            $output->writeln('The maintenance mode has been enabled.');
        }

        if ($status !== 'enabled' && $status !== 'disabled') {
            $output->writeln('The argument ' . $status . ' is not supported.');
        }
    }

}

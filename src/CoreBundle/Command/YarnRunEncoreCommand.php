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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class YarnRunEncoreCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3:yarn:encore')
            ->setDescription('Package assets')
            ->addArgument('env', InputArgument::OPTIONAL, 'Environment', 'dev')
            ->addOption('watch', 'w', InputOption::VALUE_NONE, 'Watch mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = $input->getArgument('env');
        $watch = $input->getOption('watch');

        $params = '';
        if ($watch) {
            $params .= ' --watch';
        }

        $output->writeln('Packaging assets');
        $output->writeln(shell_exec("yarn encore $env $params"));
    }
}

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

namespace Celsius3\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class OAuth2ClientCreateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3_api:oauth:create_client')
                ->setDescription('Create an OAuth client')
                ->addArgument('instance', InputArgument::REQUIRED, 'Instance URL')
                ->addArgument('redirect_uri', InputArgument::REQUIRED, 'Redirect URI');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $url = $input->getArgument('instance');
        $instance = $entityManager->getRepository('Celsius3CoreBundle:Instance')
                ->findOneBy(array('url' => $url));
        if (is_null($instance)) {
            $output->writeln('The instance with the url ' . $url . ' does not exists');
            exit;
        }

        $redirectUri = $input->getArgument('redirect_uri');
        if (!filter_var($redirectUri, FILTER_VALIDATE_URL)) {
            $output->writeln('The redirect uri ' . $redirectUri . ' is not valid.');
            exit;
        }

        $output->writeln('');
        $output->writeln('Creating client');

        $client = $clientManager->createClient();
        $client->setInstance($instance);
        $client->setRedirectUris(array($redirectUri));
        $client->setAllowedGrantTypes(array('authorization_code','refresh_token','token'));

        $clientManager->updateClient($client);

        $output->writeln('');
        $output->writeln('Client ID: ' . $client->getPublicId());
        $output->writeln('Client Secret: ' . $client->getSecret());
        $output->writeln('');
    }

}

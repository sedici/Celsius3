<?php

namespace Celsius3\NotificationBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('notification:server')
                ->setDescription('Starts the Notification Server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $main = $this->getContainer()->get('celsius3_notification.entry_point');

        $output->writeln('Starting Notification Server');

        $main->setOutput($output);

        $main->launch();
    }
}

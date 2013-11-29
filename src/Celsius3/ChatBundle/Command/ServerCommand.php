<?php

namespace Celsius3\ChatBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('chat:server')->setDescription('Starts the Chat Server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $main = $this->getContainer()->get('celsius3_chat.entry_point');

        $output->writeln('Starting Chat Server');

        $main->setOutput($output);

        $main->launch();
    }

}

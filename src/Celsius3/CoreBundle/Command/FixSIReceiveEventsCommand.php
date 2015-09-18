<?php

namespace Celsius3\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Entity\CatalogResult;
use Celsius3\CoreBundle\Manager\CatalogManager;

class FixSIReceiveEventsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3:fix-receives')
            ->setDescription('Corrige un error en la recepción de algunos pedidos.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $events = $em->getRepository('Celsius3CoreBundle:Event\\SingleInstanceReceiveEvent')
                ->createQueryBuilder('s')
                ->where('s.deliveryType IS NULL')
                ->getQuery()
                ->execute();

        foreach ($events as $event) {
            echo 'Updating Event ' . $event->getId() . "\n";
            $owner = $event->getRequest()->getOwner();
            $event->setDeliveryType($owner->getPdf() ? 'PDF' : 'Printed');
            $em->persist($event);
            $em->flush($event);
        }
        $em->clear();
    }
}

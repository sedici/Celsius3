<?php

namespace Celsius\Celsius3Bundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius\Celsius3Bundle\Document\Configuration;
use Celsius\Celsius3Bundle\Document\Instance;

class ConfigurationListener
{

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Instance)
        {
            $default = $dm->getRepository('CelsiusCelsius3Bundle:Configuration')
                    ->createQueryBuilder()
                    ->field('instance')->equals(null)
                    ->getQuery()
                    ->execute();

            foreach ($default as $configuration)
            {
                $new = Configuration::duplicate($configuration);
                $new->setInstance($document);
                $dm->persist($new);
                $dm->flush();
            }
        } elseif ($document instanceof Configuration)
        {
            $instances = $dm->getRepository('CelsiusCelsius3Bundle:Instance')
                    ->createQueryBuilder()
                    ->getQuery()
                    ->execute();

            foreach ($instances as $instance)
            {
                $new = Configuration::duplicate($document);
                $new->setInstance($instance);
                $dm->persist($new);
                $dm->flush();
            }
        }
    }

}
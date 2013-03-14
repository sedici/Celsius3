<?php

namespace Celsius\Celsius3Bundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius\Celsius3Bundle\Helper\ConfigurationHelper;
use Celsius\Celsius3Bundle\Document\Configuration;
use Celsius\Celsius3Bundle\Document\Instance;

class ConfigurationListener
{

    private $configuration_helper;

    public function __construct(ConfigurationHelper $configuration_helper)
    {
        $this->configuration_helper = $configuration_helper;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Instance)
        {
            $default = $dm->getRepository('CelsiusCelsius3Bundle:Configuration')
                    ->findBy(array('instance' => null));

            foreach ($default as $configuration)
            {
                $new = $this->configuration_helper->duplicate($configuration);
                $new->setInstance($document);
                $dm->persist($new);
                $dm->flush();
            }
        } elseif ($document instanceof Configuration)
        {
            if (!$document->getInstance())
            {
                $instances = $dm->getRepository('CelsiusCelsius3Bundle:Instance')
                        ->findAll();

                foreach ($instances as $instance)
                {
                    $new = $this->configuration_helper->duplicate($document);
                    $new->setInstance($instance);
                    $dm->persist($new);
                    $dm->flush();
                }
            }
        }
    }

}
<?php

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Document\Configuration;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\InstanceManager;

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

        if ($document instanceof Instance) {
            $default = $dm
                    ->getRepository('Celsius3CoreBundle:Configuration')
                    ->findBy(array('instance' => null));

            foreach ($default as $configuration) {
                $new = $this->configuration_helper->duplicate($configuration);
                $new->setInstance($document);
                $dm->persist($new);
                $dm->flush();
            }
        } elseif ($document instanceof Configuration) {
            if (!$document->getInstance()) {
                $instances = $dm
                        ->getRepository('Celsius3CoreBundle:Instance')
                        ->createQueryBuilder()
                        ->field('url')->notEqual(InstanceManager::INSTANCE__DIRECTORY)
                        ->getQuery()
                        ->execute();

                foreach ($instances as $instance) {
                    $new = $this->configuration_helper->duplicate($document);
                    $new->setInstance($instance);
                    $dm->persist($new);
                    $dm->flush();
                }
            }
        }
    }

}

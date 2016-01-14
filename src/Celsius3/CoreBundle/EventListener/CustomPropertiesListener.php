<?php

namespace Celsius3\CoreBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\TransformEvent;

class CustomPropertiesListener implements EventSubscriberInterface
{

    public function addCustomProperties(TransformEvent $event)
    {
        $object = $event->getObject();
        if ($object instanceof \Celsius3\CoreBundle\Entity\BookType) {
            $document = $event->getDocument();
            $document->set('editor', $object->getEditor());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'addCustomProperties',
        );
    }

}

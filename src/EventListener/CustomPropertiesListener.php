<?php

namespace Celsius3\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\TransformEvent;

class CustomPropertiesListener implements EventSubscriberInterface
{

    public function addCustomProperties(TransformEvent $event)
    {
        $object = $event->getObject();
        $document = $event->getDocument();

        if ($object instanceof \Celsius3\Entity\BookType) {
            $document->set('editor', $object->getEditor());
            $document->set('isbn', $object->getISBN());
            $document->set('chapter', $object->getChapter());
        } elseif ($object instanceof \Celsius3\Entity\JournalType) {
            if (!is_null($object->getJournal())) {
                $document->set('journal', $object->getJournal()->getName());
            } else {
                $document->set('journal', $object->getOther());
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'addCustomProperties',
        );
    }

}

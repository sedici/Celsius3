<?php

namespace Celsius3\EventListener;

use Celsius3\Entity\BookType;
use Celsius3\Entity\JournalType;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\AbstractTransformEvent;

class CustomPropertiesListener implements EventSubscriberInterface
{

    public function addCustomProperties(AbstractTransformEvent $event)
    {
        $object = $event->getObject();
        $document = $event->getDocument();

        if ($object instanceof BookType) {
            $document->set('editor', $object->getEditor());
            $document->set('isbn', $object->getISBN());
            $document->set('chapter', $object->getChapter());
        } elseif ($object instanceof JournalType) {
            if ($object->getJournal() !== null) {
                $document->set('journal', $object->getJournal()->getName());
            } else {
                $document->set('journal', $object->getOther());
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            PostTransformEvent::class => 'addCustomProperties',
        ];
    }

}

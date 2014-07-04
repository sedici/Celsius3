<?php

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Document\Event\SearchEvent;
use Celsius3\CoreBundle\Document\JournalType;
use Celsius3\CoreBundle\Document\CatalogResult;
use Celsius3\CoreBundle\Manager\CatalogManager;

class SearchEventListener
{

    private $negative = array(
        CatalogManager::CATALOG__NOT_FOUND,
        CatalogManager::CATALOG__NON_SEARCHED,
    );
    private $positive = array(
        CatalogManager::CATALOG__FOUND,
        CatalogManager::CATALOG__PARTIALLY_FOUND,
    );
    private $result = null;

    public function preUpdate(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof SearchEvent) {
            $uow = $dm->getUnitOfWork();
            $changeset = $uow->getDocumentChangeSet($document);

            if ($document->getRequest()->getOrder()->getMaterialData() instanceof JournalType) {
                $title = $document->getRequest()->getOrder()->getMaterialData()->getJournal()->getName();
            } else {
                $title = $document->getRequest()->getOrder()->getMaterialData()->getTitle();
            }

            if (array_key_exists('result', $changeset) && $changeset['result'][0] !== $changeset['result'][1]) {
                $result = $dm->getRepository('Celsius3CoreBundle:CatalogResult')
                        ->findOneBy(array(
                    'catalog.id' => $document->getCatalog()->getId(),
                    'title' => $title,
                ));
                $old = $changeset['result'][0];
                $new = $changeset['result'][1];

                if (in_array($old, $this->positive) && in_array($new, $this->negative)) {
                    $result->setMatches($result->getMatches() - 1);
                    if ($new === CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() - 1);
                    }
                } elseif (in_array($old, $this->negative) && in_array($new, $this->positive)) {
                    $result->setMatches($result->getMatches() + 1);
                    if ($old === CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() + 1);
                    }
                } elseif (in_array($old, $this->negative) && in_array($new, $this->negative)) {
                    if ($old === CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() + 1);
                    } else {
                        $result->setSearches($result->getSearches() - 1);
                    }
                }
                $this->result = $result;
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof SearchEvent) {
            if ($document->getRequest()->getOrder()->getMaterialData() instanceof JournalType) {
                $title = $document->getRequest()->getOrder()->getMaterialData()->getJournal()->getName();
            } else {
                $title = $document->getRequest()->getOrder()->getMaterialData()->getTitle();
            }

            $result = $dm->getRepository('Celsius3CoreBundle:CatalogResult')
                    ->findOneBy(array(
                'catalog.id' => $document->getCatalog()->getId(),
                'title' => $title,
            ));

            if (!$result) {
                $result = new CatalogResult();
                $result->setCatalog($document->getCatalog());
                $result->setTitle($title);
            }
            if ($document->getResult() !== CatalogManager::CATALOG__NON_SEARCHED) {
                $result->setSearches($result->getSearches() + 1);
            }
            if (in_array($document->getResult(), $this->positive)) {
                $result->setMatches($result->getMatches() + 1);
            }

            $dm->persist($result);
            $dm->flush();
        }
    }

    public function postUpdate(LifecycleEventArgs $args, $update = false)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof SearchEvent) {
            if ($this->result) {
                $dm->persist($this->result);
                $dm->flush();
            }
        }
    }

}

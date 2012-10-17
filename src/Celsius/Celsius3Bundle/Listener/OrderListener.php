<?php

namespace Celsius\Celsius3Bundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;

class OrderListener
{

    private $lh = null;

    public function postPersist(LifecycleEventArgs $args)
    {   
        if (is_null($this->lh))
        {
            /**
             * @todo reemplazar este new por un pedido al servicio 
             */
            $this->lh = new LifecycleHelper($args->getDocumentManager(), new StateManager());
        }

        $document = $args->getDocument();

        if ($document instanceof Order)
        {
            $this->lh->createEvent('creation', $document);
        }
    }

}
<?php

namespace Celsius\Celsius3Bundle\Listener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Manager\EventManager;

class OrderListener {

	private $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	public function postPersist(LifecycleEventArgs $args) {
		$document = $args->getDocument();

		if ($document instanceof Order) {
			$this->container->get('lifecycle_helper')
					->createEvent(EventManager::EVENT__CREATION, $document,
							$document->getInstance());
		}
	}
}

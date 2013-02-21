<?php

namespace Celsius\Celsius3Bundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius\Celsius3Bundle\Document\Institution;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Form\Type\OrderRequestType;
use Celsius\Celsius3Bundle\Form\Type\OrderReceiveType;

class EventManager
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function prepareExtraDataForRequest(Order $order, array $extraData)
    {
        $form = $this->container->get('form.factory')->create(new OrderRequestType($this->container->get('doctrine.odm.mongodb.document_manager'), 'Celsius\Celsius3Bundle\Document\SingleInstanceRequest'), new SingleInstanceRequest());
        $request = $this->container->get('request');

        $form->bind($request);

        if ($form->isValid())
        {
            $provider = $form->getData()->getProvider();
            $extraData['provider'] = $provider;
        } else
        {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            return new RedirectResponse($this->container->get('router')->generate('admin_order_show', array('id' => $order->getId())));
        }

        return $extraData;
    }

    private function prepareExtraDataForReceive(Order $order, array $extraData)
    {
        if (!$this->container->get('request')->query->has('request'))
        {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            return new RedirectResponse($this->container->get('router')->generate('admin_order_show', array('id' => $order->getId())));
        }
        
        $form = $this->container->get('form.factory')->create(new OrderReceiveType());
        $request = $this->container->get('request');

        $form->bind($request);

        if ($form->isValid())
        {
            $data = $form->getData();
            $extraData['request'] = $this->container->get('request')->query->get('request');
            $extraData['files'] = $data['files'];
        } else
        {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            return new RedirectResponse($this->container->get('router')->generate('admin_order_show', array('id' => $order->getId())));
        }

        return $extraData;
    }

    public function getRealEventName($event, array $extraData)
    {
        switch ($event)
        {
            case 'request': $event = ($extraData['provider'] instanceof Institution && $extraData['provider']->getInstance()) ? 'mirequest' : 'sirequest';
                break;
            default:;
        }
        return $event;
    }

    public function prepareExtraData($event, Order $order)
    {
        $extraData = array();
        switch ($event)
        {
            case 'request': $extraData = $this->prepareExtraDataForRequest($order, $extraData);
                break;
            case 'receive': $extraData = $this->prepareExtraDataForReceive($order, $extraData);
                break;
            default:;
        }
        return $extraData;
    }

}
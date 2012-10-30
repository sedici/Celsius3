<?php

namespace Celsius\Celsius3Bundle\Controller;

use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Form\Type\OrderType;

class OrderController extends BaseInstanceDependentController
{

    protected function listQuery($name)
    {
        return parent::listQuery($name)
                        ->field('currentState')->prime(true);
    }

    protected function change()
    {
        $material = 'Celsius\\Celsius3Bundle\\Form\\Type\\' . ucfirst($this->getRequest()->get('material')) . 'TypeType';

        if (!class_exists($material))
            $this->createNotFoundException('Inexistent Material Type');

        $type = new OrderType($this->getInstance(), new $material);
        $form = $this->createForm($type, new Order());
        return $this->render('CelsiusCelsius3Bundle:AdminOrder:_materialData.html.twig', array('form' => $form->createView()));
    }

    protected function getMaterialType($materialData = null)
    {
        if (is_null($materialData))
        {
            $materialTypeName = 'Celsius\\Celsius3Bundle\\Form\\Type\\' .
                    ucfirst($this->getRequest()->request->get('celsius_celsius3bundle_ordertype[materialDataType]', null, true)) .
                    'TypeType';
        } else
        {
            $class = explode('\\', $materialData);
            $materialTypeName = 'Celsius\\Celsius3Bundle\\Form\\Type\\' .
                    end($class) . 'Type';
        }

        return new $materialTypeName;
    }

    public function baseEvent($id, $event, $route)
    {
        $dm = $this->getDocumentManager();

        $order = $this->findQuery('Order', $id);

        $this->get('lifecycle_helper')->createEvent($event, $order);

        return $this->redirect($this->generateUrl($route . '_show', array('id' => $order->getId())));
    }

}

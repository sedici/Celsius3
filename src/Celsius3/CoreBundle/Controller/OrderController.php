<?php

namespace Celsius3\CoreBundle\Controller;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;

abstract class OrderController extends BaseInstanceDependentController
{

    protected function listQuery($name)
    {
        return parent::listQuery($name)->field('currentState')->prime(true);
    }

    protected function change()
    {
        $material = 'Celsius3\\CoreBundle\\Form\\Type\\'
                . ucfirst($this->getRequest()->get('material')) . 'TypeType';

        if (!class_exists($material))
            $this->createNotFoundException('Inexistent Material Type');

        $type = new OrderType($this->getInstance(), new $material);
        $form = $this->createForm($type, new Order());

        return $this
                ->render(
                        'Celsius3CoreBundle:Order:_materialData.html.twig',
                        array('form' => $form->createView()));
    }

    protected function getMaterialType($materialData = null)
    {
        if (is_null($materialData)) {
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\'
                    . ucfirst(
                            $this->getRequest()->request
                                    ->get(
                                            'celsius3_corebundle_ordertype[materialDataType]',
                                            null, true)) . 'TypeType';
        } else {
            $class = explode('\\', $materialData);
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\'
                    . end($class) . 'Type';
        }

        return new $materialTypeName;
    }

}

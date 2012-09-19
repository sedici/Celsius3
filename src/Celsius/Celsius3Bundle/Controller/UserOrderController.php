<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Form\Type\OrderType;
use Celsius\Celsius3Bundle\Filter\Type\OrderFilterType;

/**
 * Order controller.
 *
 * @Route("/user/order")
 */
class UserOrderController extends OrderController
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getInstance()->getId())
                        ->field('owner.id')->equals($this->getUser()->getId());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getInstance()->getId())
                        ->field('owner.id')->equals($this->getUser()->getId())
                        ->field('id')->equals($id)
                        ->getQuery()
                        ->getSingleResult();
    }

    /**
     * Lists all Order documents.
     *
     * @Route("/", name="user_order")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Order', $this->createForm(new OrderFilterType($this->getInstance(), $this->getUser())));
    }

    /**
     * Displays a form to create a new Order document.
     *
     * @Route("/new", name="user_order_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Order', new Order(), new OrderType($this->getInstance(), null, $this->getUser()));
    }

    /**
     * Creates a new Order document.
     *
     * @Route("/create", name="user_order_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:UserOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Order', new Order(), new OrderType($this->getInstance(), $this->getMaterialType(), $this->getUser()), 'user_order');
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="user_order_change")
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        return $this->change();
    }

}
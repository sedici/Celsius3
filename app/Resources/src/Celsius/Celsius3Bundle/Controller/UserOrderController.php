<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\Librarian;
use Celsius\Celsius3Bundle\Form\Type\OrderType;
use Celsius\Celsius3Bundle\Form\Type\LibrarianOrderType;
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
        $qb = $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getInstance()->getId());

        $qb = $qb->addOr($qb->expr()->field('owner.id')->equals($this->getUser()->getId()));
        $qb = $qb->addOr($qb->expr()->field('librarian.id')->equals($this->getUser()->getId()));

        return $qb;
    }

    protected function findQuery($name, $id)
    {
        $qb = $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getInstance()->getId());

        $qb = $qb->addOr($qb->expr()->field('owner.id')->equals($this->getUser()->getId()));
        $qb = $qb->addOr($qb->expr()->field('librarian.id')->equals($this->getUser()->getId()));

        $qb = $qb->field('id')->equals($id)
                ->getQuery()
                ->getSingleResult();

        return $qb;
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
     * Finds and displays a Order document.
     *
     * @Route("/{id}/show", name="user_order_show")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('Order', $id);
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
        if ($this->getUser() instanceof Librarian)
        {
            $type = new LibrarianOrderType($this->getInstance(), null, $this->getUser());
        } else
        {
            $type = new OrderType($this->getInstance(), null, $this->getUser());
        }

        return $this->baseNew('Order', new Order(), $type);
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
        if ($this->getUser() instanceof Librarian)
        {
            $type = new LibrarianOrderType($this->getInstance(), $this->getMaterialType(), $this->getUser());
        } else
        {
            $type = new OrderType($this->getInstance(), $this->getMaterialType(), $this->getUser());
        }

        return $this->baseCreate('Order', new Order(), $type, 'user_order');
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
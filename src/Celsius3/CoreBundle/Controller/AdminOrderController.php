<?php

namespace Celsius3\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Form\Type\AdminOrderType as OrderType;
use Celsius3\CoreBundle\Filter\Type\OrderFilterType;

/**
 * Order controller.
 *
 * @Route("/admin/order")
 */
class AdminOrderController extends OrderController
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:' . $name)
                ->findForInstance($this->getInstance());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:' . $name)
                ->findOneForInstance($id, $this->getInstance())->getQuery()
                ->getSingleResult();
    }

    /**
     * Lists all Order documents.
     *
     * @Route("/", name="admin_order")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this
                ->baseIndex('Order',
                        $this
                                ->createForm(
                                        new OrderFilterType(
                                                $this->getInstance())));
    }

    /**
     * Finds and displays a Order document.
     *
     * @Route("/{id}/show", name="admin_order_show")
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
     * @Route("/new", name="admin_order_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this
                ->baseNew('Order', new Order(),
                        new OrderType($this->getInstance(), null, null,
                                $this->getUser()));
    }

    /**
     * Creates a new Order document.
     *
     * @Route("/create", name="admin_order_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this
                ->baseCreate('Order', new Order(),
                        new OrderType($this->getInstance(),
                                $this->getMaterialType(), null,
                                $this->getUser()), 'admin_order');
    }

    /**
     * Displays a form to edit an existing Order document.
     *
     * @Route("/{id}/edit", name="admin_order_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        $document = $this->findQuery('Order', $id);

        if (!$document) {
            throw $this
                    ->createNotFoundException('Unable to find ' . 'Order' . '.');
        }

        $materialClass = get_class($document->getMaterialData());

        $editForm = $this
                ->createForm(
                        new OrderType($this->getInstance(),
                                $this->getMaterialType($materialClass),
                                $document->getOwner(), $this->getUser()),
                        $document);
        $deleteForm = $this->createDeleteForm($id);

        return array('document' => $document,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Edits an existing Order document.
     *
     * @Route("/{id}/update", name="admin_order_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminOrder:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        $document = $this->findQuery('Order', $id);

        if (!$document) {
            throw $this
                    ->createNotFoundException('Unable to find ' . 'Order' . '.');
        }

        $document->setMaterialData(null);

        $editForm = $this
                ->createForm(
                        new OrderType($this->getInstance(),
                                $this->getMaterialType(), null,
                                $this->getUser()), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this
                    ->redirect(
                            $this
                                    ->generateUrl('admin_order_edit',
                                            array('id' => $id)));
        }

        return array('document' => $document,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Deletes a Order document.
     *
     * @Route("/{id}/delete", name="admin_order_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Order', $id, 'admin_order');
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="admin_order_change")
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        return $this->change();
    }

    /**
     * Creates an Event for an Order
     *
     * @Route("/{id}/event/{event}", name="admin_order_event")
     * @Method("post")
     *
     * @param string $id The document ID
     * @param string $event The event name
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function eventAction($id, $event)
    {
        $order = $this->findQuery('Order', $id);

        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        if ($this->get('celsius3_core.lifecycle_helper')->createEvent($event, $order)) {
            $this->get('session')->getFlashBag()
                    ->add('success', 'The state has been successfully changed.');
        } else {
            $this->get('session')->getFlashBag()
                    ->add('success',
                            'There was an error processing your request.');
        }

        return $this
                ->redirect(
                        $this
                                ->generateUrl('admin_order_show',
                                        array('id' => $order->getId())));
    }

    /**
     * Undoes the last state change
     *
     * @Route("/{id}/undo", name="admin_order_undo")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function undoStateAction($id)
    {
        $order = $this->findQuery('Order', $id);

        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        if ($this->get('celsius3_core.lifecycle_helper')->undoState($order)) {
            $this->get('session')->getFlashBag()
                    ->add('success', 'The state has been successfully changed.');
        } else {
            $this->get('session')->getFlashBag()
                    ->add('success',
                            'There was an error processing your request.');
        }

        return $this
                ->redirect(
                        $this
                                ->generateUrl('admin_order_show',
                                        array('id' => $order->getId())));
    }
}

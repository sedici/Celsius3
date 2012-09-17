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
 * @Route("/superadmin/order")
 */
class SuperadminOrderController extends OrderController
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder();
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    /**
     * Lists all Order documents.
     *
     * @Route("/", name="superadmin_order")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Order', $this->createForm(new OrderFilterType()));
    }

    /**
     * Displays a form to create a new Order document.
     *
     * @Route("/new", name="superadmin_order_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Order', new Order(), new OrderType());
    }

    /**
     * Creates a new Order document.
     *
     * @Route("/create", name="superadmin_order_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Order', new Order(), new OrderType(null, $this->getMaterialType()), 'superadmin_order');
    }

    /**
     * Displays a form to edit an existing Order document.
     *
     * @Route("/{id}/edit", name="superadmin_order_edit")
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

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find ' . 'Order' . '.');
        }

        $materialClass = get_class($document->getMaterialData());

        $editForm = $this->createForm(new OrderType($document->getInstance(), $this->getMaterialType($materialClass)), $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Order document.
     *
     * @Route("/{id}/update", name="superadmin_order_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminOrder:edit.html.twig")
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

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find ' . 'Order' . '.');
        }

        $document->setMaterialData(null);

        $editForm = $this->createForm(new OrderType($document->getInstance(), $this->getMaterialType()), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('superadmin_order_edit', array('id' => $id)));
        }

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Order document.
     *
     * @Route("/{id}/delete", name="superadmin_order_delete")
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
        return $this->baseDelete('Order', $id, 'superadmin_order');
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="superadmin_order_change")
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        return $this->change();
    }

}
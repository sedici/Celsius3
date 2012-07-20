<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Form\Type\OrderType;

/**
 * Order controller.
 *
 * @Route("/admin/order")
 */
class OrderController extends BaseInstanceDependentController
{

    /**
     * Lists all Order documents.
     *
     * @Route("/", name="order")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Order');
    }

    /**
     * Finds and displays a Order document.
     *
     * @Route("/{id}/show", name="order_show")
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
     * @Route("/new", name="order_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Order', new Order(), new OrderType($this->getInstance()));
    }

    /**
     * Creates a new Order document.
     *
     * @Route("/create", name="order_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Order:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Order', new Order(), new OrderType($this->getInstance(), $this->getMaterialType()), 'order');
    }

    /**
     * Displays a form to edit an existing Order document.
     *
     * @Route("/{id}/edit", name="order_edit")
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

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType($materialClass)), $document);
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
     * @Route("/{id}/update", name="order_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Order:edit.html.twig")
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

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType()), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('order_edit', array('id' => $id)));
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
     * @Route("/{id}/delete", name="order_delete")
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
        return $this->baseDelete('Order', $id, 'order');
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="order_change")
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        $material = 'Celsius\\Celsius3Bundle\\Form\\Type\\' . ucfirst($this->getRequest()->get('material')) . 'TypeType';

        if (!class_exists($material))
            $this->createNotFoundException('Inexistent Material Type');

        $type = new OrderType($this->getInstance(), new $material);
        $form = $this->createForm($type, new Order());
        return $this->render('CelsiusCelsius3Bundle:Order:_materialData.html.twig', array('form' => $form->createView()));
    }

    private function getMaterialType($materialData = null)
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

}

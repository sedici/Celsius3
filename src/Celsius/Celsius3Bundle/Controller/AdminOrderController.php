<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\Receive;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Form\Type\AdminOrderType as OrderType;
use Celsius\Celsius3Bundle\Form\Type\OrderRequestType;
use Celsius\Celsius3Bundle\Form\Type\OrderReceiveType;
use Celsius\Celsius3Bundle\Filter\Type\OrderFilterType;
use Celsius\Celsius3Bundle\Manager\EventManager;
use Celsius\Celsius3Bundle\Manager\StateManager;

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
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->findForInstance($this->getInstance());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->findOneForInstance($id, $this->getInstance())
                        ->getQuery()
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
        return $this->baseIndex('Order', $this->createForm(new OrderFilterType($this->getInstance())));
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
        return $this->baseNew('Order', new Order(), new OrderType($this->getInstance(), null, null, $this->getUser()));
    }

    /**
     * Creates a new Order document.
     *
     * @Route("/create", name="admin_order_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Order', new Order(), new OrderType($this->getInstance(), $this->getMaterialType(), null, $this->getUser()), 'admin_order');
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

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find ' . 'Order' . '.');
        }

        $materialClass = get_class($document->getMaterialData());

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType($materialClass), null, $this->getUser()), $document);
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
     * @Route("/{id}/update", name="admin_order_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminOrder:edit.html.twig")
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

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType(), null, $this->getUser()), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('admin_order_edit', array('id' => $id)));
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
     * Shows the state list fragment for an Order.
     *
     * @Template()
     * 
     * @param Order $document The Order document
     *
     * @return array
     */
    public function stateListAction(Order $document)
    {
        return array(
            'document' => $document,
            'positive_states' => $this->get('state_manager')->getPositiveStates(),
        );
    }

    /**
     * Shows the state header for an Order state.
     *
     * @Template()
     * 
     * @param string $state The State name
     * @param Order $document The Order document
     *
     * @return array
     */
    public function stateHeaderAction($state, Order $document)
    {
        $instance = $this->getInstance();
        
        return array(
            'state' => $state,
            'order' => $document,
            'hasPrevious' => $document->hasState($this->get('state_manager')->getPreviousPositiveState($state), $instance),
            'instance' => $instance,
        );
    }

    /**
     * Shows the state body for an Order state.
     *
     * @Template()
     * 
     * @param string $state The State name
     * @param Order $document The Order document
     *
     * @return array
     */
    public function stateBodyAction($state, Order $document)
    {
        $instance = $this->getInstance();
        
        return $this->render('CelsiusCelsius3Bundle:AdminOrder:_info_' . $state . '.html.twig', array(
                    'state' => $state,
                    'order' => $document,
                    'events' => $this->get('state_manager')->getEventsToState($state),
                    'hasPrevious' => $document->hasState($this->get('state_manager')->getPreviousPositiveState($state), $instance),
                    'isCurrent' => $document->getCurrentState($instance)->getType()->getName() == $state,
                    'request_form' => $this->get('form.factory')->create(new OrderRequestType($this->getDocumentManager(), $this->get('event_manager')->getFullClassNameForEvent(EventManager::EVENT__SINGLE_INSTANCE_REQUEST)), new SingleInstanceRequest())->createView(),
                    'isDelivered' => $document->getState(StateManager::STATE__DELIVERED, $instance),
                    'instance' => $instance,
                ));
    }
    
    /**
     * Shows the request event info for an Order state.
     *
     * @Template()
     * 
     * @param Event $event The Request event
     * @param Order $document The Order document
     *
     * @return array
     */
    public function eventRequestAction(Event $event, Order $document)
    {
        return $this->render('CelsiusCelsius3Bundle:AdminOrder:_event_request.html.twig', array(
                    'event' => $event,
                    'isMultiInstance' => $event instanceof MultiInstanceRequest,
                    'order' => $document,
                    'receive_form' => $this->get('form.factory')->create(new OrderReceiveType(), new Receive())->createView(),
                    'isReceived' => $this->getDocumentManager()
                            ->getRepository('CelsiusCelsius3Bundle:Receive')
                            ->findBy(array('requestEvent.id' => $event->getId()))
                            ->count() > 0,
                    'isDelivered' => $document->getState(StateManager::STATE__DELIVERED, $this->getInstance()),
                ));
    }
    
    /**
     * Shows the receive event info for an Order state.
     *
     * @Template()
     * 
     * @param Event $event The Receive event
     * @param Order $document The Order document
     *
     * @return array
     */
    public function eventReceiveAction(Event $event, Order $document)
    {
        return $this->render('CelsiusCelsius3Bundle:AdminOrder:_event_receive.html.twig', array(
                    'event' => $event,
                    'isMultiInstance' => $event instanceof Receive && $event->getRequestEvent() instanceof MultiInstanceRequest,
                    'order' => $document,
                    'isDelivered' => $document->getState(StateManager::STATE__DELIVERED, $this->get('instance_helper')->getSessionInstance()),
                    'isReclaimed' => $event instanceof Receive && $event->getReclaimed(),
                ));
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

        if (!$order)
        {
            throw $this->createNotFoundException('Unable to find ' . 'Order' . '.');
        }

        try
        {
            $extraData = $this->get('event_manager')->prepareExtraData($event, $order);
            $event = $this->get('event_manager')->getRealEventName($event, $extraData);
            $this->get('lifecycle_helper')->createEvent($event, $order, $extraData);
            $this->get('session')->getFlashBag()->add('success', 'The state has been successfully changed.');
        } catch (\Exception $e)
        {
            
        }

        return $this->redirect($this->generateUrl('admin_order_show', array('id' => $order->getId())));
    }

    /**
     * Reclaims an Order
     *
     * @Route("/{id}/reclaim/{receive}", name="admin_order_reclaim")
     * @Method("post")
     * 
     * @param string $id The document ID
     * @param string $event The event name
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function reclaimAction($id, $receive)
    {
        $order = $this->findQuery('Order', $id);

        if (!$order)
        {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        $event = $this->findQuery('Receive', $receive);

        if (!$event)
        {
            throw $this->createNotFoundException('Unable to find Event.');
        }

        $this->get('lifecycle_helper')->reclaim($event, $order);

        return $this->redirect($this->generateUrl('admin_order_show', array('id' => $order->getId())));
    }

}
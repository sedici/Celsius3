<?php

namespace Celsius3\CoreBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class RegistrationController extends BaseRegistrationController
{

    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container
                ->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $dispatcher
                ->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE,
                        new UserEvent($user, $request));

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher
                        ->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);
                $this->container->get('celsius3_core.custom_field_helper')
                        ->processCustomFields($this->getInstance(), $form,
                                $user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')
                            ->generate('fos_user_registration_confirmed',
                                    array(
                                            'url' => $this->container
                                                    ->get('request')
                                                    ->get('url')));
                    $response = new RedirectResponse($url);
                }

                $dispatcher
                        ->dispatch(FOSUserEvents::REGISTRATION_COMPLETED,
                                new FilterUserResponseEvent($user, $request,
                                        $response));

                return $response;
            }
        }

        return $this->container->get('templating')
                ->renderResponse(
                        'FOSUserBundle:Registration:register.html.'
                                . $this->getEngine(),
                        array('form' => $form->createView(),));
    }

    public function waitConfirmationAction()
    {
        $email = $this->container->get('session')
                ->get('fos_user_send_confirmation_email/email');
        $this->container->get('session')
                ->remove('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')
                ->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(
                    sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->container->get('templating')
                ->renderResponse(
                        'FOSUserBundle:Registration:waitConfirmation.html.'
                                . $this->getEngine(),
                        array('user' => $user,));
    }

    /**
     * Returns the DocumentManager
     *
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->container->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * Returns the instance related to the users instance.
     *
     * @return Instance
     */
    protected function getInstance()
    {
        return $this->container->get('instance_helper')->getUrlInstance();
    }

    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     *
     *     throw $this->createNotFoundException('Page not found!');
     *
     * @param string    $message  A message
     * @param Exception $previous The previous exception
     *
     * @return NotFoundHttpException
     */
    public function createNotFoundException($message = 'Not Found',
            \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

}

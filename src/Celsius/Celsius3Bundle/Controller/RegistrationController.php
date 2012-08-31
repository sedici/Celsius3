<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends BaseRegistrationController
{

    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process)
        {
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled)
            {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else
            {
                $authUser = true;
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route, array('url' => $this->container->get('request')->get('url')));
            $response = new RedirectResponse($url);

            if ($authUser)
            {
                $this->authenticateUser($user, $response);
                $this->container->get('session')->set('instance_id', $user->getInstance()->getId());
                $this->container->get('session')->set('instance_url', $user->getInstance()->getUrl());
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.' . $this->getEngine(), array(
                    'form' => $form->createView(),
                ));
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
        $instance = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->find($this->get('session')->get('instance_id'));

        if (!$instance)
        {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        return $instance;
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
    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

}

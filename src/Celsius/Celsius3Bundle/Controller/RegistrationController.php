<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;

class RegistrationController extends BaseRegistrationController
{

    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationType = $this->container->get('configuration_helper')->getCastedValue($this->getInstance()->get('confirmation_type'));
        $confirmationEnabled = $confirmationType == 'email';

        $process = $formHandler->process($confirmationEnabled);
        if ($process)
        {
            $user = $form->getData();

            $this->container->get('custom_field_helper')->processCustomFields($this->getInstance(), $form, $user);

            $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
            if ($confirmationEnabled)
            {
                $route = 'fos_user_registration_check_email';
            } else
            {
                $route = 'fos_user_registration_wait_confirmation';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route, array('url' => $this->container->get('request')->get('url')));
            $response = new RedirectResponse($url);

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.' . $this->getEngine(), array(
                    'form' => $form->createView(),
                ));
    }

    public function waitConfirmationAction()
    {
        $email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
        $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user)
        {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:waitConfirmation.html.' . $this->getEngine(), array(
                    'user' => $user,
                ));
    }

    public function citiesAction()
    {
        $request = $this->container->get('request');

        $cities = $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:City')
                ->createQueryBuilder()
                ->field('country.id')->equals($request->query->get('country_id'))
                ->getQuery()
                ->execute();

        $response = '';

        foreach ($cities as $city)
        {
            $response .= '<option value="' . $city->getId() . '">' . $city->getName() . '</option>';
        }

        return new Response($response);
    }

    public function institutionsAction()
    {
        $request = $this->container->get('request');

        $institutions = $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:Institution')
                ->createQueryBuilder()
                ->field('city.id')->equals($request->query->get('city_id'))
                ->getQuery()
                ->execute();

        $response = '';

        foreach ($institutions as $key => $institution)
        {
            $response .= '<input type="radio" value="' . $institution->getId() . '" required="required" name="fos_user_registration_form[institution]" id="fos_user_registration_form_institution_' . $key . '">';
            $response .= '<label class="required" for="fos_user_registration_form_institution_' . $key . '">' . $institution->getName() . '</label>';
        }

        return new Response($response);
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
                ->findOneBy(array('url' => $this->container->get('request')->attributes->get('url')));

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

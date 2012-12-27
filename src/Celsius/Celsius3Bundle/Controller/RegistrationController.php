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

        if (!$request->query->has('country_id'))
        {
            throw $this->createNotFoundException();
        }

        $cities = $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:City')
                ->createQueryBuilder()
                ->field('country.id')->equals($request->query->get('country_id'))
                ->getQuery()
                ->execute();

        $response = array();

        foreach ($cities as $city)
        {
            $response[] = array('value' => $city->getId(), 'name' => $city->getName());
        }

        return new Response(json_encode($response));
    }

    public function institutionsAction()
    {
        $request = $this->container->get('request');

        if (!$request->query->has('country_id') && !$request->query->has('city_id') && !$request->query->has('institution_id'))
        {
            throw $this->createNotFoundException();
        }

        $qb = $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:Institution')
                ->createQueryBuilder();

        if ($request->query->has('city_id'))
        {
            $qb = $qb->field('city.id')->equals($request->query->get('city_id'))
                            ->field('parent.id')->equals(null);
        } else if ($request->query->has('country_id'))
        {
            $qb = $qb->field('country.id')->equals($request->query->get('country_id'))
                            ->field('city.id')->equals(null)
                            ->field('parent.id')->equals(null);
        } else
        {
            $qb = $qb->field('parent.id')->equals($request->query->get('institution_id'));
        }

        $institutions = $qb
                ->getQuery()
                ->execute();

        $response = array();

        foreach ($institutions as $institution)
        {
            $response [] = array(
                'value' => $institution->getId(),
                'name' => $institution->getName(),
                'hasChildren' => $institution->getInstitutions()->count() > 0,
            );
        }

        return new Response(json_encode($response));
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

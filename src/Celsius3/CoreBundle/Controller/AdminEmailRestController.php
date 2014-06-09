<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Email;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/email")
 */
class AdminEmailRestController extends BaseInstanceDependentRestController
{

    /**
     * @Post("", name="admin_rest_email", options={"expose"=true})
     */
    public function sendEmailAction(Request $request)
    {
        if (!$request->request->has('email')) {
            throw new NotFoundHttpException('Error sending email');
        }
        $email = $request->request->get('email');

        $emailConstraint = new Email();
        $emailConstraint->message = 'Invalid email';

        $errors = $this->get('validator')->validateValue(
                $email, $emailConstraint
        );
        
        if (count($errors) !== 0) {
            throw new NotFoundHttpException('Error sending email');
        }

        if (!$request->request->has('subject')) {
            throw new NotFoundHttpException('Error sending email');
        }
        $subject = $request->request->get('subject');

        if (!$request->request->has('text')) {
            throw new NotFoundHttpException('Error sending email');
        }
        $text = $request->request->get('text');

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->getInstance()->get('email_reply_address')->getValue())
                ->setTo($email)
                ->setBody($text);
        $this->get('mailer')->send($message);

        $view = $this->view(true, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}

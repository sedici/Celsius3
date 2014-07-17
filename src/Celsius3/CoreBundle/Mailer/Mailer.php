<?php

namespace Celsius3\CoreBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Email;

class Mailer
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function saveEmail($address, $subject, $text, Instance $instance)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');

        $email = new Email();
        $email->setAddress($address);
        $email->setSubject($subject);
        $email->setText($text);
        $email->setSender($this->container->get('security.context')->getToken()->getUser());
        $email->setInstance($instance);

        $dm->persist($email);
        $dm->flush();
    }

    public function sendEmail($address, $subject, $text, Instance $instance)
    {
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($instance->get('email_reply_address')->getValue())
                ->setTo($address)
                ->setBody($text);
        $this->container->get('mailer')->send($message);

        $this->saveEmail($address, $subject, $text, $instance);

        return true;
    }
}
<?php

namespace Celsius3\Controller\Html;

use Celsius3\Controller\Base\EmailController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class HtmlEmailPruebaController extends EmailController
{
    #[
        Route('/public/send_mail_prueba'),
        IsGranted('IS_AUTHENTICATED_FULLY')
    ]
    public function prueba(): Response
    {
        $sent = $this->sendEmail1(
            'jeresmendi@gmail.com',
            'Email de prueba',
            'Este es un mail de prueba'
        );

        if ($sent) return new Response('Email enviado');
        return new Response('Error al enviar el email');
    }
}
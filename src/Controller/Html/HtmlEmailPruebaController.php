<?php

namespace Celsius3\Controller\Html;

use Celsius3\Controller\Base\EmailController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HtmlEmailPruebaController extends EmailController
{
    #[Route('/public/send_mail_prueba')]
    public function prueba(): Response
    {
        $sent = $this->sendEmail1(
            'jeresmendi@gmail.com',
            'Email de prueba',
            'Hola como va, este es un mail de prueba'
        );

        if ($sent) return new Response('Email enviado');
        return new Response('Error al enviar el email');
    }
}
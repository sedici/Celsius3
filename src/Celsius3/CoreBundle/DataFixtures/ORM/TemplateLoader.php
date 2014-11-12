<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\DataFixtures\ORM;

use Faker\Factory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Manager\MailManager;
use Celsius3\CoreBundle\Entity\MailTemplate;
use Celsius3\NotificationBundle\Entity\NotificationTemplate;
use Celsius3\NotificationBundle\Manager\NotificationManager;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class TemplateLoader extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $notification_templates = array(
        NotificationManager::CAUSE__NEW_MESSAGE => array(
            'class' => '\\Celsius3\\NotificationBundle\\Entity\\NotificationTemplate',
            'text' => 'You have a new message from {{ user }}.',
        ),
        NotificationManager::CAUSE__NEW_USER => array(
            'class' => '\\Celsius3\\NotificationBundle\\Entity\\NotificationTemplate',
            'text' => 'There\'s a new registered user called {{ user }}.',
        ),
    );
    private $mail_templates = array(
        MailManager::MAIL__ORDER_PRINTED => array(
            'title' => 'Recepción de Pedido - HORARIO 13 a 19 Hs.',
            'text' => "Estimado Usuario {{ user }}:
                Tenemos el agrado de dirigirnos a usted con la finalidad de avisarle que su pedido con descripción: \"{{ order.materialData.title }}\" ha sido recibido.
                El mismo puede ser retirado en las oficinas del PrEBi en el horario de 13 a 19.00 hs.
                Sin más, lo saluda atentamente, Personal ISTEC-PreBi",
        ),
        MailManager::MAIL__ORDER_CANCEL => array(
            'title' => 'Cancelación de solicitud de bibliografía',
            'text' => "Estimado Usuario {{ user }}:
                Me dirijo a ud. para informarle que lamentablemente no hemos ubicado su solicitud de: {{ order.materialData.title }}.
                Lo sentimos mucho. Hasta pronto. Personal ISTEC-PreBi",
        ),
        MailManager::MAIL__ORDER_PRINTED_RECONFIRM => array(
            'title' => 'Reconfirmacion de los pedidos encontrados',
            'text' => "Estimado/a {{ user }}:
                Nos dirigimos a Usted dado que se registra en nuestra Base de Datos que posee {{ user.order.count }} pedido(s) solicitado(s) sin retirar de nuestras oficinas.
                Le rogamos efectuar tal diligencia los días hábiles en el horario de 13 a 18 hs. Sin embargo, si usted lo desea, le ofrecemos acercar los pedidos personalmente. En caso de que así lo desee, informenos por medio de un mail.
                Sin más se despide Atte. Gustavo Personal del PREBI.",
        ),
        MailManager::MAIL__ORDER_PRINTED_RECONFIRM => array(
            'title' => 'Documento listo para descargar',
            'text' => "Estimado {{ user }}:
                Le informo a Ud. que su solicitud {{ order.materialData.title }} ha sido recibido.
                Usted puede encontrarlo en su SITIO DE USUARIOS en la página web del PrEBi {{ instance.website }} Recuerde que puede bajar el artículo sólo UNA vez.
                Si tiene inconvenientes por favor contáctenos a bibistec@ing.unlp.edu.ar.
                Atte. Personal del PrEBi",
        ),
        MailManager::MAIL__USER_WELCOME => array(
            'title' => 'Bienvenido al PrEBi!!!',
            'text' => "Estimado {{ user }}:
                Por la presente cumplo en informarle que ha sido dado de alta como Usuario del Proyecto de Enlace de Bibliotecas.
                A partir de este momento podrá realizar sus solicitudes de \"Búsqueda\" del material bibliográfico que Usted necesite, directamente a través de nuestra página web: {{ instance.website }} Ingrese al Sitio de Usuario cliqueando este item en la parte superior de la pantalla de su explorador, despues de hacerlo deberá completar la siguiente información: Su login es: {{ user.username }} y su clave es la que eligió al registrarse.
                A partir de este momento se encuentra en su \"sitio personal\" del PrEBi.
                Entre las ventajas de este sistema podemos mencionar que el usuario cuenta con la información en tiempo real de todo lo que ocurrre con sus solicitudes en curso y un registro histórico relacionado con todas sus solicitudes a lo largo del tiempo de utilización del servicio. Por medio de la Opción Pedido Nuevo, usted puede llenar el formulario para solicitar un Pedido Nuevo sin necesidad de escribirnos un email. Esta modalidad acelera el proceso de búsqueda.
                Para retirar los pedidos personalmente, nuestra direccion es Calle 49 y 115 (frente al departamento de Fisica) el edificio construído para que funcionara el Liceo y en el que finalmente funcionan aulas y secretarias de Informatica e Ingenieria. Nuestro lugar de trabajo es en el primer piso pasando la Secretaria de Fisicomatematica de la Facultad de Ingenieria.
                Atentamente, Prof. Ing. Marisa De Giusti Directora PrEBi-SeDiCI",
        ),
        MailManager::MAIL__USER_LOST => array(
            'title' => 'Alta de Usuario no UNLP',
            'text' => "Estimado {{ user }}:
                Hemos detectado que ud. no pertenece a la UNLP. Por favor, comuníquese con la directora del servicio al siguente email: Ing. Marisa De Giusti bibiste@ing.unlp.edu.ar.
                Disculpe las molestias.
                Muchas Gracias por su interés en nuestros servicios.",
        ),
        MailManager::MAIL__USER_WELCOME_PROVISION => array(
            'title' => 'Bienvenida Nuevo Usuario - Provisión (Exterior)',
            'text' => "Estimado {{ user }}:
                Por la presente cumplo en informarle que ha sido dado de alta como Usuario del Proyecto de Enlace de Bibliotecas.
                A partir de este momento podrá realizar sus solicitudes de \"Provisión\" del material bibliográfico que Usted necesite, directamente a través de nuestra página web: {{ instance.website }} Ingrese al Sitio de Usuario cliqueando este item en la parte superior de la pantalla de su explorador, despues de hacerlo deberá completar la siguiente información: Su login es: {{ user.username }} Su clave es la que ingresó al registrarse.
                A partir de este momento se encuentra en su \"sitio personal\" del PrEBi. Para cambiar su clave seleccione Cambiar Clave de acceso y modifique los datos, según su comodidad.
                Entre las ventajas de este sistema podemos mencionar que el usuario cuenta con la información en tiempo real de todo lo que ocurrre con sus solicitudes en curso y un registro histórico relacionado con todas sus solicitudes a lo largo del tiempo de utilización del servicio. Por medio de la Opción Pedido Nuevo, usted puede llenar el formulario para solicitar un Pedido A la hora de solicitar un nuevo pedido, debe revisar que la publicación que desea se encuentra disponible en la UNLP; caso contrario, su pedido no podrá ser resuelto. Para chequear esto, ingrese al Catálogo Colectivo ROBLE (www.roble.unlp.edu.ar) e ingrese el nombre del material que desea. En caso de que la misma se encuentre, haga click sobre el nombre del material: de este modo, se abrirá un cuadro informativo. Copie la \"Ubicación Topográfica\" que arroje este cuadro en el campo \"Biblioteca\" del nuevo pedido. El hecho de que el material no arroje resultados asegura que será imposible conseguirlo, por lo que evite cargar pedidos cuyo material no aparezca en el catalogo colectivo.
                Atentamente, Prof. Ing. Marisa De Giusti Directora PrEBi-SeDiCI",
        ),
    );
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('en');
        $generator->seed(1113);

        $directory = $this->getReference('directory');

        foreach ($this->mail_templates as $key => $value) {
            $template = new MailTemplate();
            $template->setCode($key);
            $template->setEnabled(true);
            $template->setText($value['text']);
            $template->setTitle($value['title']);
            $template->setInstance($directory);
            $manager->persist($template);
            unset($template);
        }

        foreach ($this->notification_templates as $key => $value) {
            $template = new NotificationTemplate();
            $template->setCode($key);
            $template->setText($value['text']);
            $manager->persist($template);
            unset($template);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}

<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\Controller\Base;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\EmailTemplate;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Order;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\Filter\EmailTemplateFilterType;
use Error;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class EmailTemplateController extends EntityController
{

    public function initialize(): void
    {
        $this->setEntity(EmailTemplate::class);

        parent::initialize();

        $this->setInstanceDependent(true);
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc',
        ]);
        $this->setType(EmailTemplateFilterType::class);
    }


    public function renderTemplate(
        $code,
        Instance $instance,
        BaseUser $user,
        Order $order = null,
        SerializerInterface $serializer
    ): ?string {
        try {
            $template = $this->htmlRenderer->createTemplate(
                $this->getTemplate(
                    $code, $this->instance
                )->getText()
            );
            $vars = compact('instance', 'user', 'order');
            return $template->render(
                $this->serializeData($vars, $serializer)
            );
        } catch (Error $error) {
            throw Exception::create(Exception::RENDER_TEMPLATE, 'exception.template.mail_template');
        }
    }


    public function getTemplate($code, Instance $instance)
    {
        $template = $this->repository
            ->findForInstanceAndGlobal($instance, $this->directory, $code)
            ->getQuery()->getResult();

        $template = $template[0] ?? null;

        if (!$template) $this->error('entity_not_found');

        return $template;
    }


    protected function serializeData($vars, SerializerInterface $serializer): array
    {
        return array_map(
            fn ($value) => $value !== null
                ? $serializer->serialize($value, 'json', [
                    AbstractNormalizer::GROUPS => ['email_template'],
                ])
                : null,
            $vars
        );
    }
}
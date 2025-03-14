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
use Celsius3\Entity\EmailTemplate;
use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\Filter\EmailTemplateFilterType;
use Error;


class EmailTemplateController extends EntityController
{

    public const MAIL__ORDER_PRINTED = 'order_printed';
    public const MAIL__ORDER_DOWNLOAD = 'order_download';
    public const MAIL__ORDER_CANCEL = 'order_cancel';
    public const MAIL__ORDER_PRINTED_RECONFIRM = 'order_printed_reconfirm';
    public const MAIL__USER_WELCOME = 'user_welcome';
    public const MAIL__USER_WELCOME_PROVISION = 'user_welcome_provision';
    public const MAIL__USER_LOST = 'user_lost';
    public const MAIL__NO_HIVE = 'no_hive';
    public const MAIL__RESETTING = 'resetting';
    public const MAIL__USER_CONFIRMATION = 'user_confirmation';
    public const MAIL__CUSTOM = 'custom';


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
        string $code,
        array $params = []
    ): ?string {
        try {
            $template = $this->htmlRenderer->createTemplate(
                $this->getTemplate(
                    $code, $this->instance
                )->getText()
            );

            return $template->render(
                $params
            );
        } catch (\Exception $e) {
            $this->error(Exception::RENDER_TEMPLATE, msg: $e->getMessage());
        }
    }


    public function getTemplate($code, Instance $instance)
    {
        $template = $this->repository
            ->findForInstanceAndGlobal($instance, $this->directory, $code)
            ->getQuery()->getResult();

        $template = $template[0] ?? null;

        if (!$template) $this->error(Exception::ENTITY_NOT_FOUND);

        return $template;
    }
}
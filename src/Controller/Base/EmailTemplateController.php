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

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class EmailTemplateController extends EntityController
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


    public function __construct(
        protected SerializerInterface $serializer,
        ValidatorInterface $validator,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper,
        FormFactoryInterface $formFactory,
        SessionInterface $session,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        Security $security,
        HtmlRenderer $htmlRenderer,
        RestRenderer $restRenderer
    ) {
        parent::__construct(
            $validator,
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper,
            $formFactory,
            $session,
            $router,
            $tokenStorage,
            $security,
            $htmlRenderer,
            $restRenderer
        );
    }


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
        ?Order $order = null
    ): ?string {
        try {
            $template = $this->htmlRenderer->createTemplate(
                $this->getTemplate(
                    $code, $this->instance
                )->getText()
            );
            $vars = compact('instance', 'user', 'order');
            return $template->render(
                $this->serializeData($vars)
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


    protected function serializeData($vars): array
    {
        return array_map(
            fn ($value) => $value !== null
                ? $this->serializer->serialize($value, 'json', [
                    AbstractNormalizer::GROUPS => ['email_template'],
                ])
                : null,
            $vars
        );
    }


    public function renderRawTemplate($text, $vars): ?string
    {
        try {
            $template = $this->htmlRenderer->createTemplate($text);

            return $template->render($this->serializeData($vars));
        } catch (Error $error) {
            throw Exception::create(Exception::RENDER_TEMPLATE, 'exception.template.mail_template');
        }
    }
}
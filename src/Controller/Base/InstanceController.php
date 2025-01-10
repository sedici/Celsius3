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

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Validator\Constraints\ContainsCSS;
use Celsius3\Entity\Instance;
use Celsius3\Entity\LegacyInstance;
use Celsius3\Helper\MailerHelper;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class InstanceController extends BaseInstanceDependentController
{

    private MailerHelper $mailerHelper;
    protected SessionInterface $session;


    public function __construct(
        SessionInterface $session,
        MailerHelper $mailerHelper,
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
        InstanceHelper $instanceHelper
    ) {
        parent::__construct(
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
            $instanceHelper
        );

        $this->mailerHelper = $mailerHelper;
        $this->session = $session;
    }


    final protected function getEntity(): string
    { return Instance::class; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function getDirectory(): Instance|null
    {
        return  $this->repository
            ->findOneBy(['url' => 'directory']);
    }


    protected function listQuery(): QueryBuilder
    {
        $qb = $this->repository
            ->createQueryBuilder('e')
            ->where('e.id != :id')
            ->setParameter('id', $this->directory->getId());

        return ($this->entityClassName == LegacyInstance::class)
            ? $qb->andWhere('e INSTANCE OF Celsius3:LegacyInstance')
            : $qb;
    }


    /**
     * Construye un array con la configuracion para cada widget .Permite manejar casos especiales como textareas o files.
     *
     * @author gonetil
     *
     * @param $configuration la configuracion del form widget
     * @param $configurationType el tipo widget de configuracion
     *
     * @return array con la estructura esperada por el metodo add de FormBuilder
     */
    private function buildConfigurationArray(
        $configuration,
        $configurationType,
        ConfigurationHelper $configurationHelper
    ): array {
        $configs = $configurationHelper->configurations;
        $config_array = [
            'constraints' => $configurationHelper->getConstraints($configuration),
            'data' => $configurationHelper->getCastedValue($configuration),
            /** @Ignore */
            'label' => $configuration->getName(),
            'required' => array_key_exists($configuration->getKey(), $configs) && isset($configs[$configuration->getKey()]['required']) ? $configs[$configuration->getKey()]['required'] : false,
            'attr' => [
                'value' => $configuration->getValue(),
                'class' => $configurationType === 'Symfony\Component\Form\Extension\Core\Type\TextareaType' && $configuration->getKey() !== ConfigurationHelper::CONF__INSTANCE_CSS ? 'summernote' : '',
                'required' => array_key_exists($configuration->getKey(), $configs) && isset($configs[$configuration->getKey()]['required']) ? $configs[$configuration->getKey()]['required'] : false,
            ],
        ];

        if ($configuration->getKey() === ConfigurationHelper::CONF__INSTANCE_CSS) {
            $config_array['constraints'] = new ContainsCSS();
        }

        if ($configuration->getKey() === ConfigurationHelper::CONF__SMTP_PROTOCOL) {
            $config_array['choices'] = ['SSL' => 'ssl', 'TLS' => 'tls'];
        }

        return $config_array;
    }


    private function getConfigurationForm(Instance $entity): FormInterface
    {
        $builder = $this->createFormBuilder();

        foreach ($entity->getConfigurations() as $configuration) {
            $configurationType = $this->configurationHelper
                ->guessConfigurationType($configuration);
            $builder->add(
                $configuration->getKey(),
                $configurationType,
                $this->buildConfigurationArray(
                    $configuration,
                    $configurationType,
                    $this->configurationHelper
                )
            );
        }

        return $builder->getForm();
    }


    protected function baseConfigure(string $id): array
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $configureForm = $this->getConfigurationForm($entity);

        return [
            'entity' => $entity,
            'configure_form' => $configureForm->createView(),
        ];
    }


    protected function baseConfigureUpdate(
        string $id, string $route
    ): array|RedirectResponse {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $configureForm = $this->getConfigurationForm($entity);
        $request = $this->requestStack->getCurrentRequest();
        $configureForm->handleRequest($request);

        if ($configureForm->isValid()) {
            $values = $configureForm->getData();
            $class = new \ReflectionClass($this);
            $basedir = dirname($class->getFileName()).'/../../../..';

            $uploadedFile = $configureForm['instance_logo']->getData();
            if ($uploadedFile !== null) {
                $randomName = md5(uniqid(mt_rand(), true));
                $uploadedFile->move($basedir.'/web/uploads/logos/', $randomName.'.'.$uploadedFile->guessClientExtension());
                $values['instance_logo'] = $randomName.'.'.$uploadedFile->guessClientExtension();
            }

            $em = $this->managerRegistry->getManager();
            foreach ($entity->getConfigurations() as $configuration) {
                if ($configuration->getKey() === 'instance_logo' && $configuration->getValue() !== '' && !is_null($configuration->getValue()) && !is_null($values[$configuration->getKey()])) {
                   if (file_exists($basedir.'/web/uploads/logos/'.$configuration->getValue())){
                        unlink($basedir.'/web/uploads/logos/'.$configuration->getValue());
                    }

                }
                if (
                    $values[$configuration->getKey()] === null
                    || $configuration->getKey() === ConfigurationHelper::CONF__INSTANCE_CSS
                ) {
                    $configuration->setValue($values[$configuration->getKey()]);
                    $em->persist($entity);
                }
            }

            $entity->get('smtp_status')
                ->setValue($this->mailerHelper->validateSmtpServerData($entity));

            $this->persistEntity($entity);

            $this->addEntityFlash('success', 'The %entity% was successfully configured.');

            return $this->redirect($this->generateUrl(
                (string) $route.'_configure',
                [ 'id' => $id ]
            ));
        }

        return [
            'entity' => $entity,
            'configure_form' => $configureForm->createView(),
        ];
    }
}

<?php

declare(strict_types=1);

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

namespace Celsius3\Helper;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Contact;
use Celsius3\CoreBundle\Entity\CustomContactValue;
use Celsius3\CoreBundle\Entity\CustomField;
use Celsius3\CoreBundle\Entity\CustomUserValue;
use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomFieldHelper
{
    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function processCustomUserFields(Instance $instance, FormInterface $form, BaseUser $user)
    {
        $fields = $this->entityManager->getRepository(CustomField::class)
            ->findBy([
                         'instance' => $instance->getId(),
                         'entity' => 'BaseUser'
                     ]);

        $data = $this->requestStack->getCurrentRequest()->get($form->getName());

        foreach ($fields as $field) {
            if (array_key_exists($field->getKey(), $data)) {
                $value = $this->entityManager
                    ->getRepository(CustomUserValue::class)
                    ->findOneBy([
                                    'field' => $field->getId(),
                                    'user' => $user->getId()
                                ]);

                if (!$value) {
                    $value = new CustomUserValue();
                    $value->setField($field);
                    $value->setUser($user);
                }
                $value->setValue($data[$field->getKey()]);
                $this->entityManager->persist($value);
            }
        }
        $this->entityManager->flush();
    }

    public function processCustomContactFields(Instance $instance, FormInterface $form, Contact $contact)
    {
        $fields = $this->entityManager->getRepository(CustomField::class)
            ->findBy([
                         'instance' => $instance->getId(),
                         'entity' => 'Contact'
                     ]);

        $data = $this->requestStack->getCurrentRequest()->get($form->getName());

        foreach ($fields as $field) {
            if (array_key_exists($field->getKey(), $data)) {
                $value = $this->entityManager
                    ->getRepository(CustomContactValue::class)
                    ->findOneBy([
                                    'field' => $field->getId(),
                                    'contact' => $contact->getId()
                                ]);

                if (!$value) {
                    $value = new CustomContactValue();
                    $value->setField($field);
                    $value->setContact($contact);
                }
                $value->setValue($data[$field->getKey()]);
                $this->entityManager->persist($value);
            }
        }
        $this->entityManager->flush();
    }
}

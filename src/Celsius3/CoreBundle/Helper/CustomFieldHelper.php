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

namespace Celsius3\CoreBundle\Helper;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\CustomUserValue;
use Celsius3\CoreBundle\Entity\Instance;

class CustomFieldHelper
{
    private $request_stack;
    private $em;

    public function __construct(RequestStack $request_stack, EntityManager $em)
    {
        $this->request_stack = $request_stack;
        $this->em = $em;
    }

    public function processCustomFields(Instance $instance, FormInterface $form, BaseUser $entity)
    {
        $fields = $this->em->getRepository('Celsius3CoreBundle:CustomUserField')
                ->findBy(array('instance' => $instance->getId()));

        $data = $this->request_stack->getCurrentRequest()->get($form->getName());

        foreach ($fields as $field) {
            if (array_key_exists($field->getKey(), $data)) {
                $value = $this->em
                        ->getRepository('Celsius3CoreBundle:CustomUserValue')
                        ->findOneBy(array(
                    'field' => $field->getId(),
                    'user' => $entity->getId(),
                ));

                if (!$value) {
                    $value = new CustomUserValue();
                    $value->setField($field);
                    $value->setUser($entity);
                }
                $value->setValue($data[$field->getKey()]);
                $this->em->persist($value);
            }
        }
        $this->em->flush();
    }
}

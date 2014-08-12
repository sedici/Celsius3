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

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Manager\MaterialTypeManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\BaseUser;
use JMS\TranslationBundle\Annotation\Ignore;

class OrderType extends AbstractType
{

    protected $instance;
    protected $material;
    protected $preferredMaterial;
    protected $user;
    protected $operator;
    protected $librarian;

    public function __construct(Instance $instance, MaterialTypeType $material = null, BaseUser $user = null, BaseUser $operator = null, $librarian = false)
    {
        $this->instance = $instance;
        $this->material = (is_null($material)) ? new JournalTypeType() : $material;

        $class = explode('\\', get_class($this->material));
        $this->preferredMaterial = lcfirst(str_replace('Type', '', end($class)));

        $this->user = $user;
        $this->operator = $operator;
        $this->librarian = $librarian;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('originalRequest', new RequestType($this->instance, $this->user, $this->operator, $this->librarian), array(
                    'label' => false,
                ))
                ->add('materialDataType', 'choice', array(
                    'choices' => array(
                        /** @Ignore */ MaterialTypeManager::TYPE__JOURNAL => ucfirst(MaterialTypeManager::TYPE__JOURNAL),
                        /** @Ignore */ MaterialTypeManager::TYPE__BOOK => ucfirst(MaterialTypeManager::TYPE__BOOK),
                        /** @Ignore */ MaterialTypeManager::TYPE__CONGRESS => ucfirst(MaterialTypeManager::TYPE__CONGRESS),
                        /** @Ignore */ MaterialTypeManager::TYPE__THESIS => ucfirst(MaterialTypeManager::TYPE__THESIS),
                        /** @Ignore */ MaterialTypeManager::TYPE__PATENT => ucfirst(MaterialTypeManager::TYPE__PATENT),
                    ),
                    'mapped' => false,
                    'data' => $this->preferredMaterial,
                    'label' => 'Material Type',
                ))
                ->add('materialData', $this->material)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Document\\Order',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'celsius3_corebundle_ordertype';
    }

}
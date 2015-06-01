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

namespace Celsius3\MessageBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\MessageBundle\DataTransformer\RecipientsDataTransformer;

class RecipientsCustomType extends AbstractType
{
    /**
     * @var RecipientsDataTransformer
     */
    private $recipientsTransformer;

    /**
     * @param RecipientsDataTransformer $transformer
     */
    public function __construct(RecipientsDataTransformer $transformer)
    {
        $this->recipientsTransformer = $transformer;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->recipientsTransformer);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected recipient does not exist',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'genemu_jqueryselect2_entity';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'celsius3_messagebundle_recipients_selector_custom';
    }
}

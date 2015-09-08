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

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\CoreBundle\Entity\Journal;

class JournalTypeType extends MaterialTypeType
{
    private $journal;

    public function __construct(Journal $journal = null, $other = '')
    {
        $this->journal = $journal;
        $this->other = $other;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('journal', 'celsius3_corebundle_journal_selector', array(
                    'attr' => array(
                        'required' => true,
                        'value' => (!is_null($this->journal)) ? $this->journal->getId() : '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
                ->add('journal_autocomplete', 'text', array(
                    'attr' => array(
                        'required' => false,
                        'class' => 'autocomplete',
                        'target' => 'Journal',
                        'value' => (!is_null($this->journal)) ?  $this->journal : $this->other
                    ),
                    'mapped' => false,
                    'label' => 'Journal',
                    'required' => false,
                ))
        ;
        
        parent::buildForm($builder, $options);

        $builder
                ->add('volume')
                ->add('number')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Entity\\JournalType',
        ));
    }

    public function getName()
    {
        return 'celsius3_corebundle_journaltype';
    }
}

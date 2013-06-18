<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LibrarianOrderType extends AdminOrderType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('target', 'choice',
                        array(
                                'choices' => array('me' => 'Me',
                                        'other' => 'Other',),
                                'mapped' => false,))
                ->add('librarian', 'user_selector',
                        array(
                                'attr' => array('readonly' => 'readonly',),));

        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'celsius3_corebundle_ordertype';
    }

}

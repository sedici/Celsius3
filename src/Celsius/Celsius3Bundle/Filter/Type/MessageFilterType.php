<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('sender', null, array(
                   'required' => false,
                  ));
        
          //  ->add('sender', 'entity', array(
          //          'class' => 'CelsiusCelsius3Bundle:BaseUser',
          //          'property' => 'name',
          //         ));
              //  ->add('Para', null, array('required' => false,))
              //  ->add('createdAt', 'date')
              //  ->add('createdAt', 'date')
              //  ->add('Bandeja', 'choice', array(
              //        'required' => false,
              //        'choices' => array(
              //          'Inbox' => 'Inbox',
              //          'Sent' => 'Sent',
              //       ),
               //     'expanded' => true,
               //     'multiple' => false,
              //  ))
           //      ->add('unreadForParticipants', 'choice', array(
          //             'required' => false,
           //            'choices' => array(
           //              'Leidos' => 'Leidos',
           //              'No leidos' => 'No leidos',
           //           ),
           //          'expanded' => true,
          //           'multiple' => false,
            //     ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
        
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_messagetype';
    }

}

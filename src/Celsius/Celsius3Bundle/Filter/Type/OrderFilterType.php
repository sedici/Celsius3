<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius\Celsius3Bundle\Manager\StateManager;

class OrderFilterType extends AbstractType
{

    private $instance;
    private $owner;

    public function __construct($instance = null, $owner = null)
    {
        $this->instance = $instance;
        $this->owner = $owner;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (is_null($this->owner))
        {
            $builder->add('owner', 'document', array(
                'required' => false,
                'class' => 'CelsiusCelsius3Bundle:BaseUser',
            ));
        }

        $builder
                ->add('code', null, array(
                    'required' => false,
                ))
                ->add('type', 'choice', array(
                    'required' => false,
                    'choices' => array(
                        '' => '',
                        0 => 'Provision',
                        1 => 'Search',
                    ),
                ))
                ->add('state', 'choice', array(
                    'required' => false,
                    'choices' => array(
                        StateManager::STATE__CREATED => ucfirst(StateManager::STATE__CREATED),
                        StateManager::STATE__SEARCHED => ucfirst(StateManager::STATE__SEARCHED),
                        StateManager::STATE__REQUESTED => ucfirst(StateManager::STATE__REQUESTED),
                        StateManager::STATE__APPROVAL_PENDING => str_replace('_', ' ', ucfirst(StateManager::STATE__APPROVAL_PENDING)),
                        StateManager::STATE__RECEIVED => ucfirst(StateManager::STATE__RECEIVED),
                        StateManager::STATE__DELIVERED => ucfirst(StateManager::STATE__DELIVERED),
                        StateManager::STATE__CANCELED => ucfirst(StateManager::STATE__CANCELED),
                        StateManager::STATE__ANNULED => ucfirst(StateManager::STATE__ANNULED),
                    ),
                    'multiple' => true,
                    'expanded' => true,
                ))
        ;

        if (is_null($this->instance))
        {
            $builder->add('instance', 'document', array(
                'required' => false,
                'class' => 'CelsiusCelsius3Bundle:Instance',
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return '';
    }

}

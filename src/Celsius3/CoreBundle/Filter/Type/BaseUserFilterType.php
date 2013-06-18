<?php

namespace Celsius3\CoreBundle\Filter\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Document\Instance;

class BaseUserFilterType extends AbstractType
{

    private $instance;

    public function __construct(Instance $instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('id', 'hidden',
                        array('required' => false,))
                ->add('name', null,
                        array('required' => false,))
                ->add('surname', null,
                        array('required' => false,))
                ->add('username', null,
                        array('required' => false,))
                ->add('email', null,
                        array('required' => false,))
                ->add('state', 'choice',
                        array('required' => false,
                                'choices' => array('enabled' => 'Enabled',
                                        'pending' => 'Pending',
                                        'rejected' => 'Rejected',),
                                'expanded' => true, 'multiple' => true,));

        if (is_null($this->instance)) {
            $builder
                    ->add('instance', 'document',
                            array('required' => false,
                                    'class' => 'Celsius3CoreBundle:Instance',));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('csrf_protection' => false,));
    }

    public function getName()
    {
        return '';
    }

}

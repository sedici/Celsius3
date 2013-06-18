<?php

namespace Celsius3\CoreBundle\Filter\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Document\Instance;

class InstitutionFilterType extends AbstractType
{

    private $instance;

    public function __construct(Instance $instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null,
                        array('required' => false,))
                ->add('abbreviation', null,
                        array('required' => false,))
                ->add('parent', 'document',
                        array('required' => false,
                                'class' => 'Celsius3CoreBundle:Institution',))
                ->add('city', 'document',
                        array('required' => false,
                                'class' => 'Celsius3CoreBundle:City',));
        if (is_null($this->instance)) {
            $builder
                    ->add('instance', 'document',
                            array('required' => false,
                                    'class' => 'Celsius3CoreBundle:Instance',
                                    'label' => 'Owning Instance',))
                    ->add('celsiusInstance', 'document',
                            array('required' => false,
                                    'class' => 'Celsius3CoreBundle:Instance',
                                    'label' => 'Celsius Instance',));
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
        return 'celsius3_corebundle_institutionfiltertype';
    }

}

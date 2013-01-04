<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius\Celsius3Bundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class OrderRequestType extends AbstractType
{

    private $dm;
    private $data_class;

    public function __construct($dm, $data_class)
    {
        $this->dm = $dm;
        $this->data_class = $data_class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('observations', 'textarea', array(
                    'required' => false,
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->dm, 'provider');
        $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->data_class,
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_orderrequesttype';
    }

}
<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Doctrine\ODM\MongoDB\DocumentManager;

class OrderRequestType extends AbstractType
{

    private $dm;
    private $data_class;

    public function __construct(DocumentManager $dm, $data_class)
    {
        $this->dm = $dm;
        $this->data_class = $data_class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new AddInstitutionFieldsSubscriber(
                $builder->getFormFactory(), $this->dm, 'provider');
        $builder->addEventSubscriber($subscriber);
        $builder
                ->add('observations', 'textarea', array('required' => false,));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('data_class' => $this->data_class,
                                'validation_groups' => array('request'),));
    }

    public function getName()
    {
        return 'celsius3_corebundle_orderrequesttype';
    }

}

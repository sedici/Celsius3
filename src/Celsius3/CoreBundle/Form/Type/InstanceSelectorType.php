<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Form\DataTransformer\InstanceToIdTransformer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstanceSelectorType extends AbstractType
{

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new InstanceToIdTransformer($this->dm);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('data_class' => null,
                                'invalid_message' => 'The selected Instance does not exist',));
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'celsius3_corebundle_instance_selector';
    }

}

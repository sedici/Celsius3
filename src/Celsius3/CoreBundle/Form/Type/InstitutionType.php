<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class InstitutionType extends AbstractType
{

    private $instance;
    private $dm;

    public function __construct(DocumentManager $dm, Instance $instance = null)
    {
        $this->instance = $instance;
        $this->dm = $dm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')->add('abbreviation')
                ->add('website', null, array(
                    'required' => false
                ))
                ->add('address', null, array(
                    'required' => false
                ))
                ->add('isLiblink', null, array(
                    'required' => false
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->dm, 'parent', false);
        $builder->addEventSubscriber($subscriber);

        if (is_null($this->instance)) {
            $builder
                    ->add('instance', null, array(
                        'required' => false,
                        'label' => 'Owning Instance',
                    ))
                    ->add('celsiusInstance', null, array(
                        'required' => false,
                        'label' => 'Celsius Instance',
                    ))
            ;
        } else {
            $builder
                    ->add('instance', 'celsius3_corebundle_instance_selector', array(
                        'data' => $this->instance,
                        'attr' => array(
                            'value' => $this->instance->getId(),
                            'readonly' => 'readonly',
                        ),
                    ))
            ;
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_institutiontype';
    }

}

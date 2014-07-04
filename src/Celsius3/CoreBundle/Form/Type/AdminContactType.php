<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class AdminContactType extends ContactType
{

    private $owningInstance;
    private $dm;

    public function __construct(Instance $owningInstance, DocumentManager $dm)
    {
        parent::__construct();
        $this->owningInstance = $owningInstance;
        $this->dm = $dm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('owningInstance', 'celsius3_corebundle_instance_selector', array(
                    'data' => $this->owningInstance,
                    'attr' => array(
                        'value' => $this->owningInstance->getId(),
                        'readonly' => 'readonly',
                    ),
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->dm);
        $builder->addEventSubscriber($subscriber);
    }

}

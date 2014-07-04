<?php

namespace Celsius3\CoreBundle\Form\Type;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class CatalogType extends AbstractType
{

    private $dm;
    private $instance;

    public function __construct(DocumentManager $dm, Instance $instance)
    {
        $this->dm = $dm;
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('url')
                ->add('comments', 'textarea', array(
                    'required' => false,
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->dm, 'institution', false);
        $builder->addEventSubscriber($subscriber);

        if ($this->instance->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
            $builder->add('instance');
        } else {
            $builder->add('instance', 'celsius3_corebundle_instance_selector', array(
                'data' => $this->instance,
                'attr' => array(
                    'value' => $this->instance->getId(),
                    'readonly' => 'readonly',
                ),
            ));
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_catalogtype';
    }

}

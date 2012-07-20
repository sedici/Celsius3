<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Celsius\Celsius3Bundle\Form\DataTransformer\InstanceToIdTransformer;
use Doctrine\ODM\MongoDB\DocumentManager;

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

    public function buildForm(FormBuilder $builder, array $options)
    {
        $transformer = new InstanceToIdTransformer($this->dm);
        $builder->appendClientTransformer($transformer);
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'invalid_message' => 'The selected Instance does not exist',
        );
    }

    public function getParent(array $options)
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'instance_selector';
    }

}

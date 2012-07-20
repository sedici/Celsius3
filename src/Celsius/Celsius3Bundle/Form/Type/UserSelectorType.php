<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Celsius\Celsius3Bundle\Form\DataTransformer\UserToIdTransformer;
use Doctrine\ODM\MongoDB\DocumentManager;

class UserSelectorType extends AbstractType
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
        $transformer = new UserToIdTransformer($this->dm);
        $builder->appendClientTransformer($transformer);
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'invalid_message' => 'The selected User does not exist',
        );
    }

    public function getParent(array $options)
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'user_selector';
    }

}

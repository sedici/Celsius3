<?php

namespace Celsius\Celsius3Bundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ODM\MongoDB\DocumentManager;

class InstanceToIdTransformer implements DataTransformerInterface
{

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * Transforms an object (Instance) to a string (id).
     *
     * @param  Instance|null $instance
     * @return string
     */
    public function transform($instance)
    {
        if (null === $instance)
        {
            return "";
        }

        return $instance->getId();
    }

    /**
     * Transforms a string (id) to an object (Instance).
     *
     * @param  string $id
     * @return Instance|null
     * @throws TransformationFailedException if object (Instance) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id)
        {
            return null;
        }

        $instance = $this->dm
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('id' => $id));

        if (null === $instance)
        {
            throw new TransformationFailedException(sprintf(
                            'An instance with id "%s" does not exist!', $id
            ));
        }
        
        return $instance;
    }

}

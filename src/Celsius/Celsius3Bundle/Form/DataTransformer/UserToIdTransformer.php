<?php

namespace Celsius\Celsius3Bundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ODM\MongoDB\DocumentManager;

class UserToIdTransformer implements DataTransformerInterface
{

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * Transforms an object (BaseUser) to a string (id).
     *
     * @param  BaseUser|null $instance
     * @return string
     */
    public function transform($user)
    {
        if (null === $user)
        {
            return "";
        }

        return $user->getId();
    }

    /**
     * Transforms a string (id) to an object (BaseUser).
     *
     * @param  string $id
     * @return BaseUser|null
     * @throws TransformationFailedException if object (BaseUser) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id)
        {
            return null;
        }

        $user = $this->dm
                ->getRepository('CelsiusCelsius3Bundle:BaseUser')
                ->findOneBy(array('id' => $id));

        if (null === $user)
        {
            throw new TransformationFailedException(sprintf(
                            'A user with id "%s" does not exist!', $id
            ));
        }

        return $user;
    }

}

<?php

namespace Celsius3\MessageBundle\DataTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Form\DataTransformer\UserToUsernameTransformer;
use FOS\MessageBundle\DataTransformer\RecipientsDataTransformer;

class CustomRecipientsDataTransformer extends RecipientsDataTransformer
{

    /**
     * @var UserToUsernameTransformer
     */
    private $userToUsernameTransformer;

    /**
     * @param UserToUsernameTransformer $userToUsernameTransformer
     */
    public function __construct(
            UserToUsernameTransformer $userToUsernameTransformer)
    {
        $this->userToUsernameTransformer = $userToUsernameTransformer;
    }

    /**
     * Transforms a collection of recipients into a string
     *
     * @param Collection $recipients
     *
     * @return string
     */
    public function transform($recipients)
    {
        if (null === $recipients) {
            return new ArrayCollection();
        }

        $usernames = new ArrayCollection();

        foreach ($recipients as $recipient) {
            $usernames
                    ->add(
                            $this->userToUsernameTransformer
                                    ->transform($recipient));
        }

        return $usernames;
    }

    /**
     * Transforms a string (usernames) to a Collection of UserInterface
     *
     * @param string $usernames
     *
     * @throws UnexpectedTypeException
     * @throws TransformationFailedException
     * @return Collection $recipients
     */
    public function reverseTransform($usernames)
    {
        return $usernames;
    }

}

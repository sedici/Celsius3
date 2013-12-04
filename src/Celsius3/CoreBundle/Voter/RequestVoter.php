<?php

namespace Celsius3\CoreBundle\Voter;

use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;

/**
 * Voter based on the uri
 */
class RequestVoter implements VoterInterface
{

    protected $request_stack;

    public function __construct(RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
    }

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param ItemInterface $item
     * @return boolean|null
     */
    public function matchItem(ItemInterface $item)
    {
        if (false !== strpos($this->request_stack->getCurrentRequest()->getRequestUri(), $item->getUri())) {
            return true;
        }

        return null;
    }

}

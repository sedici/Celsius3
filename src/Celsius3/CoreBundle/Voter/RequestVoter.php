<?php

namespace Celsius3\CoreBundle\Voter;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;

/**
 * Voter based on the uri
 */
class RequestVoter implements VoterInterface
{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
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
        if (false !== strpos($this->request->getRequestUri(), $item->getUri())) {
            return true;
        }

        return null;
    }

}

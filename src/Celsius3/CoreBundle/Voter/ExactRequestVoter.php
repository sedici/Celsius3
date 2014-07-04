<?php

namespace Celsius3\CoreBundle\Voter;

use Knp\Menu\ItemInterface;

/**
 * Voter based on the uri
 */
class ExactRequestVoter extends RequestVoter
{

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param  ItemInterface $item
     * @return boolean|null
     */
    public function matchItem(ItemInterface $item)
    {
        if ($item->getUri() === preg_replace('/\?.*/', '', $this->request_stack->getCurrentRequest()->getRequestUri())) {
            return true;
        }

        return null;
    }

}

<?php

namespace FOS\MessageBundle\EntityManager;

use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Query\Builder;
use FOS\MessageBundle\EntityManager\ThreadManager as BaseThreadManager;

class ThreadManager extends BaseThreadManager
{

    /**
     * Finds not deleted threads for a participant,
     * matching the given search term
     * ordered by last message not written by this participant in reverse order.
     *
     * @param ParticipantInterface $participant
     * @param string $search
     * @return Builder a query builder suitable for pagination
     */
    public function getParticipantThreadsBySearchQueryBuilder(ParticipantInterface $participant, $search)
    {
        // remove all non-word chars
        $search = preg_replace('/[^\w]/', ' ', trim($search));
        // build a regex like (term1|term2)
        $regex = sprintf('/(%s)/', implode('|', explode(' ', $search)));

        throw new \Exception('not yet implemented');
    }
}

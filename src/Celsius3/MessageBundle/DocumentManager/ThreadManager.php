<?php

namespace Celsius3\MessageBundle\DocumentManager;
use FOS\MessageBundle\DocumentManager\ThreadManager as BaseThreadManager;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * Default MongoDB ThreadManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
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
    public function getParticipantThreadsBySearchQueryBuilder(
            ParticipantInterface $participant, $search)
    {
        // remove all non-word chars
        $search = preg_replace('/[^\w]/', ' ', trim($search));
        // build a regex like (term1|term2)
        $regex = sprintf('/(%s)/', implode('|', explode(' ', $search)));
        $expr = new \MongoRegex('/.*' . $search . '.*/i');

        $qb = $this->dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->createQueryBuilder()->hydrate(false)->select('id');
        $qb = $qb->addOr($qb->expr()->field('name')->equals($expr))
                ->addOr($qb->expr()->field('surname')->equals($expr))
                ->addOr($qb->expr()->field('username')->equals($expr));

        $users = array_keys($qb->getQuery()->execute()->toArray());

        $qb2 = $this->repository->createQueryBuilder()
                ->field('activeParticipants')->equals($participant->getId());

        return $qb2
                ->addOr(
                        $qb2->expr()->field('keywords')
                                ->equals(new \MongoRegex($regex)))
                ->addOr($qb2->expr()->field('participants.id')->in($users))
                // Note: This query is not anchored, so "keywords" need not be indexed

                /* TODO: Sort by date of the last message written by this
                 * participant, as is done for ORM. This is not possible with the
                 * current schema; compromise by sorting by last message date.
                 */
                ->sort('lastMessageDate', 'desc');
    }

}

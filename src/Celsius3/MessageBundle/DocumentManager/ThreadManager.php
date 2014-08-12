<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\MessageBundle\DocumentManager;
use FOS\MessageBundle\DocumentManager\ThreadManager as BaseThreadManager;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ODM\MongoDB\Query\Builder;

class ThreadManager extends BaseThreadManager
{

    /**
     * Finds not deleted threads for a participant,
     * matching the given search term
     * ordered by last message not written by this participant in reverse order.
     *
     * @param  ParticipantInterface $participant
     * @param  string               $search
     * @return Builder              a query builder suitable for pagination
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
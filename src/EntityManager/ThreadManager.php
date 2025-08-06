<?php

namespace Celsius3\EntityManager;

// use FOS\MessageBundle\Model\ParticipantInterface;

use Celsius3\Entity\Thread;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

// use FOS\MessageBundle\EntityManager\ThreadManager as BaseThreadManager;

class ThreadManager // extends BaseThreadManager
{

    protected EntityRepository $repository;

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager
            ->getRepository(Thread::class);
    }

    /**
     * Finds not deleted threads for a participant,
     * containing at least one message not written by this participant,
     * ordered by last message not written by this participant in reverse order.
     * In one word: an inbox.
     *
     * @param $participant
     *
     * @return QueryBuilder a query builder suitable for pagination
     */
    public function getParticipantInboxThreadsQueryBuilder($participant): QueryBuilder
    {
        return $this->repository->createQueryBuilder('t')
            ->addSelect('tm.lastMessageDate AS HIDDEN lastMessageDate')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // there is at least one message written by an other participant
            ->andWhere('tm.lastMessageDate IS NOT NULL')

            // sort by date of last message written by an other participant
            ->orderBy('lastMessageDate', 'DESC')
        ;
    }

    /**
     * Finds not deleted threads from a participant,
     * containing at least one message written by this participant,
     * ordered by last message written by this participant in reverse order.
     * In one word: an sentbox.
     *
     * @param $participant
     *
     * @return QueryBuilder a query builder suitable for pagination
     */
    public function getParticipantSentThreadsQueryBuilder($participant): QueryBuilder
    {
        return $this->repository->createQueryBuilder('t')
            ->addSelect('tm.lastParticipantMessageDate AS HIDDEN lastParticipantMessageDate')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // there is at least one message written by this participant
            ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')

            // sort by date of last message written by this participant
            ->orderBy('lastParticipantMessageDate', 'DESC')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipantDeletedThreadsQueryBuilder($participant): QueryBuilder
    {
        return $this->repository->createQueryBuilder('t')
            ->addSelect('tm.lastMessageDate AS HIDDEN lastMessageDate')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread is deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', true, \PDO::PARAM_BOOL)

            // sort by date of last message
            ->orderBy('lastMessageDate', 'DESC')
        ;
    }

    /**
     * Finds not deleted threads for a participant,
     * matching the given search term
     * ordered by last message not written by this participant in reverse order.
     *
     * @param $participant
     * @param string               $search
     *
     * @return QueryBuilder a query builder suitable for pagination
     */
    public function getParticipantThreadsBySearchQueryBuilder( $participant, $search): never
    {
        throw new \Exception('not yet implemented');
    }
}

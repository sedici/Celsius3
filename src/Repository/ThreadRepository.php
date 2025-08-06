<?php

namespace Celsius3\Repository;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Thread;
use Doctrine\ORM\QueryBuilder;


class ThreadRepository extends BaseRepository
{
    protected static $entityClass = Thread::class;


    public function getParticipantThreadsQueryBuilder(
        BaseUser $participant,
        ?bool $isDeleted = null,
        ?bool $sentByParticipant = null,
        ?int $limit = null
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            // ->innerJoin('tm.participant', 'p')
            ->where('tm.participant = :user_id')
            ->setParameter('user_id', $participant->getId())
            ->orderBy('tm.lastMessageDate', 'DESC');

        $qb = ($isDeleted === null) ? $qb
            : $qb->andWhere('tm.isDeleted = :isDeleted')
                ->setParameter('isDeleted', $isDeleted, \PDO::PARAM_BOOL);

        $qb = ($sentByParticipant === null) ? $qb
            : $qb->andWhere('t.createdBy ' . ($sentByParticipant ? '=' : '<>') . ' :user_id');
                // ->setParameter('sentByParticipant', $sentByParticipant, \PDO::PARAM_BOOL);

        $qb = ($limit === null) ? $qb
            : $qb->setMaxResults($limit);

        return $qb;
    }


    public function getParticipantInboxThreadsQueryBuilder(
        BaseUser $participant
    ): QueryBuilder {
        return $this
            ->getParticipantThreadsQueryBuilder(
                $participant, false
            )
            ->addSelect('tm.lastMessageDate AS HIDDEN lastMessageDate')

            // there is at least one message written by an other participant
            ->andWhere('tm.lastMessageDate IS NOT NULL')

            // sort by date of last message written by an other participant
            ->orderBy('lastMessageDate', 'DESC');
    }


    public function getParticipantNotDeletedThreadsQueryBuilder(
        BaseUser $participant
    ): QueryBuilder {
        return $this->getParticipantThreadsQueryBuilder(
            $participant, false
        );
    }

    public function getParticipantSentThreadsQueryBuilder(
        BaseUser $participant
    ): QueryBuilder {
        return $this->getParticipantThreadsQueryBuilder(
            $participant, false, true
        );
    }


    public function getParticipantDeletedThreadsQueryBuilder(
        BaseUser $participant
    ): QueryBuilder {
        return $this->getParticipantThreadsQueryBuilder(
            $participant, true
        );
    }


    public function getParticipantThreadsBySearchQueryBuilder(
        BaseUser $participant,
        string $search
    ): QueryBuilder {
        // remove all non-word chars
        $search = preg_replace(
            '/[^\w]/', ' ',
            trim($search)
        );

        // build a regex like (term1|term2)
        $regex = sprintf(
            '/(%s)/',
            implode(
                '|', explode(' ', (string) $search)
            )
        );

        return $this
            ->getParticipantNotDeletedThreadsQueryBuilder(
                $participant
            )
            // Note: This query is not anchored, so "keywords" need not be indexed
            ->andWhere('t.keywords REGEXP :regex')
            ->setParameter('regex', $regex)
            ->orderBy('t.lastMessageDate', 'DESC');
    }


    public function findThreadsCreatedBy(
        BaseUser $participant
    ): QueryBuilder {
        return $this
            ->getParticipantNotDeletedThreadsQueryBuilder(
                $participant
            )
            ->andWhere('t.createdBy = :createdBy')
            ->setParameter('createdBy', $participant)
            ->orderBy('t.lastMessageDate', 'DESC');
    }


    // /**
    //  * Creates a base QueryBuilder for fetching threads where the given user is a participant.
    //  */
    // public function createQueryBuilderForUserParticipant(BaseUser $user): QueryBuilder
    // {
    //     $qb = $this->createQueryBuilder('t'); // 't' es el alias para Thread
    //     $qb->select('t')
    //         ->innerJoin('t.metadata', 'tm_participant_check') // tm_participant_check para ThreadMetadata
    //         ->where('tm_participant_check.participant = :user_param')
    //         ->setParameter('user_param', $user)
    //         ->addOrderBy('t.createdAt', 'DESC');
    //     return $qb;
    // }

    // /**
    //  * Returns the DQL string for a subquery that checks for unread messages in a thread 't'.
    //  * The alias 't' is expected to be the alias of the Thread entity in the main query.
    //  * Parameters :user_for_subquery and :is_false_subquery will be used.
    //  */
    // private function getDQLForUnreadMessagesSubquery(): string
    // {
    //     // Este QueryBuilder es solo para construir el DQL de la subconsulta.
    //     // No se ejecuta aquí.
    //     return $this->getEntityManager()->createQueryBuilder()
    //         ->select('1') // Seleccionamos '1' porque solo nos interesa la existencia (EXISTS)
    //         ->from(Message::class, 'm_sub') // m_sub para Message en subconsulta
    //         ->leftJoin(
    //             MessageMetadata::class,
    //             'mm_sub', // mm_sub para MessageMetadata en subconsulta
    //             Join::WITH,
    //             // Condición del JOIN: metadata del mensaje Y para el participante específico
    //             'mm_sub.message = m_sub AND mm_sub.participant = :user_for_subquery'
    //         )
    //         // 't' se refiere al alias del Thread de la consulta principal que usará esta subconsulta
    //         ->where('m_sub.thread = t') 
    //         ->andWhere(
    //             // Usamos expr() de un QueryBuilder temporal solo para construir la expresión OR
    //             $this->getEntityManager()->createQueryBuilder()->expr()->orX(
    //                 'mm_sub.id IS NULL',          // El mensaje no tiene metadata para este usuario (implica no leído)
    //                 'mm_sub.isRead = :is_false_subquery'   // El mensaje tiene metadata y está marcado como no leído
    //             )
    //         )
    //         ->setMaxResults(1) // Optimización: solo necesitamos saber si al menos uno existe
    //         ->getDQL();
    // }

    // /**
    //  * Applies a filter to the QueryBuilder to select only threads with unread messages for the user.
    //  */
    // public function applyUnreadFilter(QueryBuilder $qb, BaseUser $user): QueryBuilder
    // {
    //     $subQueryDQL = $this->getDQLForUnreadMessagesSubquery();
    //     $qb->andWhere($qb->expr()->exists($subQueryDQL))
    //         // Los parámetros de la subconsulta se establecen en el QueryBuilder principal
    //         ->setParameter('user_for_subquery', $user)
    //         ->setParameter('is_false_subquery', false);
    //     return $qb;
    // }

    // /**
    //  * Applies a filter to the QueryBuilder to select only threads where all messages are read by the user,
    //  * or threads that have no messages (considered "read" in this context if the user is a participant).
    //  * It also ensures the thread is not empty if only "truly read" (having messages, all read) is desired.
    //  */
    // public function applyReadFilter(QueryBuilder $qb, BaseUser $user, bool $includeEmptyThreadsAsRead = true): QueryBuilder
    // {
    //     $subQueryDQLUnread = $this->getDQLForUnreadMessagesSubquery();

    //     // Condición: NO existen mensajes no leídos para el usuario en este hilo.
    //     $qb->andWhere($qb->expr()->not($qb->expr()->exists($subQueryDQLUnread)))
    //        ->setParameter('user_for_subquery', $user) // Parámetros para la subconsulta de no leídos
    //        ->setParameter('is_false_subquery', false);

    //     if (!$includeEmptyThreadsAsRead) {
    //         // Si no queremos incluir hilos vacíos como leídos,
    //         // nos aseguramos de que el hilo tenga al menos un mensaje.
    //         $dqlForNonEmptyThread = $this->getEntityManager()->createQueryBuilder()
    //             ->select('1')
    //             ->from(Message::class, 'm_exists')
    //             ->where('m_exists.thread = t') // 't' es el alias del Thread de la consulta principal
    //             ->setMaxResults(1)
    //             ->getDQL();
    //         $qb->andWhere($qb->expr()->exists($dqlForNonEmptyThread));
    //     }
    //     return $qb;
    // }

    // /**
    //  * Applies ordering by thread creation date (newest first) to the QueryBuilder.
    //  * Assumes 't' is the alias for the Thread entity.
    //  */
    // public function applyOrderByCreatedAtDesc(QueryBuilder $qb): QueryBuilder
    // {
    //     $qb->addOrderBy('t.createdAt', 'DESC');
    //     return $qb;
    // }
}
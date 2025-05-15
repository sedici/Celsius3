<?php

namespace Celsius3\Repository;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Message;
use Celsius3\Entity\MessageMetadata;
use Celsius3\Entity\Mixin\ParticipantInterface;
use Celsius3\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }


    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $metaClass;

    /**
     * @param EntityManager $em
     * @param string        $class
     * @param string        $metaClass
     */
    public function __construct(EntityManager $em, $class, $metaClass)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $em->getClassMetadata($class)->name;
        $this->metaClass = $em->getClassMetadata($metaClass)->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbUnreadMessageByParticipant(ParticipantInterface $participant)
    {
        $builder = $this->repository->createQueryBuilder('m');

        return (int) $builder
            ->select($builder->expr()->count('mm.id'))

            ->innerJoin('m.metadata', 'mm')
            ->innerJoin('mm.participant', 'p')

            ->where('p.id = :participant_id')
            ->setParameter('participant_id', $participant->getId())

            ->andWhere('m.sender != :sender')
            ->setParameter('sender', $participant->getId())

            ->andWhere('mm.isRead = :isRead')
            ->setParameter('isRead', false, \PDO::PARAM_BOOL)

            ->getQuery()
            ->getSingleScalarResult();
    }


    public function markAsReadByParticipant(Message $message, BaseUser $user): void
    {
        $message->setIsReadByParticipant($user, true);
    }


    public function markAsUnreadByParticipant(Message $message, BaseUser $user): void
    {
        $message->setIsReadByParticipant($user, false);
    }

    /**
     * Marks all messages of this thread as read by this participant.
     */
    public function markIsReadByThreadAndParticipant(Thread $thread, BaseUser $user, bool $isRead): void
    {
        foreach ($thread->getMessages() as $message) {
            $this->markIsReadByParticipant($message, $user, $isRead);
        }
    }

    /**
     * Marks the message as read or unread by this participant.
     */
    protected function markIsReadByParticipant(Message $message, BaseUSer $user, bool $isRead): void
    {
        $meta = $message->getMetadataForParticipant($user);
        if (!$meta || $meta->getIsRead() == $isRead) {
            return;
        }

        $this->em->createQueryBuilder()
            ->update($this->metaClass, 'm')
            ->set('m.isRead', '?1')
            ->setParameter('1', (bool) $isRead, \PDO::PARAM_BOOL)

            ->where('m.id = :id')
            ->setParameter('id', $meta->getId())

            ->getQuery()
            ->execute();
    }


    public function saveMessage(Message $message, bool $andFlush = true): void
    {
        // $this->denormalize($message);
        $this->em->persist($message);
        if ($andFlush) {
            $this->em->flush();
        }
    }


    public function getClass(): string
    { return $this->class; }


    /*
     * DENORMALIZATION
     *
     * All following methods are relative to denormalization
     */

    /**
     * Performs denormalization tricks.
     */
    protected function denormalize(Message $message): void
    { $this->doMetadata($message); }


    /**
     * Ensures that the message metadata are up to date.
     */
    protected function doMetadata(Message $message): void
    {
        foreach ($message->getThread()->getMetadata() as $threadMeta) {
            $meta = $message->getMetadataForParticipant($threadMeta->getParticipant());
            if (!$meta) {
                $meta = $this->createMessageMetadata();
                $meta->setParticipant($threadMeta->getParticipant());

                $message->addMetadata($meta);
            }
        }
    }

    protected function createMessageMetadata()
    {
        return new $this->metaClass();
    }
}
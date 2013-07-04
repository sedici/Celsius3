<?php
namespace Celsius3\NotificationBundle\Repository;
use Doctrine\ODM\MongoDB\DocumentRepository;

class NotificationRepository extends DocumentRepository
{
    public function getUnreadNotificationsCount($user_id)
    {
        return $this->createQueryBuilder()->field('receivers.id')
                ->equals($user_id)->field('isViewed')->equals(false)
                ->getQuery()->execute()->count();
    }

    public function getUnreadNotifications($user_id, $limit)
    {
        return $this->createQueryBuilder()->field('receivers.id')
                ->equals($user_id)->field('isViewed')->equals(false)
                ->sort('createdAt', 'desc')
                ->limit($limit)
                ->getQuery()->execute();
    }
}

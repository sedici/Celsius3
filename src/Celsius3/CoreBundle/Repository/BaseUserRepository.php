<?php

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Builder;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\UserManager;

/**
 * BaseUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BaseUserRepository extends DocumentRepository
{

    public function findAdmins(Instance $instance)
    {
        return $this->createQueryBuilder()
                        ->field('instance.id')->equals($instance->getId())
                        ->field('roles')->in(array(UserManager::ROLE_ADMIN))
                        ->getQuery()
                        ->execute();
    }

    public function findPendingUsers(Instance $instance = null)
    {
        $qb = $this->createQueryBuilder()
                        ->field('enabled')->equals(false)
                        ->field('locked')->equals(false);

        if (!is_null($instance))
            $qb = $qb->field('instance.id')->equals($instance->getId());

        return $qb->getQuery()->execute();
    }

    public function countUsers(Instance $instance = null)
    {
        $qb = $this->createQueryBuilder()
                        ->field('enabled')->equals(false)
                        ->field('locked')->equals(false);

        if (!is_null($instance))
            $qb = $qb->field('instance.id')->equals($instance->getId());

        return array(
            'pending' => $qb->count()->getQuery()->execute(),
        );
    }

    public function findByTerm($term, $instance = null, $limit = null, array $institutions = array())
    {
        $expr = new \MongoRegex('/.*' . $term . '.*/i');

        $qb = $this->createQueryBuilder();
        $qb = $qb->addOr($qb->expr()->field('name')->equals($expr))
                ->addOr($qb->expr()->field('surname')->equals($expr))
                ->addOr($qb->expr()->field('username')->equals($expr))
                ->addOr($qb->expr()->field('email')->equals($expr));

        if (!is_null($instance)) {
            $qb = $qb->field('instance.id')->equals($instance->getId());
        }

        if (count($institutions) > 0) {
            $qb = $qb->field('institution.id')->in($institutions);
        }

        if (!is_null($limit)) {
            $qb = $qb->limit(10);
        }

        return $qb->getQuery();
    }

    public function addFindByStateType(array $data, Builder $query, Instance $instance = null)
    {
        foreach ($data as $value) {
            switch ($value) {
                case 'enabled':
                    $query = $query->addOr($query->expr()
                                    ->field('enabled')->equals(true)
                                    ->field('locked')->equals(false)
                    );
                    break;
                case 'pending':
                    $query = $query->addOr($query->expr()
                                    ->field('enabled')->equals(false)
                                    ->field('locked')->equals(false)
                    );
                    break;
                case 'rejected':
                    $query = $query->addOr($query->expr()->field('locked')->equals(true));
                    break;
            }
        }

        if (!is_null($instance)) {
            $query = $query->field('instance.id')->equals($instance->getId());
        }

        return $query;
    }

    public function findUsersPerInstance()
    {
        return $this->createQueryBuilder()
                        ->map('function () { emit(this.instance.$id, 1); }')
                        ->reduce('function (k, vals) {
                            var sum = 0;
                            for (var i in vals) {
                                sum += vals[i];
                            }

                            return sum;
                        }')
                        ->getQuery()
                        ->execute();
    }

    public function findNewUsersPerInstance()
    {
        return $this->createQueryBuilder()
                        ->field('enabled')->equals(false)
                        ->map('function () { emit(this.instance.$id, 1); }')
                        ->reduce('function (k, vals) {
                            var sum = 0;
                            for (var i in vals) {
                                sum += vals[i];
                            }

                            return sum;
                        }')
                        ->getQuery()
                        ->execute();
    }

}

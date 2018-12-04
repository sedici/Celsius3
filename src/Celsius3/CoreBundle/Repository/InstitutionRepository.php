<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\CoreBundle\Repository;

use Celsius3\CoreBundle\Entity\Institution;
use Celsius3\CoreBundle\Entity\Country;
use Celsius3\CoreBundle\Entity\City;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Hive;

/**
 * InstitutionRepository.
 */
class InstitutionRepository extends BaseRepository
{
    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, $firstLevel = false, Hive $hive = null, $country_id = null, $city_id = null, $filter = null)
    {
        $qb = $this->createQueryBuilder('e')
                ->where('e.instance = :instance_id')
                ->orWhere('e.instance = :directory_id')
                ->orderBy('e.name', 'asc')
                ->setParameter('instance_id', $instance->getId())
                ->setParameter('directory_id', $directory->getId());

        if ($firstLevel) {
            $qb = $qb->andWhere('e.parent IS NULL');
        }

        if (!is_null($country_id)) {
            $qb = $qb->andWhere('e.country = :country_id')
                    ->setParameter('country_id', $country_id);
        }

        if (!is_null($city_id)) {
            $qb = $qb->andWhere('e.city = :city_id')
                    ->setParameter('city_id', $city_id);
        }

        if (!is_null($filter)) {
            if ($filter === 'hive' && !is_null($hive)) {
                $qb = $qb->andWhere('e.hive = :hive_id')
                        ->setParameter('hive_id', $hive->getId());
            } elseif ($filter === 'celsius3') {
                $qb = $qb->andWhere('e.celsiusInstance IS NOT NULL');
            }
        }

        return $qb;
    }

    public function countRequestsOrigin($instance, $type, $initialYear, $finalYear, $country = null, $institution = null)
    {
        if (!is_null($institution)) {
            $base = $this->countInstitutionRequestsOriginPerInstitution($instance, $type, $initialYear, $finalYear, $institution);
            foreach ($base as $key => $count) {
                $base[$key]['requestsCount'] += $this->total($instance, $type, $count['id']);
            }

            return $base;
        } else {
            if (!is_null($country)) {
                $base = $this->countCountryRequestsOriginPerInstitution($instance, $type, $initialYear, $finalYear, $country);
                foreach ($base as $key => $count) {
                    $base[$key]['requestsCount'] += $this->total($instance, $type, $count['id']);
                }

                return $base;
            } else {
                $base = $this->countTotalRequestsOriginPerCountry($instance, $type, $initialYear, $finalYear);
                foreach ($base as $key => $count) {
                    $base[$key]['requestsCount'] += $this->total($instance, $type, $count['id']);
                }

                return $base;
            }
        }
    }

    private function countTotalRequestsOriginPerCountry($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('institution');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('institution.instance = :instance')->setParameter('instance', $instance);
        }

        $query = $qb->select('country.id id')
                ->addSelect('country.name name')
                ->addSelect('IDENTITY(institution.country) institutionCountry')
                ->addSelect('COUNT(request) requestsCount')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->leftJoin('institution.country', 'country')
                ->andWhere('request.type = :type OR request.type IS NULL')->setParameter('type', $type)
                ->groupBy('id')
                ->addGroupBy('name')
                ->addGroupBy('institutionCountry');

        if ($initialYear === $finalYear) {
            $query = $query->andWhere('YEAR(request.createdAt) = :year')->setParameter('year', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $query = $query->andWhere('YEAR(request.createdAt) >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andWhere('YEAR(request.createdAt) <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        return $query->getQuery()->getResult();
    }

    private function countCountryRequestsOriginPerInstitution($instance, $type, $initialYear, $finalYear, $country)
    {
        $qb = $this->createQueryBuilder('institution');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('institution.instance = :instance')->setParameter('instance', $instance);
        }

        $query = $qb->select('institution.name name')
                ->addSelect('institution.id id')
                ->addSelect('IDENTITY(institution.country) institutionCountry')
                ->addSelect('COUNT(request.id) requestsCount')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->andWhere('institution.country = :country')->setParameter('country', $country)
                ->andWhere('institution.parent IS NULL')
                ->andwhere('request.type = :type OR request.type IS NULL')->setParameter('type', $type)
                ->groupBy('institution.id');

        if ($initialYear === $finalYear) {
            $query = $query->andWhere('YEAR(request.createdAt) = :year')->setParameter('year', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $query = $query->andWhere('YEAR(request.createdAt) >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andWhere('YEAR(request.createdAt) <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        return $query->getQuery()->getResult();
    }

    public function countInstitutionRequestsOriginPerInstitution($instance, $type, $initialYear, $finalYear, $institution)
    {
        $qb = $this->createQueryBuilder('institution');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('institution.instance = :instance')->setParameter('instance', $instance);
        }

        $query = $qb->addSelect('institution.name name')
                ->addSelect('institution.id id')
                ->addSelect('COUNT(request.id) requestsCount')
                ->addSelect('IDENTITY(institution.country) institutionCountry')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->andWhere('institution.parent = :parent')->setParameter('parent', $institution)
                ->andWhere('request.type = :type OR request.type IS NULL')->setParameter('type', $type)
                ->groupBy('institution.id');

        if ($initialYear === $finalYear) {
            $query = $query->andWhere('YEAR(request.createdAt) = :year')->setParameter('year', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $query = $query->andWhere('YEAR(request.createdAt) >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andWhere('YEAR(request.createdAt) <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        return $query->getQuery()->getResult();
    }

    public function total($instance, $type, $institution)
    {
        $qb = $this->createQueryBuilder('institution');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('institution.instance = :instance')->setParameter('instance', $instance);
        }

        $counts = $qb->addSelect('institution.id id')
                ->addSelect('COUNT(request.id) requestsCount')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->andWhere('institution.parent = :parent')->setParameter('parent', $institution)
                ->andWhere('request.type = :type OR request.type IS NULL')->setParameter('type', $type)
                ->groupBy('institution.id')
                ->getQuery()
                ->getResult();

        $total = 0;
        foreach ($counts as $count) {
            $total += $count['requestsCount'] + $this->total($instance, $type, $count['id']);
        }

        return $total;
    }

    public function getBaseInstitution($institution)
    {
        $parent = $institution->getParent();
        while (!is_null($parent)) {
            $institution = $parent;
            $parent = $institution->getParent();
        }

        return $institution;
    }

    private function getChilds(array $institutions)
    {
        return array_map('current', $this->createQueryBuilder('i')
                ->select('i.id')
                ->where('i.parent IN (:institutions)')
                ->setParameter('institutions', $institutions)
                ->getQuery()->getArrayResult());
    }

    public function getInstitutionsTree(Institution $institution)
    {
        $institutions = array($institution->getId());
        $ids = array();

        while (count($institutions) > 0) {
            $ids = array_merge($ids, $institutions);
            $institutions = $this->getChilds($institutions);
        }

        return $ids;
    }

    public function findByCountryAndCityQB(Country $country = null, City $city = null)
    {
        $qb = $this->createQueryBuilder('i');

        if ($city instanceof City) {
            $qb = $qb->where('i.city = :city_id')
                    ->setParameter('city_id', $city->getId());
        } elseif ($country instanceof Country) {
            $qb = $qb->where('i.country = :country_id')
                    ->andWhere('i.city IS NULL')
                    ->setParameter('country_id', $country->getId());
        } else {
            $qb = $qb->where('i.city IS NULL')
                    ->andWhere('i.country IS NULL');
        }

        return $qb->orderBy('i.name', 'asc');
    }

    public function findByCountry($country_id)
    {
        return $this->createQueryBuilder('i')
                    ->where('i.country = :country')
                    ->setParameter('country', $country_id)
                    ->andWhere('i.parent IS NULL')
                    ->getQuery()->getResult();
    }

    public function findForCountryOrCity($country_id, $city_id)
    {
        $qb = $this->createQueryBuilder('i')
                    ->select('i.id, i.name, i.abbreviation, p.id as parent_id, ins.id AS celsiusInstance, h.id AS hive_id')
                    ->leftJoin('i.parent', 'p')
                    ->leftJoin('i.celsiusInstance', 'ins')
                    ->leftJoin('i.hive', 'h');

        if ($city_id) {
            $qb = $qb->where('i.city = :cid')->setParameter('cid', $city_id);
        } elseif ($country_id) {
            $qb = $qb->where('i.country = :country')->setParameter('country', $country_id);
        }

        return $qb->getQuery()->getArrayResult();
    }
}

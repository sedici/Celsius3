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

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Hive;
use Doctrine\ORM\Query\ResultSetMapping;

class InstitutionRepository extends EntityRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, Hive $hive, $country_id, $city_id = null, $filter = null)
    {
        $qb = $this->createQueryBuilder('i')
                ->where('c.instance_id = :instance_id')
                ->orWhere('c.instance_id = :directory_id')
                ->andWhere('i.country_id = :country_id')
                ->andWhere('i.parent_id IS NULL')
                ->setParameter('instance_id', $instance->getId())
                ->setParameter('directory_id', $directory->getId())
                ->setParameter('country_id', $country_id);

        if (!is_null($city_id)) {
            $qb = $qb->andWhere('i.city_id = :city_id')
                    ->setParameter('city_id', $city_id);
        }

        if (!is_null($filter)) {
            if ($filter === 'hive') {
                $qb = $qb->andWhere('i.hive_id')
                        ->setParameter('hive_id', $hive->getId());
            } elseif ($filter === 'celsius3') {
                $qb = $qb->andWhere('i.celsius_instance_id IS NOT NULL');
            }
        }

        return $qb->getQuery()->getResult();
    }
    
    
    public function countRequestsOrigin($instance,$type,$country = null,$institution = null) {
        if(!is_null($institution)){
            $base = $this->countInstitutionRequestsOriginPerInstitution($instance,$type,$institution);
            foreach($base as $key => $count){
                $base[$key]['requestsCount'] += $this->total($instance,$type,$count['id']);
            }
            return $base;
        } else {
            if(!is_null($country)){
                $base = $this->countCountryRequestsOriginPerInstitution($instance,$type,$country);
                foreach($base as $key => $count){
                    $base[$key]['requestsCount'] += $this->total($instance,$type,$count['id']);
                }
                return $base;
            } else {
                $base = $this->countTotalRequestsOriginPerCountry($instance,$type);
                foreach($base as $key => $count){
                    $base[$key]['requestsCount'] += $this->total($instance,$type,$count['id']);
                }
                return $base;
            }
        }
    }
    
    private function countTotalRequestsOriginPerCountry($instance,$type) {
        $query = $this->createQueryBuilder('institution')
                ->select('country.name name')
                ->addSelect('country.id id')
                ->addSelect('IDENTITY(institution.country) institutionCountry')
                ->addSelect('COUNT(request) requestsCount')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->leftJoin('institution.country', 'country')
                ->andWhere('institution.instance = :instance')->setParameter('instance',$instance)
                ->andWhere('request.type = :type OR request.type IS NULL')->setParameter('type',$type)
                ->groupBy('country.id');
        
        return $query->getQuery()->getResult();
    }
    
    private function countCountryRequestsOriginPerInstitution($instance,$type,$country){
        $query = $this->createQueryBuilder('institution')
                ->select('institution.name name')
                ->addSelect('institution.id id')
                ->addSelect('IDENTITY(institution.country) institutionCountry')
                ->addSelect('COUNT(request.id) requestsCount')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->andWhere('institution.instance = :instance')->setParameter('instance',$instance)
                ->andWhere('institution.country = :country')->setParameter('country',$country)
                ->andWhere('institution.parent IS NULL')
                ->andwhere('request.type = :type OR request.type IS NULL')->setParameter('type',$type)
                ->groupBy('institution.id');
        
        return $query->getQuery()->getResult();
    }

    public function countInstitutionRequestsOriginPerInstitution($instance,$type,$institution){
        $qb = $this->createQueryBuilder('institution');
        $query = $qb->addSelect('institution.name name')
                ->addSelect('institution.id id')
                ->addSelect('COUNT(request.id) requestsCount')
                ->addSelect('IDENTITY(institution.country) institutionCountry')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->andWhere('institution.instance = :instance')->setParameter('instance',$instance)
                ->andWhere('institution.parent = :parent')->setParameter('parent', $institution)
                ->andWhere('request.type = :type OR request.type IS NULL')->setParameter('type',$type)
                ->groupBy('institution.id');
        return $query->getQuery()->getResult();
    }

    public function total($instance,$type,$institution){
        $counts = $this->createQueryBuilder('institution')
                ->addSelect('institution.id id')
                ->addSelect('COUNT(request.id) requestsCount')
                ->leftJoin('institution.users', 'user')
                ->leftJoin('user.orders', 'request')
                ->andWhere('institution.instance = :instance')->setParameter('instance',$instance)
                ->andWhere('institution.parent = :parent')->setParameter('parent', $institution)
                ->andWhere('request.type = :type OR request.type IS NULL')->setParameter('type',$type)
                ->groupBy('institution.id')
                ->getQuery()
                ->getResult();
        
        $total = 0;
        foreach($counts as $count) {
            $total += $count['requestsCount'] + $this->total($instance,$type,$count['id']);
        }
        return $total;
    }
}
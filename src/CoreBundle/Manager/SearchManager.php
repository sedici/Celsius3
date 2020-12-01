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

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Entity\Instance;
use Elastica\Query\Term;
use Symfony\Component\DependencyInjection\Container;
use Elastica\Query;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\Nested;
use Elastica\Query\BoolQuery;

class SearchManager
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private function prepareKeyword($keyword)
    {
        $search = '';
        foreach (explode(' ', trim($keyword)) as $word) {
            $search .= " *$word* ";
        }

        return $search;
    }

    private function addAgregations(Query $query)
    {
        $typesAgg = (new Terms('types'))->setField('type');
        $ownersAgg = (new Nested('owners', 'owner'))
            ->addAggregation((new Terms('owners'))->setField('owner.username'));
        $operatorsAgg = (new Nested('operators', 'operator'))
            ->addAggregation((new Terms('operators'))->setField('operator.username'));
        $materialsAgg = (new Nested('materials', 'order.materialData'))
            ->addAggregation((new Terms('materials'))->setField('order.materialData.materialType'));
        $statesAgg = (new Nested('states', 'currentState'))
            ->addAggregation((new Terms('states'))->setField('currentState.type'));

        $query->addAggregation($typesAgg);
        $query->addAggregation($ownersAgg);
        $query->addAggregation($operatorsAgg);
        $query->addAggregation($materialsAgg);
        $query->addAggregation($statesAgg);
    }

    private function addInstanceFilter(BoolQuery $boolQuery, Instance $instance)
    {
        $nested = new \Elastica\Query\Nested();
        $nested->setPath('instance');
        $nested->setQuery((new Term())->setTerm('instance.id', $instance->getId()));
        $boolQuery->addFilter($nested);
    }

    private function addOperatorsFilter(BoolQuery $boolQuery, $value)
    {
        $nested = new \Elastica\Query\Nested();
        $nested->setPath('operator');
        $nested->setQuery((new Term())->setTerm('operator.username', $value));
        $boolQuery->addFilter($nested);
    }

    private function addOwnersFilter(BoolQuery $boolQuery, $value)
    {
        $nested = new \Elastica\Query\Nested();
        $nested->setPath('owner');
        $nested->setQuery((new Term())->setTerm('owner.username', $value));
        $boolQuery->addMust($nested);
    }

    private function addMaterialsFilter(BoolQuery $boolQuery, $value)
    {
        $nested = new \Elastica\Query\Nested();
        $nested->setPath('order.materialData');
        $nested->setQuery((new Term())->setTerm('order.materialData.materialType', $value));
        $boolQuery->addMust($nested);
    }

    private function addStatesFilter(BoolQuery $boolQuery, $value)
    {
        $nested = new \Elastica\Query\Nested();
        $nested->setPath('currentState');
        $nested->setQuery((new Term())->setTerm('currentState.type', $value));
        $boolQuery->addMust($nested);
    }

    private function addTypesFilter(BoolQuery $boolQuery, $value)
    {
        $boolQuery->addMust((new Term())->setTerm('type', $value));
    }

    private function addAggregationsFilters(BoolQuery $boolQuery, array $filters = array())
    {
        if (array_key_exists('instance', $filters)) {
            $this->addInstanceFilter($boolQuery, $filters['instance']);
        }
        if (array_key_exists('operators', $filters)) {
            $this->addOperatorsFilter($boolQuery, $filters['operators']);
        }
        if (array_key_exists('owners', $filters)) {
            $this->addOwnersFilter($boolQuery, $filters['owners']);
        }
        if (array_key_exists('materials', $filters)) {
            $this->addMaterialsFilter($boolQuery, $filters['materials']);
        }
        if (array_key_exists('states', $filters)) {
            $this->addStatesFilter($boolQuery, $filters['states']);
        }
        if (array_key_exists('types', $filters)) {
            $this->addTypesFilter($boolQuery, $filters['types']);
        }
    }

    public function search($keyword, $filters, Instance $instance)
    {
        $query = new Query();
        $boolQuery = new BoolQuery();

        $this->addAgregations($query);
        $this->addInstanceFilter($boolQuery, $instance);
        $this->addAggregationsFilters($boolQuery, $filters);

        $boolQuery->addShould($this->getTypeQuery($keyword));
        $boolQuery->addShould($this->getTitleQuery($keyword));
        $boolQuery->addShould($this->getJournalQuery($keyword));
        $boolQuery->addShould($this->getCodeQuery($keyword));
        $boolQuery->addShould($this->getMaterialTypeQuery($keyword));
        $boolQuery->addShould($this->getISBNQuery($keyword));
        $boolQuery->addShould($this->getEditorQuery($keyword));
        $boolQuery->setMinimumShouldMatch(1);

        $query->setQuery($boolQuery);

        $finder = $this->container->get('fos_elastica.finder.app.request');

        return $finder->createPaginatorAdapter($query);
    }

    public function getAggsUsersData($aggs)
    {
        $usernames = array();
        foreach ($aggs['owners']['owners']['buckets'] as $user) {
            $usernames[] = $user['key'];
        }

        foreach ($aggs['operators']['operators']['buckets'] as $user) {
            $usernames[] = $user['key'];
        }

        $usernames = array_unique($usernames);

        $baseusers = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('Celsius3CoreBundle:BaseUser')
            ->findBy(['username' => $usernames]);

        $users = [];
        foreach ($baseusers as $user) {
            $users[$user->getUsername()] = [
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
            ];
        }

        return $users;
    }

    private function getTitleQuery($keyword): Query\AbstractQuery
    {
        $matchQuery = new Query\Match();
        $matchQuery->setFieldQuery('order.materialData.title', $keyword);
        $matchQuery->setFieldOperator('order.materialData.title', Query\Match::OPERATOR_AND);

        $nestedMaterialDataQuery = new Query\Nested();
        $nestedMaterialDataQuery->setQuery($matchQuery);
        $nestedMaterialDataQuery->setPath('order.materialData');

        $nestedOrderQuery = new Query\Nested();
        $nestedOrderQuery->setQuery($nestedMaterialDataQuery);
        $nestedOrderQuery->setPath('order');

        return $nestedOrderQuery;
    }

    private function getMaterialTypeQuery($keyword): Query\AbstractQuery
    {
        $termsQuery = new Query\Terms('order.materialData.materialType', explode(" ", preg_replace('/[^a-z0-9 ]/i', '', $keyword)));

        $nestedMaterialDataQuery = new Query\Nested();
        $nestedMaterialDataQuery->setQuery($termsQuery);
        $nestedMaterialDataQuery->setPath('order.materialData');

        $nestedOrderQuery = new Query\Nested();
        $nestedOrderQuery->setQuery($nestedMaterialDataQuery);
        $nestedOrderQuery->setPath('order');

        return $nestedOrderQuery;
    }

    private function getJournalQuery($keyword): Query\AbstractQuery
    {
        $matchQuery = new Query\Match();
        $matchQuery->setFieldQuery('order.materialData.journal', $keyword);
        $matchQuery->setFieldOperator('order.materialData.journal', Query\Match::OPERATOR_AND);

        $nestedMaterialDataQuery = new Query\Nested();
        $nestedMaterialDataQuery->setQuery($matchQuery);
        $nestedMaterialDataQuery->setPath('order.materialData');

        $nestedOrderQuery = new Query\Nested();
        $nestedOrderQuery->setQuery($nestedMaterialDataQuery);
        $nestedOrderQuery->setPath('order');

        return $nestedOrderQuery;
    }

    private function getEditorQuery($keyword): Query\AbstractQuery
    {
        $matchQuery = new Query\Match();
        $matchQuery->setFieldQuery('order.materialData.editor', $keyword);
        $matchQuery->setFieldOperator('order.materialData.editor', Query\Match::OPERATOR_AND);

        $nestedMaterialDataQuery = new Query\Nested();
        $nestedMaterialDataQuery->setQuery($matchQuery);
        $nestedMaterialDataQuery->setPath('order.materialData');

        $nestedOrderQuery = new Query\Nested();
        $nestedOrderQuery->setQuery($nestedMaterialDataQuery);
        $nestedOrderQuery->setPath('order');

        return $nestedOrderQuery;
    }

    private function getCodeQuery($keyword): Query\AbstractQuery
    {
        $terms = explode(' ', preg_replace('/[^0-9 ]/i', '', $keyword));
        $termsQuery = new Query\Terms('order.code', $terms);

        $nestedOrderQuery = new Query\Nested();
        $nestedOrderQuery->setQuery($termsQuery);
        $nestedOrderQuery->setPath('order');

        return $nestedOrderQuery;
    }

    private function getTypeQuery($keyword): Query\AbstractQuery
    {
        $terms = explode(' ', preg_replace('/[^a-z ]/i', '', $keyword));

        return new Query\Terms('type', $terms);
    }

    private function getISBNQuery($keyword): Query\AbstractQuery
    {
        $terms = explode(' ', $keyword);
        $termsQuery = new Query\Terms('order.materialData.isbn', $terms);

        $nestedMaterialDataQuery = new Query\Nested();
        $nestedMaterialDataQuery->setQuery($termsQuery);
        $nestedMaterialDataQuery->setPath('order.materialData');

        $nestedOrderQuery = new Query\Nested();
        $nestedOrderQuery->setQuery($nestedMaterialDataQuery);
        $nestedOrderQuery->setPath('order');

        return $nestedOrderQuery;
    }
}

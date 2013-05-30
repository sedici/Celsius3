<?php

namespace Celsius\Celsius3Bundle\Controller;
abstract class BaseInstanceDependentController extends BaseController
{
    protected function listQuery($name)
    {

        return parent::listQuery($name)->field('instance.id')
                ->equals($this->getInstance()->getId());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:' . $name)
                ->createQueryBuilder()->field('instance.id')
                ->equals($this->getInstance()->getId())->field('id')
                ->equals($id)->getQuery()->getSingleResult();
    }

    protected function getResultsPerPage()
    {
        return $this->get('configuration_helper')
                ->getCastedValue($this->getInstance()->get('results_per_page'));
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('filter_manager')
                ->filter($query, $filter_form,
                        'Celsius\\Celsius3Bundle\\Document\\' . $name,
                        $this->getInstance());
    }

    /**
     * Returns the instance related to the users instance.
     *
     * @return Instance
     */
    protected function getInstance()
    {

        return $this->get('instance_helper')->getSessionInstance();
    }
}

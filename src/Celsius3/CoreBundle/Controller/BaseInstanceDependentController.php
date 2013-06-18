<?php

namespace Celsius3\CoreBundle\Controller;
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
                ->getRepository('Celsius3CoreBundle:' . $name)
                ->createQueryBuilder()->field('instance.id')
                ->equals($this->getInstance()->getId())->field('id')
                ->equals($id)->getQuery()->getSingleResult();
    }

    protected function getResultsPerPage()
    {
        return $this->get('celsius3_core.configuration_helper')
                ->getCastedValue($this->getInstance()->get('results_per_page'));
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')
                ->filter($query, $filter_form,
                        'Celsius3\\CoreBundle\\Document\\' . $name,
                        $this->getInstance());
    }

    /**
     * Returns the instance related to the users instance.
     *
     * @return Instance
     */
    protected function getInstance()
    {

        return $this->get('celsius3_core.instance_helper')->getSessionInstance();
    }
}

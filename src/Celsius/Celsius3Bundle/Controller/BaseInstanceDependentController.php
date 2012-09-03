<?php

namespace Celsius\Celsius3Bundle\Controller;

use Celsius\Celsius3Bundle\Helper\ConfigurationHelper;

abstract class BaseInstanceDependentController extends BaseController
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getInstance()->getId());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getInstance()->getId())
                        ->field('id')->equals($id)
                        ->getQuery()
                        ->getSingleResult();
    }

    protected function getResultsPerPage()
    {
        return ConfigurationHelper::getCastedValue($this->getDocumentManager()
                                ->getRepository('CelsiusCelsius3Bundle:Configuration')
                                ->createQueryBuilder()
                                ->field('instance.id')->equals($this->getInstance()->getId())
                                ->field('key')->equals('results_per_page')
                                ->getQuery()
                                ->getSingleResult());
    }

    /**
     * Returns the instance related to the users instance.
     *
     * @return Instance
     */
    protected function getInstance()
    {
        $instance = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->find($this->get('session')->get('instance_id'));

        if (!$instance)
        {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        return $instance;
    }

}

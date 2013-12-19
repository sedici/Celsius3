<?php

namespace Celsius3\CoreBundle\Controller;

class BaseInstanceDependentRestController extends BaseRestController
{

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

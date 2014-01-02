<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/catalogsearch")
 */
class AdminCatalogSearchRestController extends BaseInstanceDependentRestController
{
    /**
     * 
     * @Post("/", name="admin_rest_catalogsearch_mark", options={"expose"=true})
     */
    public function markAction() {
        
    }
}

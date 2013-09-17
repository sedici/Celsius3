<?php

namespace Celsius3\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\OAuthServerBundle\Document\Client as BaseClient;

/**
 * @MongoDB\Document
 */
class Client extends BaseClient
{

    /**
     * @MongoDB\Id
     */
    protected $id;

}
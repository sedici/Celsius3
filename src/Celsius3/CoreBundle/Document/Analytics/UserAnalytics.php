<?php

namespace Celsius3\CoreBundle\Document\Analytics;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="celsius_analytics")
 */
class UserAnalytics extends Analytics
{

}
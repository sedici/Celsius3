<?php

namespace Celsius3\CoreBundle\Manager;

class OrderManager
{

    const TYPE__SEARCH = 'search';
    const TYPE__PROVISION = 'provision';

    public function getTypes()
    {
        return array(
            self::TYPE__SEARCH,
            self::TYPE__PROVISION,
        );
    }

}

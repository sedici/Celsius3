<?php

namespace Celsius3\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Celsius3CoreBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

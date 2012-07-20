<?php

namespace Celsius\Celsius3Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CelsiusCelsius3Bundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

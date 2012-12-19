<?php

namespace Celsius\Celsius3MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CelsiusCelsius3MessageBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}

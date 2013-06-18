<?php

namespace Celsius3\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Celsius3MessageBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}

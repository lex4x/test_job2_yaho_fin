<?php

namespace Lex4\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Lex4UserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

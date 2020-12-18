<?php

namespace GaeaUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GaeaUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}

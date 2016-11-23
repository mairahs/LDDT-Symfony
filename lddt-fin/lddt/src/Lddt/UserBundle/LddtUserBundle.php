<?php

namespace Lddt\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LddtUserBundle extends Bundle
{
    // Héritage du bundle FOSUserBundle
    public function getParent() {
        return 'FOSUserBundle';
    }
}

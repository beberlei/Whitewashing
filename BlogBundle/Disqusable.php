<?php

namespace Whitewashing\BlogBundle;

use Symfony\Component\Routing\Router;

interface Disqusable
{
    public function getDisqusId();

    public function getDisqusUrl(Router $router);
}
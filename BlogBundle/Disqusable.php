<?php
/*
 * Whitewashing
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace Whitewashing\BlogBundle;

use Symfony\Component\Routing\Router;

/**
 * Interface for elements that should be commentable through Disqus
 */
interface Disqusable
{
    public function getDisqusId();

    public function getDisqusUrl(Router $router);
}
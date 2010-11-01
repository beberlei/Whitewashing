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

namespace Whitewashing\BlogBundle\View;

use Symfony\Component\Templating\Helper\Helper;

/**
 * Admin Navigation Helper
 */
class AdminNav extends Helper
{
    private $viewEngine;

    public function __construct($viewEngine)
    {
        $this->viewEngine = $viewEngine;
    }

    public function menu()
    {
        return $this->viewEngine->render('BlogBundle:Partial:adminNavMenu', array(

        ));
    }

    public function getName()
    {
        return "blogAdminNav";
    }
}
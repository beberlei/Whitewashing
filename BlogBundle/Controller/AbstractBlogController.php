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

namespace Whitewashing\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractBlogController extends Controller
{
    /**
     * @return \Symfony\Component\HttpKernel\Request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    
}


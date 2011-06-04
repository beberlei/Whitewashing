<?php
/**
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

namespace Whitewashing\BlogBundle\Menu;

use Knplabs\Bundle\MenuBundle\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Menu in your blog
 */
class BlogMenu extends Menu
{
    /**
     * @param Request $request
     * @param Router $router
     */
    public function __construct(Request $request, Router $router, SecurityContext $context)
    {
        parent::__construct();

        $this->setCurrentUri($request->getRequestUri());

        $this->addChild('Home', $router->generate('blog'));
        $this->addChild('RSS', $router->generate('blog_feed'));
        
        /* @var $token Null|TokenInterface */
        if ($context->isGranted("ROLE_ADMIN")) {
            $admin = $this->addChild('Admin', $router->generate('blog_admin_dashboard'));
            $admin->addChild('Authors', $router->generate('blog_list_authors'));
            $admin->addChild('Posts', $router->generate('blog_post_admin'));
            $admin->addChild('Create Post', $router->generate('blog_post_new'));
        }
    }
}

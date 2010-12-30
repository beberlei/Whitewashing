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

namespace Whitewashing\BlogBundle\Request;

use Symfony\Bundle\FrameworkBundle\Debug\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class CurrentBlogListener
{
    /**
     * @var Container
     */
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Registers a core.security listener to enforce authorization rules.
     *
     * @param EventDispatcher $dispatcher An EventDispatcher instance
     * @param integer         $priority   The priority
     */
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->connect('core.request', array($this, 'handle'), 250);
    }

    /**
     * {@inheritDoc}
     */
    public function unregister(EventDispatcher $dispatcher)
    {
        
    }

    /**
     * Handles access authorization.
     *
     * @param Event $event An Event instance
     */
    public function handle(Event $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->get('request_type')) {
            return;
        }

        $request = $event->get('request');
        if (strpos($request->attributes->get('_controller'), 'Whitewashing\BlogBundle\Controller') === 0) {
            $blogId = $this->container->getParameter('whitewashing.blog.default_blog_id');
            $blogRepository = $this->container->get('whitewashing.blog.blogservice');
            $blogRepository->setCurrentBlogId($blogId);
        }
    }
}
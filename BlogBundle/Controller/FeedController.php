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

namespace Whitewashing\BlogBundle\Controller;

class FeedController extends AbstractBlogController
{

    public function indexAction()
    {
        $feedService = $this->container->get('whitewashing.blog.feedservice');
        $feed = $feedService->createLatestFeed();

        return $this->renderFeed($feed);
    }

    public function categoryAction()
    {

    }

    public function tagAction($tagName)
    {
        $feedService = $this->container->get('whitewashing.blog.feedservice');
        $feed = $feedService->createTagFeed($tagName);
        return $this->renderFeed($feed);
    }

    private function renderFeed($feed)
    {
        $response = $this->createResponse($feed, 200, array(
            'Content-Type' => 'application/atom+xml; charset=UTF-8'
        ));
        $response->setSharedMaxAge(3600);
        return $response;
    }
}

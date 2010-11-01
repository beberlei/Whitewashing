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

namespace Whitewashing\Blog;

class UrlGenerator
{
    /**
     * @var \Zend_Controller_Router_Abstract $router
     */
    private $router = null;

    /**
     * @var string
     */
    private $hostUrl;

    /**
     * @param Zend_Controller_Router_Abstract $router 
     */
    public function __construct($router, $hostUrl)
    {
        $this->router = $router;
        $this->hostUrl = $hostUrl;
    }

    /**
     * @param Post $post
     * @return string
     */
    public function generatePostUrl(Post $post)
    {
        return $this->hostUrl . $this->router->generate('blog_show_post', array('id' => $post->getId()));
    }

    /**
     * @param Post $post
     */
    public function generatePostCommentFeedUrl(Post $post)
    {
        return $this->hostUrl;
    }

    public function generateFeedUrl()
    {
        return $this->hostUrl . $this->router->generate('blog_feed', array());
    }

    /**
     * @return string
     */
    public function generateMainUrl()
    {
        return $this->hostUrl . $this->router->generate('blog', array());
    }
}
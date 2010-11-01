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

namespace Whitewashing\BlogBundle\View;
use Whitewashing\Blog\PostService;
use Symfony\Component\Templating\Helper\Helper;

class Comments extends Helper
{
    /**
     * @var PostService
     */
    private $postService;

    private $engine;

    public function __construct(PostService $postService, $engine)
    {
        $this->postService = $postService;
        $this->engine = $engine;
    }

    public function renderRecent()
    {
        $comments = $this->postService->getRecentComments();
        return $this->engine->render('BlogBundle:Partial:recentComments.php', array('comments' => $comments));
    }

    public function getName()
    {
        return "comments";
    }
}
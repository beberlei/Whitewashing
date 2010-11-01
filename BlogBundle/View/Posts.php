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

class Posts extends Helper
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var Engine
     */
    private $engine;

    public function __construct(PostService $postService, $engine)
    {
        $this->postService = $postService;
        $this->engine = $engine;
    }

    public function renderRecent()
    {
        $posts = $this->postService->getCurrentPosts(5);

        return $this->engine->render('BlogBundle:Partial:recentPosts', array('posts' => $posts));
    }

    public function renderCloud()
    {
        $tags = $this->postService->getPopularTags();

        $maxCount = 0;
        foreach ($tags AS $tag) {
            $maxCount = max($tag['posts'], $maxCount);
        }

        foreach ($tags AS $tagKey => $tag) {
            if ($maxCount * 0.1 >= $tag['posts']) {
                $class = "tag1";
            } elseif($maxCount * 0.2 >= $tag['posts']) {
                $class = "tag2";
            } elseif($maxCount * 0.4 >= $tag['posts']) {
                $class = "tag3";
            } else {
                $class = "tag4";
            }

            $tags[$tagKey]['class'] = $class;
        }

        usort($tags, array($this, 'sortTags'));

        $html = '<ul class="tagcloud">';
        foreach ($tags AS $tag) {
            $html .= sprintf('<li class="%s"><a href="%s">%s</a></li>',
                        $tag['class'],
                        $this->engine->get('router')->generate('blog_show_tag', array('tagName' => $tag[0]->getSlug())),
                        $tag[0]->getName()
                    );
        }
        $html .= '</ul><br clear="all" />';

        return $html;
    }

    protected function sortTags($a, $b) {
        return ($a[0]->getSlug() >= $b[0]->getSlug()) ? 1 : -1;
    }

    public function getName()
    {
        return 'postsCloud';
    }
}
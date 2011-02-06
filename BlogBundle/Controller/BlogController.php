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

use Whitewashing\Blog\Comment;

class BlogController extends AbstractBlogController
{
    /**
     * @return \Whitewashing\Blog\PostRepository
     */
    public function getPostRepository()
    {
        return $this->container->get('doctrine.orm.default_entity_manager')->getRepository('Whitewashing\Blog\Post');
    }

    public function indexAction()
    {
        $postRepository = $this->getPostRepository();

        /* @var $response \Symfony\Component\HttpKernel\Response */
        $response = $this->createResponse();
        $response->setSharedMaxAge(600);

        return $this->render('WhitewashingBlogBundle:Blog:index.html.twig', array(
            'posts' => $postRepository->getCurrentPosts(5)
        ), $response);
    }

    public function viewAction($id)
    {
        $postRepository = $this->getPostRepository();
        $post = $postRepository->findPost($id);

        /* @var $response \Symfony\Component\HttpKernel\Response */
        $response = $this->createResponse();
        $response->setSharedMaxAge(600);

        return $this->render('WhitewashingBlogBundle:Blog:view.html.twig', array(
            'post' => $post,
        ), $response);
    }

    public function recentAction($count = 5)
    {
        $postRepository = $this->getPostRepository();
        $posts = $postRepository->getCurrentPosts($count);

        return $this->render('WhitewashingBlogBundle:Blog:recentPosts.html.twig', array('posts' => $posts));
    }

    public function cloudAction()
    {
        $postRepository = $this->getPostRepository();
        $tags = $postRepository->getPopularTags();

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

        return $this->render('WhitewashingBlogBundle:Blog:cloud.html.twig', array('tags' => $tags));
    }

    protected function sortTags($a, $b) {
        return ($a[0]->getSlug() >= $b[0]->getSlug()) ? 1 : -1;
    }

    public function tagAction($tagName)
    {
        $postService = $this->container->get('whitewashing.blog.postservice');

        $tag = $postService->getTag($tagName);

        /* @var $response \Symfony\Component\HttpKernel\Response */
        $response = $this->createResponse();
        $response->setSharedMaxAge(60 * 60 * 24);

        return $this->render('WhitewashingBlogBundle:Blog:tag.html.twig', array(
            'tag' => $tag,
            'posts' => $postService->getTaggedPosts($tag->getId()),
        ), $response);
    }

    public function listTagsAction()
    {
        /* @var $request \Symfony\Component\HttpKernel\Request */
        $request = $this->getRequest();
        $tagPattern = $request->query->get('term');

        /* @var $tagService Whitewashing\Blog\TagRepository */
        $tagService = $this->container->get('whitewashing.blog.tagservice');
        $tags = $tagService->matchingTags($tagPattern);

        /* @var $response \Symfony\Component\HttpKernel\Response */
        $response = $this->createResponse();
        $response->setContent(json_encode($tags));
        return $response;
    }
}


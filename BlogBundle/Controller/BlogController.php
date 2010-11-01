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

        return $this->render('BlogBundle:Blog:index', array(
            'posts' => $postRepository->getCurrentPosts(5)
        ), $response);
    }

    public function viewAction($id)
    {
        $postRepository = $this->getPostRepository();
        $post = $postRepository->findPost($id);
        
        $writeComment = new \Whitewashing\Blog\Form\WriteComment($post, $this->container->get('session'));
        $form = $writeComment->createForm($this->container->getValidatorService());

        /* @var $response \Symfony\Component\HttpKernel\Response */
        $response = $this->createResponse();
        $response->setSharedMaxAge(600);

        return $this->render('BlogBundle:Blog:view', array(
            'post' => $post,
            'comments' => $postRepository->getComments($id),
            'form' => $form,
            'writeComment' => $writeComment,
        ), $response);
    }

    public function commentAction($postId)
    {
        if ($this->getRequest()->getMethod() == 'POST') {
            $postRepository = $this->getPostRepository();

            $post = $postRepository->findPost($postId);

            $writeComment = new \Whitewashing\Blog\Form\WriteComment($post, $this->getUser());
            $form = $writeComment->createForm($this->container->getValidatorService());
            $form->bind($this->getRequest()->get('writecomment'));

            if ($form->isValid()) {
                $em = $this->container->get('doctrine.orm.default_entity_manager');
                $writeComment->process($em);

                return $this->redirect($this->generateUrl('blog_show_post', array('id' => $post->getId())), 302);
            }
        }
    }

    public function tagAction($tagName)
    {
        $postService = $this->container->get('whitewashing.blog.postservice');

        $tag = $postService->getTag($tagName);

        /* @var $response \Symfony\Component\HttpKernel\Response */
        $response = $this->createResponse();
        $response->setSharedMaxAge(60 * 60 * 24);

        return $this->render('BlogBundle:Blog:tag', array(
            'tag' => $tag,
            'posts' => $postService->getTaggedPosts($tag->getId()),
        ), $response);
    }
}


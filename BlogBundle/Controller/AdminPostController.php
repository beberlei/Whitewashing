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

use Symfony\Component\Security\SecurityContext;

class AdminPostController extends AbstractBlogController
{
    public function loginAction()
    {
        // get the error if any (works with forward and redirect -- see below)
        $request = $this->getRequest();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('BlogBundle:AdminPost:login.twig', array(
            'error' => $error,
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
        ));
    }

    public function indexAction()
    {
        return $this->render('BlogBundle:AdminPost:dashboard.twig', array(
            'user' => $this->container->get('security.context')->getUser()
        ));
    }

    public function manageAction()
    {
        return $this->render('BlogBundle:AdminPost:manage.twig', array(
            'posts' => $this->container->get('whitewashing.blog.postservice')->getCurrentPosts()
        ));
    }

    public function newAction()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $currentUser = $this->container->get('security.context')->getUser();

        $author = $this->container->get('whitewashing.blogbundle.accountservice')->findAuthorForUserAccount($currentUser);
        
        $blog = $this->container->get('whitewashing.blog.blogservice')->getCurrentBlog();
        $post = new \Whitewashing\Blog\Post($author, $blog);

        return $this->handleForm('BlogBundle:AdminPost:new.twig', $post, $em);
    }

    public function editAction()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $post = $this->container->get('whitewashing.blog.postservice')->findPost($this->getRequest()->get('id'));

        return $this->handleForm('BlogBundle:AdminPost:edit.twig', $post, $em);
    }

    /**
     * @param string $viewName
     * @param Post $post
     * @param EntityManager $em
     * @return \Symfony\Component\HttpKernel\Response
     */
    private function handleForm($viewName, $post, $em)
    {
        $writePost = new \Whitewashing\Blog\Form\WritePost($post);
        $form = $writePost->createForm($this->container->get('validator'));

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest()->get('writepost'));

            if ($form->isValid()) {
                $writePost->process($em);
                if ($this->getRequest()->get('submit_thenshow')) {
                    return $this->redirect($this->generateUrl('blog_show_post', array('id' => $post->getId())));
                } else {
                    return $this->redirect($this->generateUrl('blog_post_edit', array('id' => $post->getId())));
                }
            }
        }

        return $this->render($viewName, array(
            'writeForm' => $form,
            'post' => $post,
        ));
    }

    public function deleteAction()
    {
        $post = $this->container->get('whitewashing.blog.postservice')->findPost($this->getRequest()->get('id'));

        if ($this->getRequest()->getMethod() == 'POST') {
            $em = $this->container->get('doctrine.orm.default_entity_manager');
            $em->remove($post);
            $em->flush();

            return $this->render('BlogBundle:AdminPost:delete.twig', array('post' => $post));
        } else {
            return $this->render('BlogBundle:AdminPost:confirmDelete.twig', array('post' => $post));
        }
    }

    public function buildIndexAction()
    {
        $session = $this->container->get('zeta.search.session');
        $posts = $this->container->get('whitewashing.blog.postservice')->findAll();

        foreach ($posts AS $post) {
            $session->index( $post );
        }

        die();
    }
}
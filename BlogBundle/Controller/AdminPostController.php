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

class AdminPostController extends AbstractBlogController
{
    public function isLoggedIn()
    {
        /* @var $user Symfony\Framework\FoundationBundle\User */
        $user = $this->getUser();
        return ( $user->getAttribute('blogUser') != false);
    }

    public function createLoginRequiredResponse()
    {
        return $this->redirect($this->generateUrl('blog_admin_login'));
    }

    public function loginAction()
    {
        $error = false;
        if ($this->getRequest()->getMethod() == 'POST') {
            /* @var $userService Whitewashing\Core\UserService */
            $userService = $this->container->get('whitewashing.core.userservice');
            $blogUser = $userService->findUser($this->getRequest()->get('username'));
            if ($blogUser && $blogUser->areValidCredentials($this->getRequest()->get('password'))) {
                $user = $this->getUser();
                $user->setAttribute('blogUser', $blogUser);

                return $this->redirect($this->generateUrl('blog_admin_dashboard'));
            } else {
                $error = true;
            }
        }

        return $this->render('BlogBundle:AdminPost:login', array('error' => $error));
    }

    public function indexAction()
    {
        if (!$this->isLoggedIn()) {
            return $this->createLoginRequiredResponse();
        }
        
        return $this->render('BlogBundle:AdminPost:dashboard');
    }

    public function manageAction()
    {
        if (!$this->isLoggedIn()) {
            return $this->createLoginRequiredResponse();
        }

        return $this->render('BlogBundle:AdminPost:manage', array(
            'posts' => $this->container->get('whitewashing.blog.postservice')->getCurrentPosts()
        ));
    }

    public function newAction()
    {
        if (!$this->isLoggedIn()) {
            return $this->createLoginRequiredResponse();
        }

        $em = $this->container->get('doctrine.orm.default_entity_manager');

        // need a connected author to get this working
        $author = $this->getUser()->getAttribute('blogUser');
        $author = $em->merge($author);
        
        $blog = $this->container->get('whitewashing.blog.blogservice')->getCurrentBlog();
        $post = new \Whitewashing\Blog\Post($author, $blog);

        return $this->handleForm('BlogBundle:AdminPost:new', $post, $em);
    }

    public function editAction()
    {
        if (!$this->isLoggedIn()) {
            return $this->createLoginRequiredResponse();
        }

        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $post = $this->container->get('whitewashing.blog.postservice')->findPost($this->getRequest()->get('id'));

        return $this->handleForm('BlogBundle:AdminPost:edit', $post, $em);
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
        $form = $writePost->createForm($this->container->getValidatorService());

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest()->get('writepost'));

            if ($form->isValid()) {
                $writePost->process($em);
                if ($this->getRequest()->getParam('submit_thenshow')) {
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
        if (!$this->isLoggedIn()) {
            return $this->createLoginRequiredResponse();
        }

        $post = $this->container->get('whitewashing.blog.postservice')->findPost($this->getRequest()->get('id'));

        if ($this->getRequest()->getMethod() == 'POST') {
            $em = $this->container->get('doctrine.orm.default_entity_manager');
            $em->remove($post);
            $em->flush();

            return $this->render('BlogBundle:AdminPost:delete', array('post' => $post));
        } else {
            return $this->render('BlogBundle:AdminPost:confirmDelete', array('post' => $post));
        }
    }

    public function buildIndexAction()
    {
        if (!$this->isLoggedIn()) {
            return $this->createLoginRequiredResponse();
        }

        $session = $this->container->get('zeta.search.session');
        $posts = $this->container->get('whitewashing.blog.postservice')->findAll();

        foreach ($posts AS $post) {
            $session->index( $post );
        }

        die();
    }
}
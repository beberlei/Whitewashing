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
use Whitewashing\Blog\Author;
use Whitewashing\Blog\WritePostProcess;
use Whitewashing\BlogBundle\Form\PostType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Whitewashing\BlogBundle\Form\WritePostType;

class AdminPostController extends AbstractBlogController
{
    public function indexAction()
    {
        return $this->render('WhitewashingBlogBundle:AdminPost:dashboard.html.twig', array(
            'user' => $this->container->get('security.context')->getToken()
        ));
    }

    public function manageAction()
    {
        return $this->render('WhitewashingBlogBundle:AdminPost:manage.html.twig', array(
            'posts' => $this->container->get('whitewashing.blog.postservice')->findAll()
        ));
    }

    public function newAction()
    {        
        $securityToken = $this->container->get('security.context')->getToken();
        $author = null;
        if ($securityToken->getUser() instanceof UserInterface) {
            $username = $securityToken->getUser()->getUsername();
            $author = $this->container->get('whitewashing.blog.authorservice')->findAuthorForUserAccount($username);
        }
        
        if (!$author) {
            throw new AccessDeniedException;
        }
        
        $blog = $this->container->get('whitewashing.blog.blogservice')->getCurrentBlog();
        
        $post = new \Whitewashing\Blog\Post($author, $blog);

        return $this->handleForm('WhitewashingBlogBundle:AdminPost:new.html.twig', $post, true);
    }

    public function editAction($id)
    {
        $post = $this->container->get('whitewashing.blog.postservice')->findPost($id);

        return $this->handleForm('WhitewashingBlogBundle:AdminPost:edit.html.twig', $post, false);
    }

    /**
     * @param string $viewName
     * @param Post $post
     * @return \Symfony\Component\HttpKernel\Response
     */
    private function handleForm($viewName, $post, $allowChangeFormat)
    {
        $factory = $this->container->get('form.factory');

        $writePost = new WritePostProcess($post);
        $form = $factory->create(new WritePostType($allowChangeFormat), $writePost, array('allow_change_format' => $allowChangeFormat));

        if ($this->getRequest()->getMethod() == 'POST') {
            
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $em = $this->container->get('doctrine.orm.default_entity_manager');
                $post = $writePost->updatePost($em->getRepository('Whitewashing\Blog\Tag'));
                $em->persist($post);
                #$em->flush();

                if ($this->getRequest()->get('submit_thenshow')) {
                    return $this->redirect($this->generateUrl('blog_show_post', array('id' => $post->getId())));
                } else {
                    return $this->redirect($this->generateUrl('blog_post_edit', array('id' => $post->getId())));
                }
            }
        }

        return $this->render($viewName, array(
            'writeForm' => $form->createView(),
            'post' => $post,
        ));
    }

    public function deleteAction($id)
    {
        $post = $this->container->get('whitewashing.blog.postservice')->findPost($id);

        if ($this->getRequest()->getMethod() == 'POST') {
            $em = $this->container->get('doctrine.orm.default_entity_manager');
            $em->remove($post);
            $em->flush();

            return $this->render('WhitewashingBlogBundle:AdminPost:delete.html.twig', array('post' => $post));
        } else {
            return $this->render('WhitewashingBlogBundle:AdminPost:confirmDelete.html.twig', array('post' => $post));
        }
    }
}
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

class AdminPostController extends AbstractBlogController
{
    public function indexAction()
    {
        return $this->render('WhitewashingBlogBundle:AdminPost:dashboard.twig.html', array(
            'user' => $this->container->get('security.context')->getUser()
        ));
    }

    public function manageAction()
    {
        return $this->render('WhitewashingBlogBundle:AdminPost:manage.twig.html', array(
            'posts' => $this->container->get('whitewashing.blog.postservice')->getCurrentPosts()
        ));
    }

    public function newAction()
    {        
        $currentUser = $this->container->get('security.context')->getUser();
        if (!is_string($currentUser)) { // TODO: This allows anon users, should we?
            $currentUser = $currentUser->getUsername();
        }
        $author = $this->container->get('whitewashing.blog.authorservice')->findAuthorForUserAccount($currentUser);
        $blog = $this->container->get('whitewashing.blog.blogservice')->getCurrentBlog();
        
        $post = new \Whitewashing\Blog\Post($author, $blog);

        return $this->handleForm('WhitewashingBlogBundle:AdminPost:new.twig.html', $post);
    }

    public function editAction($id)
    {
        $post = $this->container->get('whitewashing.blog.postservice')->findPost($id);

        return $this->handleForm('WhitewashingBlogBundle:AdminPost:edit.twig.html', $post);
    }

    /**
     * @param string $viewName
     * @param Post $post
     * @return \Symfony\Component\HttpKernel\Response
     */
    private function handleForm($viewName, $post)
    {
        $builder = $this->container->get('whitewashing.blog.bundle.formbuilder');
        
        $writePost = new WritePostProcess($post);
        $form = $builder->createWritePostForm($writePost);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest()->get('writepost'));

            if ($form->isValid()) {
                $em = $this->container->get('doctrine.orm.default_entity_manager');
                $post = $writePost->updatePost($em->getRepository('Whitewashing\Blog\Tag'));
                $em->persist($post);
                $em->flush();

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

    public function deleteAction($id)
    {
        $post = $this->container->get('whitewashing.blog.postservice')->findPost($id);

        if ($this->getRequest()->getMethod() == 'POST') {
            $em = $this->container->get('doctrine.orm.default_entity_manager');
            $em->remove($post);
            $em->flush();

            return $this->render('WhitewashingBlogBundle:AdminPost:delete.twig.html', array('post' => $post));
        } else {
            return $this->render('WhitewashingBlogBundle:AdminPost:confirmDelete.twig.html', array('post' => $post));
        }
    }
}
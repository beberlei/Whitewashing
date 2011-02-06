<?php

namespace Whitewashing\BlogBundle\Controller;

class AuthorController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    public function listAction()
    {
        $authors = $this->container->get('whitewashing.blog.authorservice')->findAll();

        return $this->render('WhitewashingBlogBundle:Author:list.html.twig', array(
            'authors' => $authors,
        ));
    }

    public function userAction()
    {
        $request = $this->container->get('request');
        $searchTerm = $request->get('term');

        $users = array();
        if ($this->container->has('fos_user.user_manager')) {
            $userManager = $this->container->get('fos_user.user_manager');
            foreach ($userManager->findUsers() AS $user) {
                $username = $user->getUsername();
                if (strpos($username, $searchTerm) !== false || strpos($user->getEmail(), $searchTerm)) {
                    $users[$username] = array('label' => $username, 'value' => $username, 'email' => $user->getEmail());
                }
            }
        }

        if ($users) {
            $authorRepository = $this->container->get('whitewashing.blog.authorservice');
            $authors = $authorRepository->findAll();
            foreach ($authors AS $author) {
                unset($users[$author->getUsername()]);
            }
        }
        $users = array_values($users);

        /* @var $response \Symfony\Component\HttpKernel\Response */
        $response = $this->createResponse();
        $response->setContent(json_encode($users));
        return $response;
    }

    public function newAction()
    {
        $request = $this->container->get('request');
        $newAuthorForm = $this->container->get('whitewashing.blog.bundle.formbuilder')->createNewAuthorForm();

        if ($request->getMethod() == 'POST') {
            $author = new \Whitewashing\Blog\Author();
            $newAuthorForm->bind($request, $author);

            if ($newAuthorForm->isValid()) {
                $em = $this->container->get('doctrine.orm.default_entity_manager');
                $em->persist($author);
                $em->flush();

                return $this->redirect($this->generateUrl('blog_list_authors'));
            }
        }

        return $this->render('WhitewashingBlogBundle:Author:new.html.twig', array(
            'newAuthorForm' => $newAuthorForm
        ));
    }
}
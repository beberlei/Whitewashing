<?php

namespace Whitewashing\BlogBundle\Controller;

class AuthorController extends Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    public function listAction()
    {
        $authors = $this->container->get('whitewashing.blog.authorservice')->findAll();

        return $this->render('WhitewashingBlogBundle:Authort:list.twig.html', array(
            'authors' => $authors,
        ));
    }
}
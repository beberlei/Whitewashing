<?php

namespace Whitewashing\BlogBundle\Controller;

class CategoryController extends AbstractBlogController
{
    public function indexAction()
    {
        $repository = $this->container->get('whitewashing.blog.categoryservice');
        $categories = $repository->findBy(array(), array("name" => "ASC"));
        
        return $this->render("WhitewashingBlogBundle:Category:index.html.twig", array(
            'categories' => $categories,
        ));
    }
    
    public function listAction($slug)
    {
        $repository = $this->container->get('whitewashing.blog.categoryservice');
        $category = $repository->findOneBy(array("short" => $slug));
        
        $postRepository = $this->container->get('whitewashing.blog.postservice');
        $posts = $postRepository->getCategoryPosts($category->getId(), 10, 1);
        
        return $this->render("WhitewashingBlogBundle:Category:list.html.twig", array(
            'category' => $category,
            'posts'    => $posts,
        ));
    }
}
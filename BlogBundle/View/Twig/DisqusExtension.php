<?php

namespace Whitewashing\BlogBundle\View\Twig;

use Twig_Extension;
use Twig_Environment;
use Twig_Function_Method;

class DisqusExtension extends Twig_Extension
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    private $container;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $disqusShortname;

    public function __construct($router, $container, $disqusShortname)
    {
        $this->router = $router;
        $this->container = $container; // recursion o_O
        $this->disqusShortname = $disqusShortname;
    }
    
    public function getFunctions()
    {
        return array(
            'disqus_comments' => new Twig_Function_Method($this, 'comments', array('is_safe' => array('html'))),
            'disqus_head_comment_count' => new Twig_Function_Method($this, 'headCommentCount', array('is_safe' => array('html')))
        );
    }

    public function getName()
    {
        return 'disqus';
    }

    /**
     * @param object $object
     * @return string
     */
    public function comments($object, $route, $param = 'id')
    {
        if (!$this->disqusShortname) {
            return '';
        }

        $method = 'get' . $param;
        $id = $object->$method();

        return $this->container->get('templating')->render('WhitewashingBlogBundle:Disqus:comments.html.twig', array(
            'disqus_shortname' => $this->disqusShortname,
            'disqus_identifier' => $id,
            'disqus_url' => $this->router->generate($route, array($param => $id), true),
        ));
    }

    /**
     * @param object $object
     * @return string
     */
    public function headCommentCount()
    {
        if (!$this->disqusShortname) {
            return '';
        }

        return $this->container->get('templating')->render('WhitewashingBlogBundle:Disqus:count.html.twig', array(
            'disqus_shortname' => $this->disqusShortname,
        ));
    }
}
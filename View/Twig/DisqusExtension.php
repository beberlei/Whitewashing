<?php

namespace Whitewashing\View\Twig;

use Twig_Extension;
use Twig_Environment;
use Twig_Function_Method;

class DisqusExtension extends Twig_Extension
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    private $engine;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $disqusShortname;

    public function __construct($router, $disqusShortname)
    {
        $this->router = $router;
        $this->disqusShortname = $disqusShortname;
    }

    /**
     * Set the templating engine
     *
     * We cant do it in the constructor, because that would create an infinite loop on construction from the DIC.
     *
     * @param <type> $engine
     */
    public function setTemplatingEngine($engine)
    {
        $this->engine = $engine;
    }

    public function getFunctions()
    {
        return array(
            'disqus_comments' => new Twig_Function_Method($this, 'comments', array('is_safe' => array('html'))),
            'disqus_comment_count' => new Twig_Function_Method($this, 'commentCount', array('is_safe' => array('html')))
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
        $method = 'get' . $param;
        $id = $object->$method();

        return $this->engine->render('BlogBundle:Disqus:comments.twig.html', array(
            'disqus_shortname' => $this->disqusShortname,
            'disqus_identifier' => $id,
            'disqus_url' => $this->router->generate($route, array($param => $id), true),
        ));
    }

    /**
     * @param object $object
     * @return string
     */
    public function commentCount($object, $route, $param = 'id')
    {
        return 0;
    }
}
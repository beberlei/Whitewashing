<?php

namespace Whitewashing\View\Twig;

use Twig_Extension;
use Twig_Filter_Method;
use Whitewashing\BlogBundle\Disqusable;

class DisqusExtension extends Twig_Extension
{
    private $engine;
    private $router;
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

    public function getFilters()
    {
        return array(
            'disqus_comments' => new Twig_Filter_Method($this, 'comments', array('is_safe' => array('html'))),
            'disqus_comment_count' => new Twig_Filter_Method($this, 'commentCount', array('is_safe' => array('html'))),
        );
    }

    public function getName()
    {
        return 'disqus';
    }

    /**
     * @param Disqusable $object
     * @return string
     */
    public function comments(Disqusable $object)
    {
        return $this->engine->render('BlogBundle:Disqus:comments.twig.html', array(
            'disqus_shortname' => $this->disqusShortname,
            'disqus_identifier' => $object->getDisqusId(),
            'disqus_url' => $object->getDisqusUrl($this->router),
        ));
    }

    /**
     * @param Disqusable $object
     * @return string
     */
    public function commentCount($object)
    {
        
    }
}
<?php

namespace Whitewashing\View\Twig;

use Twig_Extension;
use Twig_Filter_Method;

class DisqusExtension extends Twig_Extension
{
    private $engine;
    private $disqusShortname;

    public function __construct($engine, $router, $disqusShortname)
    {
        $this->engine = $engine;
        $this->router = $router;
        $this->disqusShortname = $disqusShortname;
    }

    public function getFilters()
    {
        return array(
            'disqus_comments' => new Twig_Filter_Method($this, 'comments'),
            'disqus_comment_count' => new Twig_Filter_Method($this, 'commentCount'),
        );
    }

    public function getName()
    {
        return 'disqus';
    }

    public function comments($object)
    {
        return $this->engine->render('BlogBundle:Disqus:comments.twig', array(
            'disqus_shortname' => $this->disqusShortname,
            'disqus_identifier' => $object->getDisqusId(),
            'disqus_url' => $object->getDisqusUrl($this->router),
        ));
    }

    public function commentCount($object)
    {
        
    }
}
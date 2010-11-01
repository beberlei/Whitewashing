<?php
/*
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

namespace Whitewashing\Blog;

use Doctrine\ORM\EntityManager;

class PostService
{
    private $postRepository;

    public function __construct(EntityManager $em)
    {
        $this->postRepository = $em->getRepository('Whitewashing\Blog\Post');
    }

    public function __call($method, $args)
    {
        return \call_user_func_array(array($this->postRepository, $method), $args);
    }
}
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

namespace Whitewashing\Tests;

use Whitewashing\Blog\Author;
use Whitewashing\Blog\Post;
use Whitewashing\Blog\Blog;

class TestCase extends \PHPUnit_Framework_TestCase
{
    private $atCounter = 0;

    public function next()
    {
        return $this->at($this->atCounter++);
    }

    public function getCleanMock($class)
    {
        if (!class_exists($class, true)) {
            $this->fail('Class to be mocked '.$class.' does not exist!');
        }

        $r = new \ReflectionClass($class);
        $methodNames = array();
        foreach ($r->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_ABSTRACT) AS $method) {
            $methodNames[] = $method->getName();
        }

        return $this->getMock($class, $methodNames, array(), '', false);
    }

    public function createPost()
    {
        return new Post($this->fakeUser(), new Blog('Whitewashing.de'));
    }

    public function fakeUser()
    {
        return Author::create('Benjamin Eberlei', 'beberlei', 'kontakt@beberlei.de');
    }
}
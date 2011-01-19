<?php

namespace Whitewashing\Blog\Tests\Blog;

use Whitewashing\Blog\Blog;

class BlogTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateBlogCreatesUnassignedCategory()
    {
        $blog = new Blog('Foo');

        $this->assertEquals('Foo', $blog->getName());
        $this->assertEquals('Unassigned', $blog->getDefaultCategory()->getName());
    }

    public function testGetCategories()
    {
        $blog = new Blog('Foo');

        $categories = $blog->getCategories();
        $this->assertEquals(1, count($categories));
        $this->assertEquals('Unassigned', $categories[0]->getName());
    }
}
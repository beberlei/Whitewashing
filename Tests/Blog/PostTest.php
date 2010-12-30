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

namespace Whitewashing\Tests\Blog;

use Whitewashing\Tests\TestCase,
    Whitewashing\Blog\Author,
    Whitewashing\Blog\Post,
    Whitewashing\Blog\Blog,
    Whitewashing\Blog\Category,
    Whitewashing\Blog\Tag;

class PostTest extends TestCase
{
    public function testCreate()
    {
        $blog = $this->createBlog();
        $author = $this->fakeUser();

        $post = new Post($author, $blog);
        $this->assertSame($author, $post->getAuthor());
        $this->assertSame($blog, $post->getBlog());
        $this->assertType('Whitewashing\DateTime\DateTime', $post->created());
    }

    public function testCreateWithoutCategories_UsesBlogsDefaultCategory()
    {
        $blog = $this->createBlog();
        $author = $this->fakeUser();

        $post = new Post($author, $blog);

        $categories = $post->getCategories();
        $this->assertEquals(1, count($categories));
        $this->assertSame(array($blog->getDefaultCategory()), $categories->toArray());
    }

    public function testAddTags()
    {
        $blog = $this->createBlog();
        $author = $this->fakeUser();

        $post = new Post($author, $blog);

        $post->addTag(new Tag('Foo'));
        $post->addTag(new Tag('Bar'));

        $this->assertEquals(2, count($post->getTags()));
    }

    public function testRemoveTag()
    {
        $blog = $this->createBlog();
        $author = $this->fakeUser();

        $post = new Post($author, $blog);

        $foo = new Tag('Foo');
        $post->addTag($foo);
        $post->removeTag($foo);

        $this->assertEquals(0, count($post->getTags()));
    }

    /**
     * @return Blog
     */
    private function createBlog()
    {
        $blog = new Blog();
        $blog->createCategory('Unassigned');

        return $blog;
    }
}
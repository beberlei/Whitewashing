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
    Whitewashing\Core\User,
    Whitewashing\Blog\PostService,
    Whitewashing\Blog\Blog,
    Whitewashing\Blog\Category;

class PostRepositoryTest extends \Whitewashing\Tests\FunctionalTestCase
{
    /**
     * @var PostRepository
     */
    private $postRepository = null;

    public function getDataSet()
    {
        return $this->createFlatXmlDataSet(__DIR__ . '/_files/post-repository-fixture.xml');
    }

    public function setUp()
    {
        parent::setUp();
        $this->postRepository = $this->getEntityManager()->getRepository('Whitewashing\Blog\Post');
        $this->getEntityManager()->getRepository('Whitewashing\Blog\Blog')->setCurrentBlogId(1);
    }

    /**
     * @return Whitewashing\Core\User
     */
    public function getAdminUser()
    {
        return $this->getEntityManager()->find('Whitewashing\Core\User', 1);
    }

    public function testCreatePostInDefaultBlogAndCategory()
    {
        $author = $this->getAdminUser();

        $post = $this->postRepository->create($author, 'Foo', 'Bar');

        $this->assertType('Whitewashing\Blog\Post', $post);
        $this->assertSame($author, $post->getAuthor());
        $this->assertType('Whitewashing\Blog\Blog', $post->getBlog());
        $this->assertContainsOnly('Whitewashing\Blog\Category', $post->getCategories());
    }

    public function testCreatePost_IsDraft()
    {
        $author = $this->getAdminUser();

        $post = $this->postRepository->create($author, 'Foo', 'Bar');

        $this->assertTrue( $post->isDraft() );
        $this->assertFalse( $post->isPublished() );
    }

    public function testGetCurrentPosts_WithDefaultBlog()
    {
        $this->postRepository->getCurrentPosts();
    }

    public function testGetCurrentPosts_FromAnyBlog()
    {
        $this->postRepository->getCurrentPosts(2);
    }
}
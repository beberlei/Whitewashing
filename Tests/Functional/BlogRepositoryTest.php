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
    Whitewashing\Blog\User,
    Whitewashing\Blog\BlogService,
    Whitewashing\Blog\Blog;

class BlogRepositoryTest extends \Whitewashing\Tests\FunctionalTestCase
{
    /**
     * @var \Whitewashing\Blog\BlogRepository
     */
    private $blogRepository = null;

    public function getDataSet()
    {
        return $this->createFlatXmlDataSet(__DIR__ . '/_files/blogInitialFixture.xml');
    }

    public function setUp()
    {
        parent::setUp();
        $this->blogRepository = $this->getEntityManager()->getRepository('Whitewashing\Blog\Blog');
    }

    public function testGetNumberOfPosts()
    {
        $this->assertEquals(0, $this->blogRepository->getNumberOfBlogs());
    }

    public function testCreateBlog_SetName_AndCreateUnassignedCategory()
    {
        $blog = $this->blogRepository->createBlog('Foo');

        $this->assertEquals('Foo', $blog->getName());
        $this->assertEquals('Unassigned', $blog->getDefaultCategory()->getName());
    }
}
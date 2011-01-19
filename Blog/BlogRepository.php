<?php
/**
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

use Doctrine\ORM\EntityRepository;

class BlogRepository extends EntityRepository
{
    /**
     * @var int
     */
    private $currentBlogId;

    public function getCurrentBlogId()
    {
        if (!$this->currentBlogId) {
            throw new \Exception("No blog is currently set as active.");
        }

        return $this->currentBlogId;
    }

    public function setCurrentBlogId($currentBlogId)
    {
        $this->currentBlogId = $currentBlogId;
    }

    /**
     * @return Blog
     */
    public function getCurrentBlog()
    {
        return $this->find($this->getCurrentBlogId());
    }

    /**
     * @param string $name
     * @return Whitewashing\Blog\Blog
     */
    public function createBlog($name)
    {
        $blog = new Blog($name, 'Unassigned');

        $this->_em->persist($blog);
        $this->_em->flush();

        $this->setCurrentBlogId($blog->getId());

        return $blog;
    }

    /**
     * @return int
     */
    public function getNumberOfBlogs()
    {
        return $this->_em->createQuery('SELECT COUNT(b.id) FROM Whitewashing\Blog\Blog b')
                         ->getSingleScalarResult();
    }
}
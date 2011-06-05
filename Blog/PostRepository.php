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

use Doctrine\ORM\EntityManager;
use Whitewashing\Util\String;

class PostRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get all the current posts of the current blog.
     *
     * @param int $num
     * @return array
     */
    public function getCurrentPosts($num = 10)
    {
        $blogId = $this->getEntityManager()->getRepository('Whitewashing\Blog\Blog')->getCurrentBlogId();

        $dql = 'SELECT p, a, b FROM Whitewashing\Blog\Post p ' .
               'JOIN p.author a JOIN p.blog b '.
               'WHERE b.id = ?1 AND p.published = 1 ORDER BY p.created DESC';
        
        return $this->getEntityManager()->createQuery($dql)
            ->setParameter(1, $blogId)
            ->setMaxResults($num)
            ->getResult();
    }

    /**
     * @param  int $categoryId
     * @return array
     */
    public function getCategoryPosts($categoryId, $num = 10, $page = 1)
    {
        $blogId = $this->getEntityManager()->getRepository('Whitewashing\Blog\Blog')->getCurrentBlogId();
        
        $dql = 'SELECT p FROM Whitewashing\Blog\Post p ' .
               'JOIN p.blog b JOIN p.categories c '.
               'WHERE b.id = ?1 AND c.id = ?2 AND p.published = 1 ORDER BY p.created DESC';

        return $this->getEntityManager()->createQuery($dql)
             ->setParameter(1, $blogId)
             ->setParameter(2, $categoryId)
             ->setFirstResult( ($page - 1) * $num )
             ->setMaxResults($num)
             ->getResult();
    }

    /**
     * @param  int $postId
     * @return Whitewashing\Blog\Post
     */
    public function findPost($postId)
    {
        $post = $this->find($postId);
        if (!$post) {
            throw new \InvalidArgumentException("Blog Post with ID " . $postId . " does not exist!");
        }
        return $post;
    }

    /**
     *
     * @param User $author
     * @param string $headline
     * @param string $text
     * @param Blog $blog
     * @return Post
     */
    public function create(Author $author, $headline, $text, Blog $blog = null)
    {
        $blog = $this->getEntityManager()->getRepository('Whitewashing\Blog\Blog')->getCurrentBlog();

        $post = new Post($author, $blog);
        $post->setHeadline($headline);
        $post->setText($text);

        $em = $this->getEntityManager();

        $em->persist($post);
        $em->flush();

        return $post;
    }

    /**
     * @return Whitewashing\Blog\Tag[]
     */
    public function getPopularTags($num = 30)
    {
        $blogId = $this->getEntityManager()->getRepository('Whitewashing\Blog\Blog')->getCurrentBlogId();

        $dql = 'SELECT t, count(p.id) AS posts FROM Whitewashing\Blog\Tag t ' .
               'JOIN t.posts p JOIN p.blog b '.
               'WHERE b.id = ?1 AND p.published = 1 GROUP BY t.slug '.
               'ORDER BY posts DESC';

        return $this->getEntityManager()->createQuery($dql)
             ->setParameter(1, $blogId)
             ->setMaxResults($num)
             ->getResult();
    }

    /**
     * @param  string $slug
     * @return Tag
     */
    public function getTag($slug)
    {
        $tag = $this->getEntityManager()
            ->getRepository('Whitewashing\Blog\Tag')
            ->findOneBy(array('slug' => $slug));

        if (!$tag) {
            throw BlogException::unknownTag($slug);
        }

        return $tag;
    }

    public function getTaggedPosts($tagId)
    {
        $blogId = $this->getEntityManager()->getRepository('Whitewashing\Blog\Blog')->getCurrentBlogId();

        $dql = 'SELECT DISTINCT p FROM Whitewashing\Blog\Post p '.
               'JOIN p.tags t WHERE t.id = ?1 AND p.blog = ?2 AND p.published = 1 ORDER BY p.created DESC';

        return $this->getEntityManager()->createQuery($dql)
             ->setParameter(1, $tagId)
             ->setParameter(2, $blogId)
             ->setMaxResults(5)
             ->getResult();
    }
}
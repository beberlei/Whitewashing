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

class FeedService
{
    /**
     *
     * @var EntityManager
     */
    private $em;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(EntityManager $em, UrlGenerator $urlGenerator)
    {
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return Whitewashing\Blog\PostRepository
     */
    private function postRepository()
    {
        return $this->em->getRepository('Whitewashing\Blog\Post');
    }

    public function createLatestFeed()
    {
        $posts = $this->postRepository()->getCurrentPosts();

        $builder = $this->generateFeed("Latest");
        $builder->addPosts($posts);
        
        return $builder->createAtomFeed();
    }

    public function createCategoryFeed($categoryShort = null)
    {
        /* @var $category Whitewashing\Blog\Category */
        $category = $this->em->getRepository('Whitewashing\Blog\Category')->findOneBy(array('short' => $categoryShort));
        $posts = $this->postRepository()->getCategoryPosts($category->getId());

        $builder = $this->generateFeed("Category: ". $category->getName());
        $builder->addPosts($posts);
        
        return $builder->createAtomFeed();
    }

    public function createTagFeed($tagSlug)
    {
        $tag = $this->postRepository()->getTag($tagSlug);
        $posts = $this->postRepository()->getTaggedPosts($tag->getId());

        $builder = $this->generateFeed("Tag: " . $tag->getName());
        $builder->addPosts($posts);

        return $builder->createAtomFeed();
    }

    /**
     * Generate the base feed
     *
     * @param  string $titleSuffix
     * @return FeedBuilder
     */
    protected function generateFeed($titleSuffix = "")
    {
        $blog = $this->em->getRepository('Whitewashing\Blog\Blog')->getCurrentBlog();

        $builder = new FeedBuilder($blog, $this->urlGenerator);
        $builder->setTitleSuffix($titleSuffix);

        return $builder;
    }
}
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

        $feed = $this->generateFeed("Latest");
        $this->attachPosts($feed, $posts);
        
        return $feed;
    }

    public function createCategoryFeed($categoryShort = null)
    {
        /* @var $category Whitewashing\Blog\Category */
        $category = $this->em->getRepository('Whitewashing\Blog\Category')->findOneBy(array('short' => $categoryShort));

        $posts = $this->postRepository()->getCategoryPosts($category->getId());

        $feed = $this->generateFeed("Category: ". $category->getName());
        $this->attachPosts($feed, $posts);
        
        return $feed;
    }

    public function createTagFeed($tagSlug)
    {
        $tag = $this->postRepository()->getTag($tagSlug);
        $posts = $this->postRepository()->getTaggedPosts($tag->getId());

        $feed = $this->generateFeed("Tag: " . $tag->getName());
        $this->attachPosts($feed, $posts);

        return $feed;
    }

    /**
     * Generate the base feed
     *
     * @param  int $blogId
     * @return \Zend_Feed_Writer_Feed
     */
    protected function generateFeed($suffix = "")
    {
        $blog = $this->em->getRepository('Whitewashing\Blog\Blog')->getCurrentBlog();

        $feed = new \Zend_Feed_Writer_Feed;
        $feed->setTitle($blog->getName() . " - " . $suffix);
        $feed->setLink($this->urlGenerator->generateMainUrl());
        $feed->setFeedLink($this->urlGenerator->generateFeedUrl(), 'atom');
        $feed->addAuthor(array(
            'name'  => 'Paddy',
            'email' => 'paddy@example.com',
            'uri'   => 'http://www.example.com',
        ));

        return $feed;
    }

    /**
     * @param \Zend_Feed_Writer_Feed $feed
     * @param array $posts
     */
    protected function attachPosts($feed, $posts)
    {
        $maxDate = 0;
        foreach ($posts AS $post) {
            /* @var $post Post */
            $maxDate = max($maxDate, $post->created()->format('U'));
            $post->publishToFeed($feed, $this->urlGenerator);
        }

        $feed->setDateModified($maxDate);
    }
}
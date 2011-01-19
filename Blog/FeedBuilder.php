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

class FeedBuilder
{
    private $blog;
    private $urlGenerator;
    private $titleSuffix;
    private $posts = array();

    function __construct(Blog $blog, UrlGenerator $urlGenerator)
    {
        $this->blog = $blog;
        $this->urlGenerator = $urlGenerator;
    }

    public function setTitleSuffix($titleSuffix)
    {
        $this->titleSuffix = $titleSuffix;
    }

    public function addPosts($posts)
    {
        foreach ($posts AS $post) {
            $this->posts[] = $post;
        }
    }

    public function createAtomFeed()
    {
        $feed = new \Zend_Feed_Writer_Feed();
        if ($this->titleSuffix) {
            $feed->setTitle($this->blog->getName() . " - " . $this->titleSuffix);
        } else {
            $feed->setTitle($this->blog->getName());
        }

        $feed->setLink($this->urlGenerator->generateMainUrl());
        $feed->setFeedLink($this->urlGenerator->generateFeedUrl(), 'atom');

        $this->attachPosts($feed);

        return $feed->export('atom');
    }

    protected function attachPosts($feed)
    {
        if ($this->posts) {
            $maxDate = 0;
            foreach ($this->posts AS $post) {
                /* @var $post Post */
                $maxDate = max($maxDate, $post->created()->format('U'));

                $entry = $feed->createEntry();
                $this->attachPost($entry, $post);
                $feed->addEntry($entry);
            }

            $feed->setDateModified($maxDate);
        } else {
            $feed->setDateModified(time());
        }
    }

    protected function attachPost($entry, Post $post)
    {
        $author = $post->getAuthor();

        $link = $this->urlGenerator->generatePostUrl($post);
        $entry->setId($link);
        $entry->setTitle($post->getHeadline());
        $entry->setLink($link);
        $entry->addAuthor(array(
            'name'  => $author->getName(),
            'email' => $author->getEmail(),
            'uri'   => 'http://www.example.com',
        ));

        $entry->setDateModified($post->created()->format('U'));
        $entry->setDateCreated($post->created()->format('U'));
        $entry->setDescription($post->getHeadline());
        $entry->setContent($post->getText());
    }
}
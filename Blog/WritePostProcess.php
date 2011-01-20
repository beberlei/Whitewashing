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

use Doctrine\ORM\EntityManager;

use Whitewashing\Blog\Post;
use Whitewashing\Blog\ITagRepository;

class WritePostProcess
{
    /**
     * @var Post
     */
    private $post;

    /**
     * @var string
     */
    private $tags = "";

    /**
     * @var string
     */
    private $status = Post::STATUS_DRAFT;

    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->tags = $post->getTagNames();
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getPostId()
    {
        return $this->post->getId();
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function setPublishStatus($status)
    {
        $this->status = $status;
    }

    public function getPublishStatus()
    {
        return $this->post->isPublished() ? Post::STATUS_PUBLISHED : Post::STATUS_DRAFT;
    }

    /**
     * @return string[]
     */
    private function getParsedTagNames()
    {
        return array_filter(array_map('trim', explode(",", $this->tags)), 'strlen');
    }

    /**
     * Synchronize the written post to the actual post object and return it.
     * 
     * @param ITagRepository $tagRepository
     * @return Post
     */
    public function updatePost(ITagRepository $tagRepository)
    {
        if ($this->status == Post::STATUS_PUBLISHED) {
            $this->post->setPublished();
        }

        $this->post->updateTags($tagRepository->getTags($this->getParsedTagNames()));

        return $this->post;
    }
}
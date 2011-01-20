<?php

namespace Whitewashing\Blog\Tests\Blog;

use Whitewashing\Blog\WritePostProcess;
use Whitewashing\Blog\Tag;
use Whitewashing\Blog\Post;

class WritePostProcessTest extends \Whitewashing\Tests\TestCase
{
    private $post;
    private $writePostProcess;

    public function setUp()
    {
        $this->post = $this->createPost();
        $this->writePostProcess = new WritePostProcess($this->post);
    }

    public function testAddTags()
    {
        $fooTag = new Tag('Foo');
        $barTag = new Tag('Bar');
        
        $tagRepository = $this->getMock('Whitewashing\Blog\ITagRepository');
        $tagRepository->expects($this->once())
                      ->method('getTags')
                      ->with($this->equalTo(array('Foo', 'Bar')))
                      ->will($this->returnValue(array($fooTag, $barTag)));

        $this->writePostProcess->setTags("Foo, Bar");
        $post = $this->writePostProcess->updatePost($tagRepository);

        $this->assertEquals(2, count($post->getTags()));
        $this->assertContains($fooTag, $post->getTags());
        $this->assertContains($barTag, $post->getTags());
    }

    public function testRemoveTags()
    {
        $fooTag = new Tag('Foo');
        $barTag = new Tag('Bar');
        
        $this->post->addTag($fooTag);
        $this->post->addTag($barTag);

        $tagRepository = $this->getMock('Whitewashing\Blog\ITagRepository');
        $tagRepository->expects($this->once())
                      ->method('getTags')
                      ->with($this->equalTo(array()))
                      ->will($this->returnValue(array()));

        $this->writePostProcess->setTags("");
        $post = $this->writePostProcess->updatePost($tagRepository);

        $this->assertEquals(0, count($post->getTags()));
        $this->assertNotContains($fooTag, $post->getTags());
        $this->assertNotContains($barTag, $post->getTags());
    }

    public function testSynchronizeTags()
    {
        $fooTag = new Tag('Foo');
        $barTag = new Tag('Bar');

        $this->post->addTag($fooTag);

        $tagRepository = $this->getMock('Whitewashing\Blog\ITagRepository');
        $tagRepository->expects($this->once())
                      ->method('getTags')
                      ->with($this->equalTo(array("Bar")))
                      ->will($this->returnValue(array($barTag)));

        $this->writePostProcess->setTags("Bar");
        $post = $this->writePostProcess->updatePost($tagRepository);

        $this->assertEquals(1, count($post->getTags()));
        $this->assertNotContains($fooTag, $post->getTags());
        $this->assertContains($barTag, $post->getTags());
    }

    public function testPublishStatusDraft()
    {
        $tagRepository = $this->getMock('Whitewashing\Blog\ITagRepository');
        $tagRepository->expects($this->once())
                      ->method('getTags')
                      ->will($this->returnValue(array()));

        $post = $this->writePostProcess->updatePost($tagRepository);

        $this->assertTrue($post->isDraft());
    }

    public function testPublishStatusPublished()
    {
        $tagRepository = $this->getMock('Whitewashing\Blog\ITagRepository');
        $tagRepository->expects($this->once())
                      ->method('getTags')
                      ->will($this->returnValue(array()));

        $this->writePostProcess->setPublishStatus(Post::STATUS_PUBLISHED);
        $post = $this->writePostProcess->updatePost($tagRepository);

        $this->assertTrue($post->isPublished());
    }
}
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

use Whitewashing\Blog\Author;
use Whitewashing\Blog\Blog;
use Whitewashing\Blog\FeedBuilder;

class FeedBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFeed()
    {
        $blog = new Blog('Whitewashing.de');
        $urlGenerator = $this->createBasicUrlGeneratorMock();
        
        $builder = new FeedBuilder($blog, $urlGenerator);
        $feed = $builder->createAtomFeed();

        $this->assertType('string', $feed);
        $this->assertAtomFeedContains($feed, '//atom:title', 'Whitewashing.de');
    }

    public function testFeedWithTitleSuffix()
    {
        $blog = new Blog('Whitewashing.de');
        $urlGenerator = $this->createBasicUrlGeneratorMock();

        $builder = new FeedBuilder($blog, $urlGenerator);
        $builder->setTitleSuffix("Test");
        $feed = $builder->createAtomFeed();

        $this->assertAtomFeedContains($feed, '//atom:title', 'Whitewashing.de - Test');
    }

    public function testFeedWithPost()
    {
        $blog = new Blog('Whitewashing.de');
        $urlGenerator = $this->createBasicUrlGeneratorMock();

        $author = Author::create('Benjamin Eberlei', 'beberlei', 'kontakt@beberlei.de');
        $post = new \Whitewashing\Blog\Post($author, $blog);
        $post->setHeadline('Blog Post!');
        $post->setText('Foo?');

        $urlGenerator->expects($this->at(2))
                     ->method('generatePostUrl')
                     ->will($this->returnValue('http://www.whitewashing.de/blog/post/foo'));

        $builder = new FeedBuilder($blog, $urlGenerator);
        $builder->setTitleSuffix("Test");
        $builder->addPosts(array($post));

        $feed = $builder->createAtomFeed();

        $this->assertAtomFeedContains($feed, '//atom:entry/atom:title', 'Blog Post!');
    }

    public function createBasicUrlGeneratorMock()
    {
        $urlGenerator = $this->getMock('Whitewashing\Blog\UrlGenerator', array(), array(), '', false);
        $urlGenerator->expects($this->at(0))
                     ->method('generateMainUrl')
                     ->will($this->returnValue('http://www.whitewashing.de/feed.atom'));
        $urlGenerator->expects($this->at(1))
                     ->method('generateFeedUrl')
                     ->will($this->returnValue('http://www.whitewashing.de/feed.atom'));

        return $urlGenerator;
    }

    public function assertAtomFeedContains($xmlString, $xpathExpression, $text)
    {
        $dom = new \DOMDocument("1.0", 'UTF-8');
        $dom->loadXml($xmlString);

        $xpath = new \DOMXpath($dom);
        $xpath->registerNamespace('atom', 'http://www.w3.org/2005/Atom');
        $node = $xpath->evaluate($xpathExpression)->item(0);
        if ($node instanceof \DOMText) {
            $this->assertEquals($text, $node->wholeText);
        } else if ($node instanceof \DOMElement) {
            $this->assertEquals($text, $node->nodeValue);
        } else if ($node instanceof \DOMCharacterData) {
            $this->assertEquals($text, $node->data);
        } else {
            $this->fail("No DOMElement or DOMText node returned from xpath '" . $xpathExpression ."'.");
        }
    }
}
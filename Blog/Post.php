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

use Whitewashing\DateTime\DateFactory;
use Doctrine\Common\Collections\ArrayCollection;

class Post
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $headline = '';

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var string
     */
    private $inputFormat = 'rst';

    /**
     * @var string
     */
    private $formattedText = '';

    /**
     * @var Blog
     */
    private $blog;

    /**
     * @var Whitewashing\DateTime\DateTime
     */
    private $created;

    /**
     *
     * @var Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * @var Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * @var int
     */
    private $published = self::STATUS_DRAFT;

    public function __construct(Author $author, Blog $blog)
    {
        $this->author = $author;
        $this->blog = $blog;
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();

        $this->created = DateFactory::now();
        $this->addCategory($blog->getDefaultCategory());
    }

    /**
     * @var Whitewashing\Blog\Author
     */
    private $author;

    public function getId()
    {
        return $this->id;
    }

    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    public function setText($text)
    {
        $this->text = $text;
        $this->generateFormattedText();
    }

    public function setInputFormat($format)
    {
        $this->inputFormat = $format;
    }

    public function getInputFormat()
    {
        return $this->inputFormat;
    }

    public function getHeadline()
    {
        return $this->headline;
    }

    public function getText()
    {
        return $this->text;
    }

    /**
     * Run tidy, then Geshi to format the code blocks.
     * 
     * @return string
     */
    public function getFormattedText()
    {
        return $this->formattedText;
    }

    private function generateFormattedText()
    {
        switch ($this->inputFormat) {
            case 'rst':
                $document = new \ezcDocumentRst();
                $document->registerDirective( 'code-block', 'Whitewashing\Util\DocumentVisitor\CodeBlockRstDirective' );
                $document->options->xhtmlVisitor = "Whitewashing\Util\DocumentVisitor\BlogPostRstXhtmlVisitor";
                $document->options->xhtmlVisitorOptions = new \ezcDocumentHtmlConverterOptions(array("styleSheet" => ""));
                $document->loadString($this->text);

                $xhtml = $document->getAsXhtml();
                $body = $xhtml->save();

                break;
            case 'html':
            default:
                $body = '<div>'.$this->getText().'</div>';
                $tidy_config = array(
                    'clean' => true,
                    'output-xhtml' => true,
                    'show-body-only' => true,
                    'wrap' => 0,
                );

                $tidy = tidy_parse_string($body, $tidy_config);
                $tidy->cleanRepair();
                $body = (string)$tidy;
                
                $dom = new \DOMDocument('ISO-8859-1');
                $dom->loadHtml($body);

                $xpath = new \DOMXpath($dom);
                $nodes = $xpath->query('//blockquote/pre');

                $codeParts = array();
                foreach ($nodes AS $node) {
                    $geshi = new \GeSHi(trim($node->textContent), "php");
                    $geshi->enable_classes();
                    $geshi->set_header_type(GESHI_HEADER_PRE);
                    $codePart = $geshi->parse_code();

                    foreach ($node->childNodes AS $child) {
                        $node->removeChild($child);
                    }

                    $placeholder = "##CODEPART".count($codeParts)."##";
                    $codeParts[$placeholder] = array($node->parentNode->parentNode, $node->parentNode, $codePart);
                }

                foreach ($codeParts AS $placeholder => $e) {
                    list($parent, $old, $codePart) = $e;

                    $code = $dom->createElement('div');

                    $textNode = $dom->createTextNode($placeholder);
                    $code->appendChild($textNode);
                    $parent->replaceChild($code, $old);
                }

                $body = $dom->saveHtml();

                foreach ($codeParts AS $placeholder => $e) {
                    list($parent, $old, $codePart) = $e;
                    $body = str_replace($placeholder, $codePart, $body);
                }

                $body = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $body);
                $body = str_replace('<html><body><div>', '', $body);
                $body = str_replace('</div></body></html>', '', $body);
                $body = trim($body);
                break;
        }


        $this->formattedText = $body;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function addCategory(Category $category) {
        $this->categories[] = $category;
    }

    public function removeCategory(Category $category) {
        $this->categories->removeElement($category);
    }

    public function getCategories() {
        return $this->categories;
    }

    public function updateTags(array $newTags)
    {
        foreach ($this->tags AS $oldTag) {
            if (!in_array($oldTag, $newTags)) {
                $this->removeTag($oldTag);
            }
        }
        foreach ($newTags AS $tag) {
            $this->addTag($tag);
        }
    }

    public function addTag(Tag $tag) {
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags[] = $tag;
    }

    public function removeTag(Tag $tag) {
        $this->tags->removeElement($tag);
    }

    public function getTags() {
        return $this->tags->toArray();
    }

    /**
     * Return an array of tag names.
     * 
     * @return string
     */
    public function getTagNames()
    {
        return implode(", ", array_map(function($tag) { return $tag->getName(); }, $this->getTags()));
    }

    /**
     * @return Blog
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param Blog $blog
     */
    private function setBlog(Blog $blog) {
        $this->blog = $blog;
    }

    /**
     * Is Draft?
     * 
     * @return bool
     */
    public function isDraft()
    {
        return ($this->published == self::STATUS_DRAFT);
    }

    /**
     * Is Published?
     *
     * @return bool
     */
    public function isPublished()
    {
        return ($this->published == self::STATUS_PUBLISHED);
    }

    /**
     * Set Status to published.
     *
     * @return void
     */
    public function publish()
    {
        $this->published = self::STATUS_PUBLISHED;
    }

    public function setPublished()
    {
        $this->publish();
    }

    /**
     * @return Whitewashing\DateTime\DateTime
     */
    public function created()
    {
        return $this->created;
    }

    /**
     *
     * @param Zend_Feed_Writer $writer
     */
    public function publishToFeed(\Zend\Feed\Writer\Feed $feed, UrlGenerator $generator)
    {
        $entry = $feed->createEntry();

        $author = $this->getAuthor();

        $entry->setId($generator->generatePostUrl($this));
        $entry->setTitle($this->getHeadline());
        $entry->setLink($generator->generatePostUrl($this));
        $entry->addAuthor(array(
            'name'  => $author->getName(),
            'email' => $author->getEmail(),
            'uri'   => 'http://www.example.com',
        ));

        $entry->setDateModified($this->created()->format('U'));
        $entry->setDateCreated($this->created()->format('U'));
        $entry->setDescription($this->getHeadline());
        $entry->setContent($this->getText());

        $feed->addEntry($entry);
    }

    /**
     * Used by ezcSearch to get searchable values
     *
     * @internal
     * @return array
     */
    public function getState()
    {
        return array(
            'id' => $this->id,
            'headline' => $this->headline,
            'text' => $this->text,
        );
    }

    /**
     * Used by ezcSearch to set values after reconstruction
     *
     * @internal
     * @param array $state
     */
    public function setState($state)
    {
        foreach ($state AS $k => $v) {
            $this->$k = $v;
        }
    }
}
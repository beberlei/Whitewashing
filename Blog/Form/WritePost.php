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

namespace Whitewashing\Blog\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Form\ChoiceField;
use Doctrine\ORM\EntityManager;

use Whitewashing\Blog\Post;
use Symfony\Component\Form\FieldGroup;

class WritePost
{
    private $post;
    private $tags = "";

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

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function createForm($validator)
    {
        $form = new Form('writepost', $this, $validator);

        $group = new FieldGroup('post');
        if (!$this->post->getId()) {
            $group->add(new ChoiceField('inputFormat', array(
                'choices' => array(
                    'rst' => 'ReStructured Text',
                    'html' => 'HTML',
                ),
            )));
        }
        $group->add(new TextField('headline'));
        $group->add(new TextareaField('text', array()));
        $group->add(new ChoiceField('published', array(
            'choices' => array(
                Post::STATUS_DRAFT => 'Draft',
                Post::STATUS_PUBLISHED => 'Published',
            )
        )));

        $form->add($group);
        $form->add(new TextField('tags'));

        return $form;
    }

    public function process(EntityManager $em)
    {
        $oldTags = $this->post->getTags();
        $newTagNames = array_map('trim', explode(",", $this->tags));

        foreach ($oldTags AS $oldTag) {
            if (!in_array($oldTag->getName(), $newTagNames)) {
                $this->post->removeTag($oldTag);
            }
        }

        foreach ($newTagNames AS $tagName) {
            $tag = $em->getRepository('Whitewashing\Blog\Post')->getOrCreateTag($tagName);
            if (\array_search($tag, $oldTags) === false) {
                $this->post->addTag($tag);
            }
        }

        if (!$em->contains($this->post)) {
            $em->persist($this->post);
        }
        $em->flush();
    }
}
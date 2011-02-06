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

namespace Whitewashing\BlogBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Form\ChoiceField;
use Symfony\Component\Form\FieldGroup;

use Whitewashing\Blog\WritePostProcess;
use Whitewashing\Blog\Post;

class BlogFormBuilder
{
    private $formContext;

    public function __construct($formContext)
    {
        $this->formContext = $formContext;
    }

    /**
     * Create a Form to write a post
     * 
     * @return Form
     */
    public function createWritePostForm($writePost)
    {
        $form = new Form('writepost', array_merge($this->formContext->getOptions()));

        $postGroup = new Form('post');
        if (!$writePost->getPostId()) {
            $postGroup->add(new ChoiceField('inputFormat', array(
                'choices' => array(
                    'rst' => 'ReStructured Text',
                    'html' => 'HTML',
                ),
            )));
        }
        $postGroup->add(new TextField('headline'));
        $postGroup->add(new TextareaField('text', array()));

        $form->add($postGroup);
        $form->add(new ChoiceField('publishStatus', array(
            'choices' => array(
                Post::STATUS_DRAFT => 'Draft',
                Post::STATUS_PUBLISHED => 'Published',
            )
        )));
        $form->add(new TextField('tags'));

        return $form;
    }

    public function createNewAuthorForm()
    {
        $form = new Form('author', array_merge($this->formContext->getOptions()));

        $form->add(new TextField('name'));
        $form->add(new TextField('username'));
        $form->add(new TextField('email'));

        return $form;
    }
}
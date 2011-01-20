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

use Whitewashing\Blog\Form\WritePost;
use Whitewashing\Blog\Post;

class BlogFormBuilder
{
    private $validator;

    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    /**
     * Create a Form to write a post
     * @param WritePost $writePost
     * @return Form
     */
    public function createWritePostForm(WritePost $writePost)
    {
        $form = new Form('writepost', $writePost, $this->validator);

        $postGroup = new FieldGroup('post');
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
}
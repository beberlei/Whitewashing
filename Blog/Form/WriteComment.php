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
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Form\HiddenField;
use Symfony\Component\Form\FieldGroup;
use Symfony\Component\HttpFoundation\Session;

use Doctrine\ORM\EntityManager;

use Whitewashing\Blog\Comment;
use Whitewashing\Blog\Post;

class WriteComment
{
    private $post;
    private $comment;

    private $expectedRiddle = 0;
    private $riddle = "";
    private $riddleResult = 0;


    /**
     * @param Post $post
     */
    public function __construct(Post $post, Session $session)
    {
        $this->expectedRiddle = $session->get('commentRiddle', -1);
        $riddleA = rand(1, 10);
        $riddleB = rand(1, 10);
        $this->riddle = $riddleA . " + " . $riddleB;
        $session->set('commentRiddle', $riddleA + $riddleB);

        $this->post = $post;
        $this->comment = $post->createComment();
    }

    public function getRiddle()
    {
        return $this->riddle;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setRiddleResult($result)
    {
        if ($this->expectedRiddle != $result) {
            throw new \UnexpectedValueException("Wrong solution to the given riddle. Try a new one!");
        }

        $this->riddleResult = $result;
    }

    public function getRiddleResult()
    {
        return $this->riddleResult;
    }

    public function createForm($validator)
    {
        $form = new Form('writecomment', $this, $validator);

        $group = new FieldGroup('comment');
        $group->add(new TextField('username'));
        $group->add(new TextField('userEmail'));
        $group->add(new TextareaField('text'));

        $form->add($group);
        $form->add(new TextField('riddleResult'));

        return $form;
    }

    public function process(EntityManager $em)
    {
        $this->post->increaseCommentCount();
        $em->persist($this->comment);
        $em->flush();
    }
}
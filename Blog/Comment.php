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

class Comment
{
    private $id;
    private $post;
    private $username;
    private $userEmail;
    private $text;
    private $created;
    private $visible = true;

    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->created = DateFactory::now();
    }

    public function getId() {
        return $this->id;
    }

    public function getPost() {
        return $this->post;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getUserEmail() {
        return $this->userEmail;
    }

    public function getGravatarUserEmailHash()
    {
        return md5(strtolower(trim($this->userEmail)));
    }

    public function getText() {
        return $this->text;
    }

    public function getFormattedText() {
        return nl2br(strip_tags($this->text));
    }

    public function created() {
        return $this->created;
    }

    public function isVisible() {
        return $this->visible;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
}
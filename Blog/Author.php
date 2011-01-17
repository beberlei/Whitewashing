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

class Author
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email = "";

    /**
     * @param  string $name
     * @param  string $username
     * @param  string $email
     * @return User
     */
    static public function create($name, $username, $email = null)
    {
        $u = new Author();
        $u->setName($name);
        $u->setUsername($username);
        $u->setEmail($email);

        return $u;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    private function setName($name) {
        $this->name = $name;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email)
    {
        if ($email !== null && !\filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw BlogException::invalidUserEmailAddress($email);
        }

        $this->email = (string)$email;
    }

    public function __toString()
    {
        return $this->name;
    }
}
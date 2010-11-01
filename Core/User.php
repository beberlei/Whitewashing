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

namespace Whitewashing\Core;

use Symfony\Component\Security\User\AccountInterface;

class User implements AccountInterface
{
    const USER = 'user';
    const AUTHOR = 'author';
    const ADMIN = 'admin';

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
    private $password;

    /**
     * @var string
     */
    private $email;

    private $role;

    /**
     * @var string
     */
    private $salt;

    /**
     * @param  string $username
     * @param  string $email
     * @return User
     */
    static public function create($username, $email, $password, $roles = self::USER)
    {
        $u = new User();
        $u->setName($username);
        
        $u->setEmail($email);
        $u->setPassword($password);
        $u->addRole($roles);
        $u->setSalt(md5(microtime(true) . \uniqid(null, true)));

        return $u;
    }

    public function addRole($role)
    {
        $this->role = $role;
    }

    private function setSalt($salt)
    {
        $this->salt = $salt;
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

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email)
    {
        if (!\filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw CoreException::invalidUserEmailAddress($email);
        }

        $this->email = $email;
    }

    public function __toString()
    {
        return $this->name;
    }
    
    public function getRoles()
    {
        return explode(",", $this->role);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }
    
    public function getUsername()
    {
        return $this->name;
    }

    public function eraseCredentials()
    {
        $this->password = null;
        $this->salt = null;
    }
}
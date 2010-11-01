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

class User
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
     * @param  string $username
     * @param  string $email
     * @return User
     */
    static public function create($username, $email, $password, $role = self::USER)
    {
        $u = new User();
        $u->setName($username);
        
        $u->setEmail($email);
        $u->setPassword($password);
        $u->setRole($role);

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

    /**
     * @param  string $password
     * @return bool
     */
    public function areValidCredentials($password)
    {
        return md5($password) == $this->password;
    }

    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    public function setRole($role)
    {
        if (!in_array($role, array(self::USER, self::AUTHOR, self::ADMIN))) {
            throw CoreException::invalidRole($role);
        }

        $this->role = $role;
    }

    public function getRoleId()
    {
        return $this->role;
    }
}
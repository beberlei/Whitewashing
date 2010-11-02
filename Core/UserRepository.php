<?php

namespace Whitewashing\Core;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->findOneBy(array('username' => $username));
    }
}
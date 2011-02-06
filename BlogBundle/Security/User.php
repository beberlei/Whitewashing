<?php

namespace Whitewashing\BlogBundle\Security;

/**
 * Optional entity for the FOS User Bundle if the blog is deployed standalone.
 */
class User extends \FOS\UserBundle\Entity\User
{
    protected $id;
}
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

use FOS\UserBundle\Entity\User as BaseUser;

/**
 * If using the blog standalone this user can be used with FOS UserBundle
 */
class User extends BaseUser
{
    protected $id;
}
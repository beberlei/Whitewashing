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

namespace Whitewashing\Tests\Blog;

use Whitewashing\Blog\Author;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateUser()
    {
        $user = Author::create('Benjamin Eberlei', 'beberlei', 'kontakt@beberlei.de');

        $this->assertType('Whitewashing\Blog\Author', $user);
        $this->assertEquals('Benjamin Eberlei', $user->getName());
        $this->assertEquals('beberlei', $user->getUsername());
        $this->assertEquals('kontakt@beberlei.de', $user->getEmail());
    }
}
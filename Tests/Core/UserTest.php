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

namespace Whitewashing\Tests\Core;

use Whitewashing\Core\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateUser()
    {
        $user = User::create('beberlei', 'kontakt@beberlei.de', 'abcdefg');

        $this->assertType('Whitewashing\Core\User', $user);
        $this->assertEquals('beberlei', $user->getName());
        $this->assertEquals('kontakt@beberlei.de', $user->getEmail());
        $this->assertEquals(User::USER, $user->getRoleId());
    }
}
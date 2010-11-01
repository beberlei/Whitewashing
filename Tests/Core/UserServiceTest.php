<?php

namespace Whitewashing\Tests\Core;

use Whitewashing\Tests\TestCase,
    Whitewashing\Core\UserService;

class UserServiceTest extends TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * @var Whitewashing\Core\MailService
     */
    public $mail;

    /**
     * @var UserService
     */
    public $service;

    public function setUp()
    {
        $this->em = $this->getCleanMock('Doctrine\ORM\EntityManager');
        $this->mail = $this->getMock('Whitewashing\Core\MailService');
        $this->service = new UserService($this->em, $this->mail);
    }

    public function testCreateUser()
    {
        $username = "beberlei";
        $email = "kontakt@beberlei.de";
        $user = $this->service->create($username, $email);

        $this->assertType('Whitewashing\Core\User', $user);
        $this->assertEquals('beberlei', $user->getName());
        $this->assertEquals('kontakt@beberlei.de', $user->getEmail());
    }

    public function testCreateUser_InvalidEmail_ThrowsException()
    {
        $username = "beberlei";
        $email = "lkajsdf";

        $this->setExpectedException('Whitewashing\Core\CoreException');

        $user = $this->service->create($username, $email);
    }

    public function testCreateUser_IsPersisted()
    {
        $this->em->expects($this->at(0))
                 ->method('persist')
                 ->with($this->isInstanceOf('Whitewashing\Core\User'));
        $this->em->expects($this->at(1))
                 ->method('flush');

        $username = "beberlei";
        $email = "kontakt@beberlei.de";
        $user = $this->service->create($username, $email);
    }

    public function testCreateUser_SendMailToUser()
    {
        $this->mail->expects($this->at(0))
                   ->method('send')
                   ->with($this->isInstanceOf('Whitewashing\Core\User'),
                       $this->isType('string'), $this->isType('string'));

        $username = "beberlei";
        $email = "kontakt@beberlei.de";
        $user = $this->service->create($username, $email);
    }
}
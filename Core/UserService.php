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
use Doctrine\ORM\EntityManager;

class UserService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(EntityManager $em, MailService $mail)
    {
        $this->em = $em;
        $this->mailService = $mail;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return Whitewashing\Core\User
     */
    public function create($username, $email, $password = null, $role = User::USER)
    {
        if ($password == null) {
            $password = substr(md5(microtime(true)), 0, 8);
        }

        $user = User::create($username, $email, $password, $role);

        $this->em->persist($user);
        $this->em->flush();

        $subject = "Your blog account details";
        $text = "Hello %s,\n\nHere is the password for your account: %s\n\nHave fun!";
        $text = sprintf($text, $user->getName(), $password);

        $this->mailService->send($user, $subject, $text);

        return $user;
    }

    /**
     * @param  string $username
     * @return Whitewashing\Core\User
     */
    public function findUser($username)
    {
        return $this->em->getRepository('Whitewashing\Core\User')
                        ->findOneBy(array('name' => $username));
    }
}
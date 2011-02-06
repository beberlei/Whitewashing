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

use Doctrine\ORM\EntityRepository;

class AuthorRepository extends EntityRepository
{
    /**
     * @param  string $username
     * @return Author
     */
    public function createNewAuthor($username)
    {
        $author = Author::create($username, $username);
        $this->getEntityManager()->persist($author);

        return $author;
    }

    public function findAuthorForUserAccount($username)
    {
        $author = $this->findOneBy(array('username' => $username));
        if (!$author) {
            throw BlogException::unknownAuthor($username);
        }
        return $author;
    }
}
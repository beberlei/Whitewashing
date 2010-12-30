<?php

namespace Whitewashing\Blog;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function matchingTags($pattern)
    {
        $dql = "SELECT t.name FROM Whitewashing\Blog\Tag t INDEX BY WHERE t.name LIKE ?1";
        $tags = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter(1, '%' . $pattern . '%')
            ->setMaxResults(10)
            ->getArrayResult();

        return array_keys($tags);
    }
}
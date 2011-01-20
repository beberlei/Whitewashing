<?php

namespace Whitewashing\Blog;

use Doctrine\ORM\EntityRepository;
use Whitewashing\Util\String;

class TagRepository extends EntityRepository implements ITagRepository
{
    public function matchingTags($pattern)
    {
        $dql = "SELECT t.name FROM Whitewashing\Blog\Tag t WHERE t.name LIKE ?1";
        $tags = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter(1, '%' . $pattern . '%')
            ->setMaxResults(10)
            ->getArrayResult();

        return array_map(function($tag) {
            return $tag['name'];
        }, $tags);
    }

    /**
     * @param array $tagNames
     * @return array
     */
    public function getTags(array $tagNames)
    {
        $tags = array();
        foreach ($tagNames AS $tag) {
            $tags[] = $this->getOrCreateTag($tag);
        }
        return $tags;
    }

    /**
     * Get existing or create a new tag
     *
     * @param  string $name
     * @return Tag
     */
    public function getOrCreateTag($name)
    {
        $slug = String::slugize($name);
        $tag = $this->findOneBy(array('slug' => $slug));

        if (!$tag) {
            $tag = new Tag($name);
            $this->getEntityManager()->persist($tag);
        }
        return $tag;
    }
}
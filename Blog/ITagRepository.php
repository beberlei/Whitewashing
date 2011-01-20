<?php

namespace Whitewashing\Blog;

interface ITagRepository
{
    /**
     * Get the names of tags matching the given pattern.
     *
     * The matching algorithm is not defined, can be regex or sound-distance or a mix of them.
     * 
     * @param string $pattern
     * @return string[]
     */
    public function matchingTags($pattern);

    /**
     * @param array $tagNames
     * @return Tag[]
     */
    public function getTags(array $tagNames);

    public function getOrCreateTag($tagName);
}
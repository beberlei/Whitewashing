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

class BlogException extends \Exception
{
    static public function noDefaultBlogConfigured()
    {
        return new self('There is no default blog configured.');
    }

    static public function unknownTag($tag)
    {
        return new self('The tag '.$tag.' is not known to this blog');
    }
}
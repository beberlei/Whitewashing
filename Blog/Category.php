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
use Whitewashing\Util\String;

class Category
{
    private $id;
    private $blog;
    private $short;
    private $name;

    /**
     * @param string $name
     * @param Blog $blog
     */
    public function __construct($name, Blog $blog)
    {
        $this->setName($name);
        $this->blog = $blog;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getShort() {
        return $this->short;
    }

    public function setShort($short) {
        $this->short = $short;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        if ($this->short == null) {
            $this->short = String::slugize($name);
        }
        $this->name = $name;
    }
}
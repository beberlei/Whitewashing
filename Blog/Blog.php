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

class Blog
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Category
     */
    private $defaultCategory;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param string $categoryName
     * @return Category
     */
    public function createCategory($categoryName)
    {
        $category = new Category($categoryName, $this);
        if (!$this->defaultCategory) {
            $this->defaultCategory = $category;
        }
        $this->categories[] = $category;

        return $category;
    }

    public function setDefaultCategory(Category $category)
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        $this->defaultCategory = $category;
    }

    /**
     * @return Category
     */
    public function getDefaultCategory()
    {
        if (!$this->defaultCategory) {
            throw new Exception("There is no default category!");
        }

        return $this->defaultCategory;
    }
}
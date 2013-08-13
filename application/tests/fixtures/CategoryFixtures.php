<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class CategoryFixtures extends AbstractFixtures
{
    /**
     * @return int
     */
    public static function getOrder()
    {
        return 1;
    }

    /**
     * Create Category
     */
    public function load()
    {
        $oBlogCategory = $this->_createCategory('First category name', 'first_category_url');

        $this->addReference('blog-category', $oBlogCategory);
    }
}
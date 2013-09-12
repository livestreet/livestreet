<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class BlogFixtures extends AbstractFixtures
{
    /**
     * @return int
     */
    public static function getOrder()
    {
        return 2;
    }

    /**
     * Create Blog
     */
    public function load()
    {
        $oUserFirst = $this->getReference('user-golfer');
        $oCategory = $this->getReference('blog-category');

        /* @var $oBlogGadgets ModuleBlog_EntityBlog */
        $oBlogGadgets = Engine::GetEntity('Blog');
        $oBlogGadgets->setOwnerId($oUserFirst->getId());
        $oBlogGadgets->setTitle("Gadgets");
        $oBlogGadgets->setDescription('Offers latest gadget reviews');
        $oBlogGadgets->setType('open');
        $oBlogGadgets->setDateAdd(date("Y-m-d H:i:s")); // @todo freeze
        $oBlogGadgets->setUrl('gadgets');
        $oBlogGadgets->setLimitRatingTopic(0);
        $oBlogGadgets->setCategoryId($oCategory->getCategoryId());

        $this->oEngine->Blog_AddBlog($oBlogGadgets);

        $this->addReference('blog-gadgets', $oBlogGadgets);
    }
}


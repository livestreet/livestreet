<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class BlogFixtures extends AbstractFixtures
{
    public static function getOrder()
    {
        return 1;
    }

    public function load()
    {
        $oUserFirst = $this->getReference('user-golfer');

        /* @var $oBlogGadgets ModuleBlog_EntityBlog */
        $oBlogGadgets = Engine::GetEntity('Blog');
        $oBlogGadgets->setOwnerId($oUserFirst->getId());
        $oBlogGadgets->setTitle("Gadgets");
        $oBlogGadgets->setDescription('Offers latest gadget reviews');
        $oBlogGadgets->setType('open');
        $oBlogGadgets->setDateAdd(date("Y-m-d H:i:s")); // @todo freeze
        $oBlogGadgets->setUrl('gadgets');
        $oBlogGadgets->setLimitRatingTopic(0);

        $this->oEngine->Blog_AddBlog($oBlogGadgets);

        $this->addReference('blog-gadgets', $oBlogGadgets);
    }
}


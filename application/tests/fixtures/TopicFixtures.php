<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class TopicFixtures extends AbstractFixtures
{
    /**
     * @return int
     */
    public static function getOrder()
    {
        return 3;
    }

    /**
     * Create Topics:
     * Toshiba unveils 13.3-inch AT330 Android ICS 4.0 tablet,
     * iPad 3 rumored to come this March with quad-core chip and 4G LTE,
     * Sony MicroVault Mach USB 3.0 flash drive,
     * Draft Topic
     */
    public function load()
    {
        $oUserFirst = $this->getReference('user-golfer');
        $oBlogGadgets = $this->getReference('blog-gadgets');

        $oTopicToshiba = $this->_createTopic($oBlogGadgets->getBlogId(), $oUserFirst->getId(),
            'Toshiba unveils 13.3-inch AT330 Android ICS 4.0 tablet',
            'Toshiba is to add a new Android 4.0 ICS to the mass which is known as Toshiba AT330. The device is equipped with a multi-touch capacitive touch display that packs a resolution of 1920 x 1200 pixels. The Toshiba AT330 tablet is currently at its prototype stage. We have very little details about the tablet, knowing that it’ll come equipped with HDMI port, on-board 32GB storage that’s expandable via an full-sized SD card slot. It’ll also have a built-in TV tuner and a collapsible antenna.It’ll also run an NVIDIA Tegra 3 quad-core processor. Other goodies will be a 1.3MP front-facing camera and a 5MP rear-facing camera. Currently, there is no information about its price and availability. A clip is included below showing it in action.',
            'gadget', '2012-10-21 00:10:20');
        $this->addReference('topic-toshiba', $oTopicToshiba);

        $oTopicIpad = $this->_createTopic($oBlogGadgets->getBlogId(), $oUserFirst->getId(),
            'iPad 3 rumored to come this March with quad-core chip and 4G LTE',
            'Another rumor for the iPad 3 has surfaced with some details given by Bloomberg, claiming that the iPad 3 production is already underway and will be ready for a launch as early as March.',
            'apple, ipad', '2012-10-21 1:20:30');
        $this->addReference('topic-ipad', $oTopicIpad);

        $oPersonalBlogGolfer = $this->oEngine->Blog_GetPersonalBlogByUserId($oUserFirst->getId());
        $oTopicSony = $this->_createTopic($oPersonalBlogGolfer->getBlogId(), $oUserFirst->getId(),
            'Sony MicroVault Mach USB 3.0 flash drive',
            'Want more speeds and better protection for your data? The Sony MicroVault Mach flash USB 3.0 drive is what you need. It offers the USB 3.0 interface that delivers data at super high speeds of up to 5Gbps. It’s also backward compatible with USB 2.0.',
            'sony, flash, gadget', '2012-10-21 2:30:40');
        $this->addReference('topic-sony', $oTopicSony);

        $oTopicDraft = $this->_createTopic($oPersonalBlogGolfer->getBlogId(), $oUserFirst->getId(),
            'Draft Topic',
            'draft text draft text draft text draft text draft text draft text draft text',
            'sony, ipad', '2012-10-21 2:40:50', false);
        $this->addReference('topic-draft', $oTopicDraft);
    }
}
<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class CommentFixtures extends AbstractFixtures
{

    /**
     * @return int
     */
    public static function getOrder()
    {
        return 4;
    }

    /**
     * Create Comment
     */
    public function load()
    {
        $oUserFirst = $this->getReference('user-golfer');
        $oTopic = $this->getReference('topic-toshiba');

        $oTopicComment = $this->_createComment($oTopic, $oUserFirst, NULL, 'fixture comment text');
        $this->addReference('topic-toshiba-comment', $oTopicComment);

    }
}
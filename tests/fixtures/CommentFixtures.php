<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class CommentFixtures extends AbstractFixtures
{

    protected $aActivePlugins = array();
    public static function getOrder()
    {
        return 3;
    }

    public function load()
    {
        $oUserFirst = $this->getReference('user-golfer');
        $oBlogGadgets = $this->getReference('blog-gadgets');
        $oTopic = $this->getReference('topic-toshiba');

        $oTopicComment = $this->_createComment($oTopic, $oUserFirst, NULL, 'comment text',
            'comment date');
        $this->addReference('topic-toshiba-comment', $oTopicComment);

    }

    /**
     * Create topic comment with default values
     *
     */
    private function _createComment($oTopic, $oUser, $sParentId = null, $sText = 'default comment text', $sDate = "now")
    {
        $this->aActivePlugins = $this->oEngine->Plugin_GetActivePlugins();

        $oComment = Engine::GetEntity('Comment');
        //$date = new \DateTime($sDate);
        $oComment->setTargetId($oTopic->getId());
        $oComment->setTargetType('topic');
        $oComment->setTargetParentId($oTopic->getBlogId());
        $oComment->setUserId($oUser->getId());
        $oComment->setText($sText);
        $oComment->setDate(date('Y-m-d H:i:s', time()));
        $oComment->setUserIp(func_getIp());
        $oComment->setPid($sParentId);
        $oComment->setTextHash(md5($sText));
        $oComment->setPublish(true);

        $oComment = $this->oEngine->Comment_AddComment($oComment);

        return $oComment;
    }
}
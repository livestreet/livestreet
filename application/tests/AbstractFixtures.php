<?php

/**
 * Abstract class for LiveStreet fixtures
 */
abstract class AbstractFixtures
{

    /**
     * @var Engine
     */
    protected $oEngine;

    /**
     * Objects references
     *
     * @var array
     */
    private $aReferences = array();

    private $aActivePlugins = array();

    /**
     * @param Engine $oEngine
     * @param array $aReferences
     * @return void
     */
    public function __construct(Engine $oEngine, $aReferences)
    {
        $this->oEngine = $oEngine;
        $this->aReferences = $aReferences;
        $this->aActivePlugins = $oEngine->Plugin_GetActivePlugins();
    }

    /**
     * Add reference
     *
     * @param string $name
     * @param array $data
     * @return void
     */
    public function addReference($name, $data)
    {
        $this->aReferences[$name] = $data;
    }

    /**
     * Get reference by key
     *
     * @param string $key
     * @throws Exception if reference is not exist
     * @return array aReferences
     * @return void
     */
    public function getReference($key)
    {
        if (isset($this->aReferences[$key])) {
            return $this->aReferences[$key];
        }

        throw new Exception("Fixture reference \"$key\" is not exist");
    }

    /**
     * Get all references
     *
     * @return array aReferences
     */
    public function getReferences()
    {
        return $this->aReferences;
    }

    /**
     * Creating entities and saving them to DB
     *
     * @return void
     */
    abstract public function load();

    /**
     * Get order number for fixture
     *
     * @return int
     */
    public static function getOrder()
    {
        return 0;
    }

    /**
     * Get Active Plugins
     *
     * @return Active Plugins
     */
    protected function getActivePlugins()
    {
        return $this->aActivePlugins;
    }

    /**
     * Create topic with default values
     *
     * @param int $iBlogId
     * @param int $iUserId
     * @param string $sTitle
     * @param string $sText
     * @param string $sTags
     * @param string $sDate
     * @param bool $bPublish
     * @param bool $bPublishMain
     * @param bool $bPublishDraft
     *
     * @throws Exception
     *
     * @return ModuleTopic_EntityTopic
     */
    protected function _createTopic($iBlogId, $iUserId, $sTitle, $sText, $sTags, $sDate, $bPublish = true, $bPublishMain = true, $bPublishDraft = true)
    {
        $oTopic = Engine::GetEntity('Topic');
        /* @var $oTopic ModuleTopic_EntityTopic */
        $oTopic->setBlogId($iBlogId);
        $oTopic->setUserId($iUserId);
        $oTopic->setUserIp('127.0.0.1');
        $oTopic->setForbidComment(false);
        $oTopic->setType('topic');
        $oTopic->setTitle($sTitle);
        $oTopic->setPublish($bPublish);//
        $oTopic->setPublishIndex($bPublishMain);//
        $oTopic->setPublishDraft($bPublishDraft);
        $oTopic->setDateAdd($sDate);
        $oTopic->setTextSource($sText);
        list($sTextShort, $sTextNew, $sTextCut) = $this->oEngine->Text_Cut($oTopic->getTextSource());

        $oTopic->setCutText($sTextCut);
        $oTopic->setText($this->oEngine->Text_Parser($sTextNew));
        $oTopic->setTextShort($this->oEngine->Text_Parser($sTextShort));

        $oTopic->setTextHash(md5($oTopic->getType() . $oTopic->getTextSource() . $oTopic->getTitle()));
        $oTopic->setTags($sTags);
        //with active plugin l10n added a field topic_lang
        if (in_array('l10n', $this->getActivePlugins())) {
            $oTopic->setTopicLang(Config::Get('lang.current'));
        }
        // @todo refact this
        $oTopic->_setValidateScenario('topic');
        $bValid = $oTopic->_Validate();

        if (!$bValid) {
            throw new Exception("Create topic - validation error");
        }

        $this->oEngine->Topic_AddTopic($oTopic);

        return $oTopic;
    }

    /**
     * Create user with default values
     *
     * @param string $sUserName
     * @param string $sPassword
     * @param string $sMail
     * @param string $sDate
     *
     * @return ModuleTopic_EntityUser
     */
    protected function _createUser($sUserName, $sPassword, $sMail, $sDate)
    {
        $oUser = Engine::GetEntity('User');
        $oUser->setLogin($sUserName);
        $oUser->setPassword(md5($sPassword));
        $oUser->setMail($sMail);
        $oUser->setUserDateRegister($sDate);
        $oUser->setUserIpRegister('127.0.0.1');
        $oUser->setUserActivate('1');
        $oUser->setUserActivateKey('0');

        $this->oEngine->User_Add($oUser);

        return $oUser;
    }

    /**
     * Create topic comment with default values
     *
     * @param object $oTopic
     * @param object $oUser
     * @param integer $iParentId
     * @param string $sText
     *
     * @return ModuleComment_EntityComment
     */
    protected function _createComment($oTopic, $oUser, $iParentId = null, $sText = 'fixture comment text')
    {
        $oComment = Engine::GetEntity('Comment');
        $oComment->setTargetId($oTopic->getId());
        $oComment->setTargetType('topic');
        $oComment->setTargetParentId($oTopic->getBlogId());
        $oComment->setUserId($oUser->getId());
        $oComment->setText($sText);
        $oComment->setDate(date('Y-m-d H:i:s', time()));
        $oComment->setUserIp(func_getIp());
        $oComment->setPid($iParentId);
        $oComment->setTextHash(md5($sText));
        $oComment->setPublish(true);

        $oComment = $this->oEngine->Comment_AddComment($oComment);

        return $oComment;
    }

    /**
     * Create Blog Category
     *
     * @param string $sTitle
     * @param string $sUrl
     * @param integer $iSort
     * @param integer $iPid
     *
     * @throws Exception
     *
     * @return ModuleBlog_EntityBlogCategory
     */
    protected function _createCategory($sTitle, $sUrl, $iSort = 0, $iPid = null)
    {
        $oCategory = Engine::GetEntity('ModuleBlog_EntityBlogCategory');
        $oCategory->setTitle($sTitle);
        $oCategory->setUrl($sUrl);
        $oCategory->setSort($iSort);
        $oCategory->setPid($iPid);

        if ($oCategory->_Validate()) {
            $iCategoryId = $this->oEngine->Blog_AddCategory($oCategory);
            $oCategory = $this->oEngine->Blog_GetCategoryById($iCategoryId);

            return $oCategory;

        } else {
            throw new Exception("Create category - validation error");
        }
    }
}


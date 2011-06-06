<?php
class ModuleStream extends Module
{
    const EVENT_ALL = 1023;
    const EVENT_ADD_TOPIC = 2;
    const EVENT_ADD_COMMENT = 4;
    const EVENT_ADD_BLOG = 8;
    const EVENT_VOTE_TOPIC = 16;
    const EVENT_VOTE_COMMENT = 32;
    const EVENT_VOTE_BLOG = 64;
    const EVENT_VOTE_USER = 128;
    const EVENT_MAKE_FRIENDS = 256;
    const EVENT_JOIN_BLOG = 512;

    protected $oMapper = null;

    public function Init()
    {
        $this->oMapper=Engine::GetMapper(__CLASS__);
    }

    /**
     * Подписать пользователя
     * @param type $iUserId Id подписываемого пользователя
     * @param type $iSubscribeType Тип подписки (см. константы класса)
     * @param type $iTargetId Id цели подписки
     */
    public function subscribeUser($iUserId, $iTargetUserId)
    {
        return $this->oMapper->subscribeUser($iUserId, $iTargetUserId);
    }

    /**
     * Отписать пользователя
     * @param type $iUserId Id подписываемого пользователя
     * @param type $iSubscribeType Тип подписки (см. константы класса)
     * @param type $iTargetId Id цели подписки
     */
    public function unsubscribeUser($iUserId, $iTargetUserId)
    {
        return $this->oMapper->unsubscribeUser($iUserId, $iTargetUserId);
    }

    /**
     * Редактирвоание списка событий, на которые подписан юзер
     * @param type $iUserId
     * @param type $iType
     * @return type
     */
    public function switchUserEventType($iUserId, $iType)
    {
        return $this->oMapper->switchUserEventType($iUserId, $iType);
    }

    /**
     * Запись события в ленту
     * @param type $oUser
     * @param type $iEventType
     * @param type $iTargetId
     */
    public function write($oUser, $iEventType, $iTargetId)
    {
        $this->oMapper->addEvent($oUser, $iEventType, $iTargetId);
    }

    /**
     * Удалеине события из ленты
     * @param type $oUser
     * @param type $iEventType
     * @param type $iTargetId
     */
    public function delete($oUser, $iEventType, $iTargetId)
    {
        $this->oMapper->deleteEvent($oUser, $iEventType, $iTargetId);
    }

    /**
     * Чтение ленты событий
     * @param type $iCount
     * @param type $iFromId
     * @return type
     */
    public function read($iCount = null, $iFromId = null)
    {
        if (!$iCount) $iCount = Config::Get('module.stream.count_default');

        $oUser = $this->User_getUserCurrent();
        $aUserConfig = $this->getUserConfig($oUser->getId());
        $aEventTypes = $aUserConfig['event_types'];
        if (!count($aEventTypes)) return array('events' => array());
        $aUsesrList = $this->getUsersList();
        if (!$aUsesrList) return array('events' => array());

        $aEvents = array();
        $aEvents = $this->oMapper->read($aEventTypes, $aUsesrList, $iCount, $iFromId);

        $aNeededObjects = array('topics' => array(), 'blogs' => array(), 'users' => array(), 'comments' => array());
        if (!count($aEvents)) array('events' => array());
        foreach ($aEvents as $aEvent) {
            if (!in_array($aEvent['initiator'], $aNeededObjects['users'])) {
                $aNeededObjects['users'][] = $aEvent['initiator'];
            }
            switch ($aEvent['event_type']) {
                case self::EVENT_ADD_TOPIC: case self::EVENT_VOTE_TOPIC:
                    if (!in_array($aEvent['target_id'], $aNeededObjects['topics'])) {
                        $aNeededObjects['topics'][] = $aEvent['target_id'];
                    }
                    break;
                case self::EVENT_ADD_COMMENT:  case self::EVENT_VOTE_COMMENT:
                    if (!in_array($aEvent['target_id'], $aNeededObjects['comments'])) {
                        $aNeededObjects['comments'][] = $aEvent['target_id'];
                    }
                    break;
                case self::EVENT_ADD_BLOG: case self::EVENT_VOTE_BLOG: case self::EVENT_JOIN_BLOG:
                    if (!in_array($aEvent['target_id'], $aNeededObjects['blogs'])) {
                        $aNeededObjects['blogs'][] = $aEvent['target_id'];
                    }
                    break;
                case self::EVENT_VOTE_USER: case self::EVENT_MAKE_FRIENDS:
                    if (!in_array($aEvent['target_id'], $aNeededObjects['users'])) {
                        $aNeededObjects['users'][] = $aEvent['target_id'];
                    }
                    break;
            }
        }
        $aTopics = array();
        if (count($aNeededObjects['topics'])) {
            $aTopics = $this->Topic_getTopicsAdditionalData($aNeededObjects['topics']);
        }
        $aBlogs = array();
        if (count($aNeededObjects['blogs'])) {
            $aBlogs = $this->Blog_getBlogsByArrayId($aNeededObjects['blogs']);
        }
        $aUsers = array();
        if (count($aNeededObjects['users'])) {
            $aUsers = $this->User_getUsersByArrayId($aNeededObjects['users']);
        }
        $aComments = array();
        if (count($aNeededObjects['comments'])) {
            $aComments = $this->Comment_getCommentsByArrayId($aNeededObjects['comments']);
            foreach($aComments as $oComment) {
                if (!isset($aTopics[$oComment->getTargetId()])) {
                    $aTopics[$oComment->getTargetId()] = $this->Topic_getTopicById($oComment->getTargetId());
                }
            }
        }
        return array('events' => $aEvents, 'topics' => $aTopics, 'blogs' => $aBlogs, 'users' => $aUsers, 'comments' => $aComments);
    }

    /**
     * Получение списка пользователей, на которых подписан пользователь
     * @param type $iUserId
     * @return type
     */
    public function getUserSubscribes($iUserId)
    {
        $aIds = $this->oMapper->getUserSubscribes($iUserId);
        $aResult = array();
        if (count($aIds)) {
            $aUsers = $this->User_getUsersByArrayId($aIds);
            foreach ($aUsers as $oUser) {
                $aResult[$oUser->getId()] = $oUser;
            }
        }
        return $aResult;
    }

    /**
     * Получение настроек ленты
     * @param type $iUserId
     * @return type
     */
    public function getUserConfig($iUserId)
    {
        return $this->oMapper->getUserConfig($iUserId);
    }

    /**
     * Получение списка id пользователей, на которых подписан пользователь
     * @return type
     */
    protected function getUsersList()
    {
        $iUserId = $this->User_getUserCurrent()->getId();
        $aList = $this->oMapper->getUserSubscribes($iUserId);
        return $aList;
    }

}
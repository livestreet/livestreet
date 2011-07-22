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

	protected $aEventTypes = array(
		'add_topic' => array('related' => 'topics'),
		'add_comment' => array('related' => 'comments'),
		'add_blog' => array('related' => 'blogs'),
		'vote_topic' => array('related' => 'topics'),
		'vote_comment' => array('related' => 'comments'),
		'vote_blog' => array('related' => 'blogs'),
		'vote_user' => array('related' => 'users'),
		'make_friends' => array('related' => 'users'),
		'join_blog' => array('related' => 'blogs')
	);
	
	public function Init()
	{
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	
	public function getEventTypes()
	{
		return $this->aEventTypes;
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
	public function switchUserEventType($iUserId, $sType)
	{
		return $this->oMapper->switchUserEventType($iUserId, $sType);
	}

	/**
	 * Запись события в ленту
	 * @param type $oUser
	 * @param type $iEventType
	 * @param type $iTargetId
	 */
	public function write($oUser, $sEventType, $iTargetId)
	{
		$this->oMapper->addEvent($oUser, $sEventType, $iTargetId);
	}

	/**
	 * Удалеине события из ленты
	 * @param type $oUser
	 * @param type $iEventType
	 * @param type $iTargetId
	 */
	public function delete($oUser, $sEventType, $iTargetId)
	{
		$this->oMapper->deleteEvent($oUser, $sEventType, $iTargetId);
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
		$aEventTypes = $this->getTypesList($oUser->getId());
		if (!count($aEventTypes)) return array('events' => array());
		$aUsersList = $this->getUsersList();
		if (!count($aUsersList)) return array('events' => array());

		$aEvents = array();
		$aEvents = $this->oMapper->read($aEventTypes, $aUsersList, $iCount, $iFromId);
		
		/*
		 * Создание массива для загрузки дополнительных объектов. необходимых при отображении ленты
		 */
		$aNeededObjects = array();
		$aResult = array('events' => $aEvents);
		foreach ($this->aEventTypes as $aType) {
			if (!isset($aNeededObjects[$aType['related']])) {
				$aNeededObjects[$aType['related']] = array();
				$aResult[$aType['related']] = array();
			}
		}
		if (!count($aEvents)) array('events' => array());
		foreach ($aEvents as $aEvent) {
			if (!in_array($aEvent['initiator'], $aNeededObjects['users'])) {
				$aNeededObjects['users'][] = $aEvent['initiator'];
			}
			$sRelatedType = $this->aEventTypes[$aEvent['event_type']]['related'];
			if (isset($aNeededObjects[$sRelatedType])) {
				$aNeededObjects[$sRelatedType][] = $aEvent['target_id'];
			}
		}
		
		foreach ($aNeededObjects as $sType => $aList) {
			if (count($aList)) {
				$sFunction = 'loadRelated' . ucfirst($sType);
				if (method_exists($this, $sFunction)) {
					$this->$sFunction($aList, $aResult);
				}
			}
		}
		return$aResult;
	}

	protected function loadRelatedTopics($aIds, &$aRelatedObjects)
	{
		$aTopicsUnsorted =$this->Topic_getTopicsAdditionalData($aIds);
		foreach ($aTopicsUnsorted as $oTopic) {
			if (!isset($aRelatedObjects['topics'][$oTopic->getId()] )) {
				$aRelatedObjects['topics'][$oTopic->getId()] = $oTopic;
			}
		}
	}
	
	protected function loadRelatedBlogs($aIds, &$aRelatedObjects)
	{
		$aBlogsUnsorted =$this->Blog_getBlogsByArrayId($aIds);
		foreach ($aBlogsUnsorted as $oBlog) {
			if (!isset($aRelatedObjects['blogs'][$oBlog->getId()] )) {
				$aRelatedObjects['blogs'][$oBlog->getId()] = $oBlog;
			}
		}
	}
	protected function loadRelatedComments($aIds, &$aRelatedObjects)
	{
		$aCommentsUnsorted =$this->Comment_getCommentsByArrayId($aIds);
		foreach ($aCommentsUnsorted as $oComment) {
			if (!isset($aRelatedObjects['comments'][$oComment->getId()] )) {
				$aRelatedObjects['comments'][$oComment->getId()] = $oComment;
			}
		}
		$aTopics = array();
		foreach($aComments as $oComment) {
			if (!isset($aRelatedObjects['topics'][$oComment->getTargetId()])) {
				$aTopics[] = $oComment->getTargetId();
			}
		}
		$this->loadRelatedTopics($aTopics, $aRelatedObjects);
		
	}
	protected function loadRelatedUsers($aIds, &$aRelatedObjects)
	{
		$aRelatedObjects['users'] =  $this->User_getUsersByArrayId($aIds);
		$aUsersUnsorted =$this->User_getUsersByArrayId($aIds);
		foreach ($aUsersUnsorted as $oUser) {
			if (!isset($aRelatedObjects['users'][$oUser->getId()] )) {
				$aRelatedObjects['users'][$oUser->getId()] = $oUser;
			}
		}
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
	 * Получение типов событий, на которые подписан пользователь
	 * @param type $iUserId
	 * @return type
	 */
	public function getTypesList($iUserId)
	{
		return $this->oMapper->getTypesList($iUserId);
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
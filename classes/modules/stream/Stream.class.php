<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Модуль потока событий на сайте
 *
 */
class ModuleStream extends Module {

	protected $oMapper = null;

	/**
	 * Типы событий
	 *
	 * @var unknown_type
	 */
	protected $aEventTypes = array(
		'add_topic' => array('related' => 'topic'),
		'add_comment' => array('related' => 'comment'),
		'add_blog' => array('related' => 'blog'),
		'vote_topic' => array('related' => 'topic'),
		'vote_comment' => array('related' => 'comment'),
		'vote_blog' => array('related' => 'blog'),
		'vote_user' => array('related' => 'user'),
		'add_friend' => array('related' => 'user'),
		'join_blog' => array('related' => 'blog')
	);

	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}

	/**
	 * Возвращает все типы событий
	 *
	 * @return unknown
	 */
	public function getEventTypes() {
		return $this->aEventTypes;
	}
	/**
	 * Добавляет новый тип события, метод для расширения списка событий плагинами
	 *
	 * @param unknown_type $sName
	 * @param unknown_type $aParams
	 * @return unknown
	 */
	public function AddEventType($sName,$aParams) {
		if (!key_exists($sName,$this->aEventTypes)) {
			$this->aEventTypes[$sName]=$aParams;
			return true;
		}
		return false;
	}
	/**
	 * Добавление события в БД
	 *
	 * @param unknown_type $oObject
	 * @return unknown
	 */
	public function AddEvent($oObject) {
		if ($iId=$this->oMapper->AddEvent($oObject)) {
			$oObject->setId($iId);
			return $oObject;
		}
		return false;
	}
	/**
	 * Запись события в ленту
	 * @param type $oUser
	 * @param type $iEventType
	 * @param type $iTargetId
	 */
	public function Write($iUserId, $sEventType, $iTargetId) {
		$oEvent=Engine::GetEntity('Stream_Event');
		$oEvent->setEventType($sEventType);
		$oEvent->setUserId($iUserId);
		$oEvent->setTargetId($iTargetId);
		$oEvent->setDateAdded(date("Y-m-d H:i:s"));
		$this->AddEvent($oEvent);
	}
	/**
	 * Чтение потока пользователя
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iFromId
	 * @param unknown_type $iUserId
	 * @return unknown
	 */
	public function Read($iCount=null,$iFromId=null,$iUserId=null) {
		if (!$iCount) $iCount = Config::Get('module.stream.count_default');
		if (!$iUserId) {
			if ($this->User_getUserCurrent()) {
				$iUserId=$this->User_getUserCurrent()->getId();
			} else {
				return array();
			}
		}
		/**
		 * Получаем типы событий
		 */
		$aEventTypes = $this->getTypesList($iUserId);
		if (Config::Get('module.stream.disable_vote_events')) {
			foreach ($aEventTypes as $i => $sType) {
				if (substr($sType, 0, 4) == 'vote') {
					unset ($aEventTypes[$i]);
				}
			}
		}
		if (!count($aEventTypes)) return array();
		/**
		 * Получаем список тех на кого подписан
		 */
		$aUsersList = $this->getUsersList($iUserId);
		if (!count($aUsersList)) return array();
		/**
		 * Получаем список событий
		 */
		$aEvents = $this->oMapper->Read($aEventTypes, $aUsersList, $iCount, $iFromId);
		/**
		 * Составляем список объектов для загрузки
		 */
		$aNeedObjects=array();
		foreach ($aEvents as $oEvent) {
			if (isset($this->aEventTypes[$oEvent->getEventType()]['related'])) {
				$aNeedObjects[$this->aEventTypes[$oEvent->getEventType()]['related']][]=$oEvent->getTargetId();
			}
			$aNeedObjects['user'][]=$oEvent->getUserId();
		}
		/**
		 * Получаем объекты
		 */
		$aObjects=array();
		foreach ($aNeedObjects as $sType => $aListId) {
			if (count($aListId)) {
				$aListId=array_unique($aListId);
				$sMethod = 'loadRelated' . ucfirst($sType);
				if (method_exists($this, $sMethod)) {
					if ($aRes=$this->$sMethod($aListId)) {
						foreach ($aRes as $oObject) {
							$aObjects[$sType][$oObject->getId()]=$oObject;
						}
					}
				}
			}
		}
		/**
		 * Формируем результирующий поток
		 */
		foreach ($aEvents as $key => $oEvent) {
			/**
			 * Жестко вытаскиваем автора события
			 */
			if (isset($aObjects['user'][$oEvent->getUserId()])) {
				$oEvent->setUser($aObjects['user'][$oEvent->getUserId()]);
				/**
				 * Аттачим объекты
				 */
				if (isset($this->aEventTypes[$oEvent->getEventType()]['related'])) {
					$sTypeObject=$this->aEventTypes[$oEvent->getEventType()]['related'];
					if (isset($aObjects[$sTypeObject][$oEvent->getTargetId()])) {
						$oEvent->setTarget($aObjects[$sTypeObject][$oEvent->getTargetId()]);
					} else {
						unset($aEvents[$key]);
					}
				} else {
					unset($aEvents[$key]);
				}
			} else {
				unset($aEvents[$key]);
			}
		}
		return $aEvents;
	}
	
	
	/**
	 * Получение типов событий, на которые подписан пользователь
	 * @param type $iUserId
	 * @return type
	 */
	public function getTypesList($iUserId) {
		return $this->oMapper->getTypesList($iUserId);
	}
	/**
	 * Получение списка id пользователей, на которых подписан пользователь
	 * @return type
	 */
	protected function getUsersList($iUserId) {
		return $this->oMapper->getUserSubscribes($iUserId);
	}
	/**
	 * Получение списка пользователей, на которых подписан пользователь
	 * @param type $iUserId
	 * @return type
	 */
	public function getUserSubscribes($iUserId) {
		$aIds = $this->oMapper->getUserSubscribes($iUserId);
		return $this->User_GetUsersAdditionalData($aIds);
	}
	/**
	 * Редактирвоание списка событий, на которые подписан юзер
	 * @param type $iUserId
	 * @param type $iType
	 * @return type
	 */
	public function switchUserEventType($iUserId, $sType) {
		return $this->oMapper->switchUserEventType($iUserId, $sType);
	}
	/**
	 * Подписать пользователя
	 * @param type $iUserId Id подписываемого пользователя
	 * @param type $iSubscribeType Тип подписки (см. константы класса)
	 * @param type $iTargetId Id цели подписки
	 */
	public function subscribeUser($iUserId, $iTargetUserId) {
		return $this->oMapper->subscribeUser($iUserId, $iTargetUserId);
	}
	/**
	 * Отписать пользователя
	 * @param type $iUserId Id подписываемого пользователя
	 * @param type $iSubscribeType Тип подписки (см. константы класса)
	 * @param type $iTargetId Id цели подписки
	 */
	public function unsubscribeUser($iUserId, $iTargetUserId) {
		return $this->oMapper->unsubscribeUser($iUserId, $iTargetUserId);
	}
	
	/**
	 * Получает список топиков
	 *
	 * @param unknown_type $aIds
	 * @return unknown
	 */
	protected function loadRelatedTopic($aIds) {
		return $this->Topic_GetTopicsAdditionalData($aIds);
	}
	/**
	 * Получает список блогов
	 *
	 * @param unknown_type $aIds
	 * @return unknown
	 */
	protected function loadRelatedBlog($aIds) {
		return $this->Blog_GetBlogsAdditionalData($aIds);
	}
	/**
	 * Получает список комментариев
	 *
	 * @param unknown_type $aIds
	 * @return unknown
	 */
	protected function loadRelatedComment($aIds) {
		return $this->Comment_GetCommentsAdditionalData($aIds);

	}
	/**
	 * Получает список пользователей
	 *
	 * @param unknown_type $aIds
	 * @return unknown
	 */
	protected function loadRelatedUser($aIds) {
		return $this->User_GetUsersAdditionalData($aIds);
	}
}
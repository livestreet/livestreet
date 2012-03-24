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
	 * Список дефолтных типов событий, они добавляются каждому пользователю при регистрации
	 *
	 * @var array
	 */
	protected $aEventDefaultTypes=array(
		'add_topic','add_comment','add_blog','vote_topic','add_friend'
	);
	/**
	 * Типы событий
	 *
	 * @var array
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
		if (!array_key_exists($sName,$this->aEventTypes)) {
			$this->aEventTypes[$sName]=$aParams;
			return true;
		}
		return false;
	}
	/**
	 * Проверка допустимого типа событий
	 *
	 * @param string $sType
	 */
	public function IsAllowEventType($sType) {
		return array_key_exists($sType,$this->aEventTypes);
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
	 * Обновление события
	 *
	 * @param unknown_type $oObject
	 * @return unknown
	 */
	public function UpdateEvent($oObject) {
		return $this->oMapper->UpdateEvent($oObject);
	}
	/**
	 * Получает событие по типу и его ID
	 *
	 * @param unknown_type $sEventType
	 * @param unknown_type $iTargetId
	 * @return unknown
	 */
	public function GetEventByTarget($sEventType, $iTargetId) {
		return $this->oMapper->GetEventByTarget($sEventType, $iTargetId);
	}
	/**
	 * Запись события в ленту
	 * @param type $oUser
	 * @param type $iEventType
	 * @param type $iTargetId
	 */
	public function Write($iUserId, $sEventType, $iTargetId, $iPublish=1) {
		if ($oEvent=$this->GetEventByTarget($sEventType, $iTargetId)) {
			/**
			 * Событие уже было
			 */
			if ($oEvent->getPublish()!=$iPublish) {
				$oEvent->setPublish($iPublish);
				$this->UpdateEvent($oEvent);
			}
		} elseif ($iPublish) {
			/**
			 * Создаем новое событие
			 */
			$oEvent=Engine::GetEntity('Stream_Event');
			$oEvent->setEventType($sEventType);
			$oEvent->setUserId($iUserId);
			$oEvent->setTargetId($iTargetId);
			$oEvent->setDateAdded(date("Y-m-d H:i:s"));
			$oEvent->setPublish($iPublish);
			$this->AddEvent($oEvent);
		}
	}
	/**
	 * Чтение потока пользователя
	 *
	 * @param int $iCount
	 * @param int $iFromId
	 * @param int $iUserId
	 * @return array
	 */
	public function Read($iCount=null,$iFromId=null,$iUserId=null) {
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
		/**
		 * Получаем список тех на кого подписан
		 */
		$aUsersList = $this->getUsersList($iUserId);

		return $this->ReadEvents($aEventTypes,$aUsersList,$iCount,$iFromId);
	}

	/**
	 * Чтение активности конкретного пользователя
	 *
	 * @param int $iCount
	 * @param int $iUserId
	 * @return array
	 */
	public function ReadByUserId($iUserId,$iCount=null,$iFromId=null) {
		/**
		 * Получаем типы событий
		 */
		$aEventTypes=array_keys($this->getEventTypes());
		/**
		 * Получаем список тех на кого подписан
		 */
		$aUsersList = array($iUserId);

		return $this->ReadEvents($aEventTypes,$aUsersList,$iCount,$iFromId);
	}

	/**
	 * @param array $aEventTypes
	 * @param array $aUsersList
	 * @param int $iCount
	 * @param int $iFromId
	 * @return array
	 */
	public function ReadEvents($aEventTypes,$aUsersList,$iCount=null,$iFromId=null) {
		if (!count($aUsersList)) return array();
		if (!$iCount) $iCount = Config::Get('module.stream.count_default');
		/**
		 * Если не показывать голосования
		 */
		if (Config::Get('module.stream.disable_vote_events')) {
			foreach ($aEventTypes as $i => $sType) {
				if (substr($sType, 0, 4) == 'vote') {
					unset ($aEventTypes[$i]);
				}
			}
		}
		if (!count($aEventTypes)) return array();

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
	 * Редактирование списка событий, на которые подписан юзер
	 * @param int $iUserId
	 * @param string $sType
	 * @return type
	 */
	public function switchUserEventType($iUserId, $sType) {
		if ($this->IsAllowEventType($sType)) {
			return $this->oMapper->switchUserEventType($iUserId, $sType);
		}
		return false;
	}
	/**
	 * Переключает дефолтный список типов событий у пользователя
	 *
	 * @param int $iUserId
	 */
	public function switchUserEventDefaultTypes($iUserId) {
		foreach($this->aEventDefaultTypes as $sType) {
			$this->switchUserEventType($iUserId,$sType);
		}
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
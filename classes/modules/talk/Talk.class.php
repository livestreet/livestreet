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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once('mapper/Talk.mapper.class.php');

/**
 * Модуль разговоров(почта)
 *
 */
class LsTalk extends Module {		
	protected $oMapper;	
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapper=new Mapper_Talk($this->Database_GetConnect());
		$this->oUserCurrent=$this->User_GetUserCurrent();		
	}
	/**
	 * Формирует и отправляет личное сообщение
	 *
	 * @param string $sTitle
	 * @param string $sText
	 * @param int | UserEntity_User $oUserFrom
	 * @param array | int | UserEntity_User $aUserTo
	 * @param bool $bSendNotify
	 */
	public function SendTalk($sTitle,$sText,$oUserFrom,$aUserTo,$bSendNotify=true) {
		$iUserIdFrom=$oUserFrom instanceof UserEntity_User ? $oUserFrom->getId() : (int)$oUserFrom;
		if (!is_array($aUserTo)) {
			$aUserTo=array($aUserTo);
		}
		$aUserIdTo=array($iUserIdFrom);		
		foreach ($aUserTo as $oUserTo) {
			$aUserIdTo[]=$oUserTo instanceof UserEntity_User ? $oUserTo->getId() : (int)$oUserTo;
		}
		$aUserIdTo=array_unique($aUserIdTo);
		
		$oTalk=new TalkEntity_Talk();
		$oTalk->setUserId($iUserIdFrom);
		$oTalk->setTitle($sTitle);
		$oTalk->setText($sText);
		$oTalk->setDate(date("Y-m-d H:i:s"));
		$oTalk->setDateLast(date("Y-m-d H:i:s"));
		$oTalk->setUserIp(func_getIp());
		if ($oTalk=$this->Talk_AddTalk($oTalk)) {
			foreach ($aUserIdTo as $iUserId) {
				$oTalkUser=new TalkEntity_TalkUser();
				$oTalkUser->setTalkId($oTalk->getId());
				$oTalkUser->setUserId($iUserId);
				if ($iUserId==$iUserIdFrom) {
					$oTalkUser->setDateLast(date("Y-m-d H:i:s"));
				} else {
					$oTalkUser->setDateLast(null);
				}
				$this->Talk_AddTalkUser($oTalkUser);

				if ($bSendNotify) {
					if ($iUserId!=$iUserIdFrom) {
						$oUserFrom=$this->User_GetUserById($iUserIdFrom);
						$oUserToMail=$this->User_GetUserById($iUserId);
						$this->Notify_SendTalkNew($oUserToMail,$oUserFrom,$oTalk);
					}
				}
			}
			return $oTalk;
		}
		return false;
	}
	/**
	 * Добавляет новую тему разговора
	 *
	 * @param TopicEntity_Topic $oTalk
	 * @return unknown
	 */
	public function AddTalk(TalkEntity_Talk $oTalk) {
		if ($sId=$this->oMapper->AddTalk($oTalk)) {
			$oTalk->setId($sId);	
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('talk_new',"talk_new_user_{$oTalk->getUserId()}"));		
			return $oTalk;
		}
		return false;
	}
	/**
	 * Обновление разговора
	 *
	 * @param TalkEntity_Talk $oTalk
	 */
	public function UpdateTalk(TalkEntity_Talk $oTalk) {
		return $this->oMapper->UpdateTalk($oTalk);
	}
	
	
	/**
	 * Получает дополнительные данные(объекты) для разговоров по их ID
	 *
	 */
	public function GetTalksAdditionalData($aTalkId,$aAllowData=array('user','talk_user')) {
		func_array_simpleflip($aAllowData);
		if (!is_array($aTalkId)) {
			$aTalkId=array($aTalkId);
		}
		/**
		 * Получаем "голые" разговоры
		 */
		$aTalks=$this->GetTalksByArrayId($aTalkId);
		/**
		 * Формируем ID дополнительных данных, которые нужно получить
		 */
		$aUserId=array();				
		foreach ($aTalks as $oTalk) {
			if (isset($aAllowData['user'])) {
				$aUserId[]=$oTalk->getUserId();
			}			
		}
		/**
		 * Получаем дополнительные данные
		 */
		
		$aTalkUsers=array();
		$aUsers=isset($aAllowData['user']) && is_array($aAllowData['user']) ? $this->User_GetUsersAdditionalData($aUserId,$aAllowData['user']) : $this->User_GetUsersAdditionalData($aUserId);
		
		if (isset($aAllowData['talk_user']) and $this->oUserCurrent) {
			$aTalkUsers=$this->GetTalkUsersByArray($aTalkId,$this->oUserCurrent->getId());			
		}
		/**
		 * Добавляем данные к результату - списку разговоров
		 */
		foreach ($aTalks as $oTalk) {
			if (isset($aUsers[$oTalk->getUserId()])) {
				$oTalk->setUser($aUsers[$oTalk->getUserId()]);
			} else {
				$oTalk->setUser(null); // или $oTalk->setUser(new UserEntity_User());
			}
						
			if (isset($aTalkUsers[$oTalk->getId()])) {
				$oTalk->setTalkUser($aTalkUsers[$oTalk->getId()]);				
			} else {
				$oTalk->setTalkUser(null);				
			}						
		}
		return $aTalks;
	}
	/**
	 * Получить список разговоров по списку айдишников
	 *
	 * @param unknown_type $aTalkId
	 */
	public function GetTalksByArrayId($aTalkId) {
		if (!is_array($aTalkId)) {
			$aTalkId=array($aTalkId);
		}
		$aTalkId=array_unique($aTalkId);
		$aTalks=array();
		$aTalkIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTalkId,'talk_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aTalks[$data[$sKey]->getId()]=$data[$sKey];
					} else {
						$aTalkIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких разговоров не было в кеше и делаем запрос в БД
		 */		
		$aTalkIdNeedQuery=array_diff($aTalkId,array_keys($aTalks));		
		$aTalkIdNeedQuery=array_diff($aTalkIdNeedQuery,$aTalkIdNotNeedQuery);		
		$aTalkIdNeedStore=$aTalkIdNeedQuery;
		if ($data = $this->oMapper->GetTalksByArrayId($aTalkIdNeedQuery)) {
			foreach ($data as $oTalk) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTalks[$oTalk->getId()]=$oTalk;
				$this->Cache_Set($oTalk, "talk_{$oTalk->getId()}", array(), 60*60*24*4);
				$aTalkIdNeedStore=array_diff($aTalkIdNeedStore,array($oTalk->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTalkIdNeedStore as $sId) {
			$this->Cache_Set(null, "talk_{$sId}", array(), 60*60*24*4);
		}	
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTalks=func_array_sort_by_keys($aTalks,$aTalkId);
		return $aTalks;		
	}
	/**
	 * Получить список отношений разговор-юзер по списку айдишников
	 *
	 * @param unknown_type $aTalkId
	 */
	public function GetTalkUsersByArray($aTalkId,$sUserId) {
		if (!is_array($aTalkId)) {
			$aTalkId=array($aTalkId);
		}
		$aTalkId=array_unique($aTalkId);
		$aTalkUsers=array();
		$aTalkIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTalkId,'talk_user_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aTalkUsers[$data[$sKey]->getTalkId()]=$data[$sKey];
					} else {
						$aTalkIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим чего не было в кеше и делаем запрос в БД
		 */		
		$aTalkIdNeedQuery=array_diff($aTalkId,array_keys($aTalkUsers));		
		$aTalkIdNeedQuery=array_diff($aTalkIdNeedQuery,$aTalkIdNotNeedQuery);		
		$aTalkIdNeedStore=$aTalkIdNeedQuery;
		if ($data = $this->oMapper->GetTalkUserByArray($aTalkIdNeedQuery,$sUserId)) {
			foreach ($data as $oTalkUser) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTalkUsers[$oTalkUser->getTalkId()]=$oTalkUser;
				$this->Cache_Set($oTalkUser, "talk_user_{$oTalkUser->getTalkId()}_{$oTalkUser->getUserId()}", array(), 60*60*24*4);
				$aTalkIdNeedStore=array_diff($aTalkIdNeedStore,array($oTalkUser->getTalkId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTalkIdNeedStore as $sId) {
			$this->Cache_Set(null, "talk_user_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}		
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTalkUsers=func_array_sort_by_keys($aTalkUsers,$aTalkId);
		return $aTalkUsers;		
	}
	/**
	 * Получает тему разговора по айдишнику
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetTalkById($sId) {
		$aTalks=$this->GetTalksAdditionalData($sId);
		if (isset($aTalks[$sId])) {
			return $aTalks[$sId];
		}
		return null;		
	}	
	/**
	 * Добавляет юзера к разговору(теме)
	 *
	 * @param TalkEntity_TalkUser $oTalkUser
	 * @return unknown
	 */
	public function AddTalkUser(TalkEntity_TalkUser $oTalkUser) {
		return $this->oMapper->AddTalkUser($oTalkUser);
	}
	/**
	 * Удаляет юзера из разговора
	 *
	 * @param TalkEntity_TalkUser $oTalkUser
	 * @return unknown
	 */
	public function DeleteTalkUserByArray($aTalkId,$sUserId) {
		return $this->oMapper->DeleteTalkUserByArray($aTalkId,$sUserId);
	}
	/**
	 * Есть ли юзер в этом разговоре
	 *
	 * @param unknown_type $sTalkId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTalkUser($sTalkId,$sUserId) {
		$aTalkUser=$this->GetTalkUsersByArray($sTalkId,$sUserId);
		if (isset($aTalkUser[$sTalkId])) {
			return $aTalkUser[$sTalkId];
		}
		return null;		
	}
	/**
	 * Получить все темы разговора где есть юзер
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTalksByUserId($sUserId) {
		$data=$this->oMapper->GetTalksByUserId($sUserId);
		$aTalks=$this->GetTalksAdditionalData($data);
		foreach ($aTalks as $oTalk) {
			$oTalk->setUsers($this->GetUsersTalk($oTalk->getId()));	
		}		
		return $aTalks;
	}
	/**
	 * Обновляет связку разговор-юзер
	 *
	 * @param TalkEntity_TalkUser $oTalkUser
	 * @return unknown
	 */
	public function UpdateTalkUser(TalkEntity_TalkUser $oTalkUser) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("talk_read_user_{$oTalkUser->getUserId()}"));
		return $this->oMapper->UpdateTalkUser($oTalkUser);
	}
	
	/**
	 * Получает число новых тем и комментов где есть юзер
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetCountTalkNew($sUserId) {
		if (false === ($data = $this->Cache_Get("talk_count_all_new_user_{$sUserId}"))) {			
			$data = $this->oMapper->GetCountCommentNew($sUserId)+$this->oMapper->GetCountTalkNew($sUserId);
			$this->Cache_Set($data, "talk_count_all_new_user_{$sUserId}", array("talk_new","talk_comment_new","talk_read_user_{$sUserId}"), 60*5);
		}
		return $data;		
	}
	
	
	/**
	 * Получает список юзеров в теме разговора
	 *
	 * @param unknown_type $sTalkId
	 * @return unknown
	 */
	public function GetUsersTalk($sTalkId) {
		$data=$this->oMapper->GetUsersTalk($sTalkId);
		return $this->User_GetUsersAdditionalData($data);
	}
	/**
	 * Увеличивает число новых комментов у юзеров
	 *
	 * @param unknown_type $sTalkId
	 * @param unknown_type $aExcludeId
	 * @return unknown
	 */
	public function increaseCountCommentNew($sTalkId,$aExcludeId=null) {
		return $this->oMapper->increaseCountCommentNew($sTalkId,$aExcludeId);
	}
}
?>
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
 * Модуль разговоров(почта)
 *
 */
class ModuleTalk extends Module {		
	/**
	 * Статус TalkUser в базе данных
	 */
	const TALK_USER_ACTIVE = 1;
	const TALK_USER_DELETE_BY_SELF = 2;
	const TALK_USER_DELETE_BY_AUTHOR = 4;
	
	protected $oMapper;	
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();		
	}
	/**
	 * Формирует и отправляет личное сообщение
	 *
	 * @param string $sTitle
	 * @param string $sText
	 * @param int | ModuleUser_EntityUser $oUserFrom
	 * @param array | int | ModuleUser_EntityUser $aUserTo
	 * @param bool $bSendNotify
	 */
	public function SendTalk($sTitle,$sText,$oUserFrom,$aUserTo,$bSendNotify=true,$bUseBlacklist=true) {
		$iUserIdFrom=$oUserFrom instanceof ModuleUser_EntityUser ? $oUserFrom->getId() : (int)$oUserFrom;
		if (!is_array($aUserTo)) {
			$aUserTo=array($aUserTo);
		}
		$aUserIdTo=array($iUserIdFrom);		
		if($bUseBlacklist) {
			$aUserInBlacklist=$this->GetBlacklistByTargetId($iUserIdFrom);
		}
		
		foreach ($aUserTo as $oUserTo) {
			$sUserIdTo=$oUserTo instanceof ModuleUser_EntityUser ? $oUserTo->getId() : (int)$oUserTo;
			if(!$bUseBlacklist || !in_array($sUserIdTo,$aUserInBlacklist)) {
				$aUserIdTo[]=$sUserIdTo;
			}
		}
		$aUserIdTo=array_unique($aUserIdTo);
		if(!empty($aUserIdTo)) {
			$oTalk=Engine::GetEntity('Talk');
			$oTalk->setUserId($iUserIdFrom);
			$oTalk->setTitle($sTitle);
			$oTalk->setText($sText);
			$oTalk->setDate(date("Y-m-d H:i:s"));
			$oTalk->setDateLast(date("Y-m-d H:i:s"));
			$oTalk->setUserIp(func_getIp());
			if ($oTalk=$this->AddTalk($oTalk)) {
				foreach ($aUserIdTo as $iUserId) {
					$oTalkUser=Engine::GetEntity('Talk_TalkUser');
					$oTalkUser->setTalkId($oTalk->getId());
					$oTalkUser->setUserId($iUserId);
					if ($iUserId==$iUserIdFrom) {
						$oTalkUser->setDateLast(date("Y-m-d H:i:s"));
					} else {
						$oTalkUser->setDateLast(null);
					}
					$this->AddTalkUser($oTalkUser);
	
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
		}
		return false;
	}
	/**
	 * Добавляет новую тему разговора
	 *
	 * @param ModuleTopic_EntityTopic $oTalk
	 * @return unknown
	 */
	public function AddTalk(ModuleTalk_EntityTalk $oTalk) {
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
	 * @param ModuleTalk_EntityTalk $oTalk
	 */
	public function UpdateTalk(ModuleTalk_EntityTalk $oTalk) {
		$this->Cache_Delete("talk_{$oTalk->getId()}");
		return $this->oMapper->UpdateTalk($oTalk);
	}
	
	
	/**
	 * Получает дополнительные данные(объекты) для разговоров по их ID
	 *
	 */
	public function GetTalksAdditionalData($aTalkId,$aAllowData=array('user','talk_user','favourite')) {
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
		if (isset($aAllowData['favourite']) and $this->oUserCurrent) {
			$aFavouriteTalks=$this->Favourite_GetFavouritesByArray($aTalkId,'talk',$this->oUserCurrent->getId());	
		}
		
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
				$oTalk->setUser(null); // или $oTalk->setUser(new ModuleUser_EntityUser());
			}
						
			if (isset($aTalkUsers[$oTalk->getId()])) {
				$oTalk->setTalkUser($aTalkUsers[$oTalk->getId()]);				
			} else {
				$oTalk->setTalkUser(null);				
			}

			if (isset($aFavouriteTalks[$oTalk->getId()])) {
				$oTalk->setIsFavourite(true);
			} else {
				$oTalk->setIsFavourite(false);
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
		if (Config::Get('sys.cache.solid')) {
			return $this->GetTalksByArrayIdSolid($aTalkId);
		}
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
	public function GetTalksByArrayIdSolid($aTalkId) {
		if (!is_array($aTalkId)) {
			$aTalkId=array($aTalkId);
		}
		$aTalkId=array_unique($aTalkId);
		$aTalks=array();
		$s=join(',',$aTalkId);
		if (false === ($data = $this->Cache_Get("talk_id_{$s}"))) {
			$data = $this->oMapper->GetTalksByArrayId($aTalkId);
			foreach ($data as $oTalk) {
				$aTalks[$oTalk->getId()]=$oTalk;
			}
			$this->Cache_Set($aTalks, "talk_id_{$s}", array("update_talk_user","talk_new"), 60*60*24*1);
			return $aTalks;
		}
		return $data;
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
				$this->Cache_Set($oTalkUser, "talk_user_{$oTalkUser->getTalkId()}_{$oTalkUser->getUserId()}", array("update_talk_user_{$oTalkUser->getTalkId()}"), 60*60*24*4);
				$aTalkIdNeedStore=array_diff($aTalkIdNeedStore,array($oTalkUser->getTalkId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTalkIdNeedStore as $sId) {
			$this->Cache_Set(null, "talk_user_{$sId}_{$sUserId}", array("update_talk_user_{$sId}"), 60*60*24*4);
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
			$aResult=$this->GetTalkUsersByTalkId($sId);
			foreach ((array)$aResult as $oTalkUser) {
				$aTalkUsers[$oTalkUser->getUserId()]=$oTalkUser;
			}		
			$aTalks[$sId]->setTalkUsers($aTalkUsers);
			return $aTalks[$sId];
		}
		return null;		
	}	
	/**
	 * Добавляет юзера к разговору(теме)
	 *
	 * @param ModuleTalk_EntityTalkUser $oTalkUser
	 * @return unknown
	 */
	public function AddTalkUser(ModuleTalk_EntityTalkUser $oTalkUser) {
		$this->Cache_Delete("talk_{$oTalkUser->getTalkId()}");
		$this->Cache_Clean(
			Zend_Cache::CLEANING_MODE_MATCHING_TAG,
			array(
				"update_talk_user_{$oTalkUser->getTalkId()}"
			)
		);
		return $this->oMapper->AddTalkUser($oTalkUser);
	}
	/**
	 * Удаляет юзера из разговора
	 *
	 * @param ModuleTalk_EntityTalkUser $oTalkUser
	 * @return unknown
	 */
	public function DeleteTalkUserByArray($aTalkId,$sUserId,$iAcitve=self::TALK_USER_DELETE_BY_SELF) {
		if(!is_array($aTalkId)){
			$aTalkId=array($aTalkId);
		}
		// Удаляем для каждого отметку избранного
		foreach ($aTalkId as $sTalkId) {
			$this->DeleteFavouriteTalk(
				Engine::GetEntity('Favourite',
					array(
						'target_id' => $sTalkId,
						'target_type' => 'talk',
						'user_id' => $this->oUserCurrent->getId()
					)
				)
			);
		}
		// Нужно почистить зависимые кеши
		foreach ($aTalkId as $sTalkId) {
			$this->Cache_Clean(
				Zend_Cache::CLEANING_MODE_MATCHING_TAG,
				array("update_talk_user_{$sTalkId}")
			);
		}
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("update_talk_user"));
		return $this->oMapper->DeleteTalkUserByArray($aTalkId,$sUserId,$iAcitve);
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
	 * @param  string $sUserId
	 * @param  int    $iPage
	 * @param  int    $iPerPage
	 * @return array
	 */
	public function GetTalksByUserId($sUserId,$iPage,$iPerPage) {
		$data=array(
			'collection'=>$this->oMapper->GetTalksByUserId($sUserId,$iCount,$iPage,$iPerPage),
			'count'=>$iCount
		);		
		$aTalks=$this->GetTalksAdditionalData($data['collection']);
		
		/**
		 * Добавляем данные об участниках разговора
		 */
		foreach ($aTalks as $oTalk) {
			$aResult=$this->GetTalkUsersByTalkId($oTalk->getId());
			foreach ((array)$aResult as $oTalkUser) {
				$aTalkUsers[$oTalkUser->getUserId()]=$oTalkUser;
			}
			$oTalk->setTalkUsers($aTalkUsers);
		}
		$data['collection']=$aTalks;
		return $data;
	}
	
	/**
	 * Получить все темы разговора по фильтру
	 *
	 * @param  array  $aFilter
	 * @param  int    $iPage
	 * @param  int    $iPerPage
	 * @return array
	 */
	public function GetTalksByFilter($aFilter,$iPage,$iPerPage) {
		$data=array(
			'collection'=>$this->oMapper->GetTalksByFilter($aFilter,$iCount,$iPage,$iPerPage),
			'count'=>$iCount
		);		
		$aTalks=$this->GetTalksAdditionalData($data['collection']);
		/**
		 * Добавляем данные об участниках разговора
		 */		
		foreach ($aTalks as $oTalk) {
			$aResult=$this->GetTalkUsersByTalkId($oTalk->getId());
			$aTalkUsers=array();
			foreach ((array)$aResult as $oTalkUser) {
				$aTalkUsers[$oTalkUser->getUserId()]=$oTalkUser;
			}			
			$oTalk->setTalkUsers($aTalkUsers);
		}
		$data['collection']=$aTalks;
		return $data;
	}
	
	/**
	 * Обновляет связку разговор-юзер
	 *
	 * @param ModuleTalk_EntityTalkUser $oTalkUser
	 * @return unknown
	 */
	public function UpdateTalkUser(ModuleTalk_EntityTalkUser $oTalkUser) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("talk_read_user_{$oTalkUser->getUserId()}"));
		$this->Cache_Delete("talk_user_{$oTalkUser->getTalkId()}_{$oTalkUser->getUserId()}");
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
			$this->Cache_Set($data, "talk_count_all_new_user_{$sUserId}", array("talk_new","update_talk_user","talk_read_user_{$sUserId}"), 60*60*24);
		}
		return $data;		
	}
	
	
	/**
	 * Получает список юзеров в теме разговора
	 *
	 * @param  string $sTalkId
	 * @param  array  $aActive
	 * @return array
	 */
	public function GetUsersTalk($sTalkId,$aActive=array()) {
		if(!is_array($aActive)) $aActive = array($aActive);
		
		$data=$this->oMapper->GetUsersTalk($sTalkId,$aActive);
		return $this->User_GetUsersAdditionalData($data);
	}
	
	/**
	 * Возвращает массив пользователей, участвующих в разговоре
	 *
	 * @param  string $sTalkId
	 * @return array
	 */
	public function GetTalkUsersByTalkId($sTalkId) {
		if (false === ($aTalkUsers = $this->Cache_Get("talk_relation_user_by_talk_id_{$sTalkId}"))) {				
			$aTalkUsers = $this->oMapper->GetTalkUsers($sTalkId);
			$this->Cache_Set($aTalkUsers, "talk_relation_user_by_talk_id_{$sTalkId}", array("update_talk_user_{$sTalkId}"), 60*60*24*1);
		}
		
		if($aTalkUsers) {
			$aUserId=array();
			foreach ($aTalkUsers as $oTalkUser) {
				$aUserId[]=$oTalkUser->getUserId();
			}		
			$aUsers = $this->User_GetUsersAdditionalData($aUserId);
			
			foreach ($aTalkUsers as $oTalkUser){
				if(isset($aUsers[$oTalkUser->getUserId()])) {
					$oTalkUser->setUser($aUsers[$oTalkUser->getUserId()]);
				} else {
					$oTalkUser->setUser(null);
				}
			}
		}
		return $aTalkUsers;
	}
	
	/**
	 * Увеличивает число новых комментов у юзеров
	 *
	 * @param unknown_type $sTalkId
	 * @param unknown_type $aExcludeId
	 * @return unknown
	 */
	public function increaseCountCommentNew($sTalkId,$aExcludeId=null) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("update_talk_user_{$sTalkId}"));
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("update_talk_user"));
		return $this->oMapper->increaseCountCommentNew($sTalkId,$aExcludeId);
	}
	
	/**
	 * Получает привязку письма к ибранному(добавлено ли письмо в избранное у юзера)
	 *
	 * @param  string $sTalkId
	 * @param  string $sUserId
	 * @return ModuleFavourite_EntityFavourite|null
	 */
	public function GetFavouriteTalk($sTalkId,$sUserId) {
		return $this->Favourite_GetFavourite($sTalkId,'talk',$sUserId);
	}
	
	/**
	 * Получить список избранного по списку айдишников
	 *
	 * @param array $aTalkId
	 */
	public function GetFavouriteTalkByArray($aTalkId,$sUserId) {
		return $this->Favourite_GetFavouritesByArray($aTalkId,'talk',$sUserId);
	}

	/**
	 * Получить список избранного по списку айдишников, но используя единый кеш
	 *
	 * @param array  $aTalkId
	 * @param int    $sUserId
	 * @return array
	 */
	public function GetFavouriteTalksByArraySolid($aTalkId,$sUserId) {
		return $this->Favourite_GetFavouritesByArraySolid($aTalkId,'talk',$sUserId);
	}

	/**
	 * Получает список писем из избранного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int    $iCount
	 * @param  int    $iCurrPage
	 * @param  int    $iPerPage
	 * @return array
	 */
	public function GetTalksFavouriteByUserId($sUserId,$iCurrPage,$iPerPage) {		
		// Получаем список идентификаторов избранных комментов
		$data = $this->Favourite_GetFavouritesByUserId($sUserId,'talk',$iCurrPage,$iPerPage);
		// Получаем комменты по переданому массиву айдишников
		$aTalks=$this->GetTalksAdditionalData($data['collection']);
		
		/**
		 * Добавляем данные об участниках разговора
		 */		
		foreach ($aTalks as $oTalk) {
			$aResult=$this->GetTalkUsersByTalkId($oTalk->getId());
			$aTalkUsers=array();
			foreach ((array)$aResult as $oTalkUser) {
				$aTalkUsers[$oTalkUser->getUserId()]=$oTalkUser;
			}			
			$oTalk->setTalkUsers($aTalkUsers);
		}
		$data['collection']=$aTalks;	
		return $data;
	}
	/**
	 * Возвращает число писем в избранном
	 *
	 * @param  string $sUserId
	 * @return int
	 */
	public function GetCountTalksFavouriteByUserId($sUserId) {
		return $this->Favourite_GetCountFavouritesByUserId($sUserId,'talk');	
	}	
	/**
	 * Добавляет письмо в избранное
	 *
	 * @param  ModuleFavourite_EntityFavourite $oFavourite
	 * @return bool
	 */
	public function AddFavouriteTalk(ModuleFavourite_EntityFavourite $oFavourite) {	
		return ($oFavourite->getTargetType()=='talk') 
			? $this->Favourite_AddFavourite($oFavourite)
			: false;
	}
	/**
	 * Удаляет письмо из избранного
	 *
	 * @param  ModuleFavourite_EntityFavourite $oFavourite
	 * @return bool
	 */
	public function DeleteFavouriteTalk(ModuleFavourite_EntityFavourite $oFavourite) {
		return ($oFavourite->getTargetType()=='talk') 
			? $this->Favourite_DeleteFavourite($oFavourite)
			: false;
	}	
	/**
	 * Получает информацию о пользователях, занесенных в блеклист
	 *
	 * @param  string $sUserId
	 * @return array
	 */
	public function GetBlacklistByUserId($sUserId) {
		$data=$this->oMapper->GetBlacklistByUserId($sUserId);
		return $this->User_GetUsersAdditionalData($data);
	}
	
	/**
	 * Возвращает пользователей, у которых данный занесен в Blacklist
	 *
	 * @param  string $sUserId
	 * @return array
	 */
	public function GetBlacklistByTargetId($sUserId) {
		return $this->oMapper->GetBlacklistByTargetId($sUserId);
	}
	
	/**
	 * Добавление пользователя в блеклист по переданному идентификатору
	 *
	 * @param  string $sTargetId
	 * @param  string $sUserId
	 * @return bool
	 */
	public function AddUserToBlacklist($sTargetId, $sUserId) {
		return $this->oMapper->AddUserToBlacklist($sTargetId, $sUserId);
	}
	/**
	 * Добавление пользователя в блеклист по списку идентификаторов
	 *
	 * @param  array $aTargetId
	 * @param  string $sUserId
	 * @return bool
	 */
	public function AddUserArrayToBlacklist($aTargetId, $sUserId) {
		foreach ((array)$aTargetId as $oUser) {
			$aUsersId[]=$oUser instanceof ModuleUser_EntityUser ? $oUser->getId() : (int)$oUser;
		}		
		return $this->oMapper->AddUserArrayToBlacklist($aUsersId, $sUserId);
	}
	/**
	 * Удаляем пользователя из блеклиста
	 *
	 * @param  string $sTargetId
	 * @param  string $sUserId
	 * @return bool
	 */
	public function DeleteUserFromBlacklist($sTargetId, $sUserId) {
		return $this->oMapper->DeleteUserFromBlacklist($sTargetId, $sUserId);	
	}
	
	/**
	 * Возвращает список последних инбоксов пользователя,
	 * отправленных не более чем $iTimeLimit секунд назад
	 *
	 * @param  string $sUserId
	 * @param  int    $iTimeLimit
	 * @param  int    $iCountLimit
	 * @return array
	 */
	public function GetLastTalksByUserId($sUserId,$iTimeLimit,$iCountLimit=1) {
		$aFilter = array(
			'sender_id' => $sUserId,
			'date_min' => date("Y-m-d H:i:s",time()-$iTimeLimit),
		);
		$aTalks = $this->GetTalksByFilter($aFilter,1,$iCountLimit);

		return $aTalks;
	}
}
?>
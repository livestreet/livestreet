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
 * Модуль для работы с пользователями
 *
 * @package modules.user
 * @since 1.0
 */
class ModuleUser extends Module {
	/**
	 * Статусы дружбы между пользователями
	 */
	const USER_FRIEND_OFFER  = 1;
	const USER_FRIEND_ACCEPT = 2;
	const USER_FRIEND_DELETE = 4;
	const USER_FRIEND_REJECT = 8;
	const USER_FRIEND_NULL   = 16;
	/**
	 * Объект маппера
	 *
	 * @var ModuleUser_MapperUser
	 */
	protected $oMapper;
	/**
	 * Объект текущего пользователя
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent=null;
	/**
	 * Объект сессии текущего пользователя
	 *
	 * @var ModuleUser_EntitySession|null
	 */
	protected $oSession=null;
	/**
	 * Список типов пользовательских полей
	 *
	 * @var array
	 */
	protected $aUserFieldTypes=array(
		'social','contact'
	);

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
		/**
		 * Проверяем есть ли у юзера сессия, т.е. залогинен или нет
		 */
		$sUserId=$this->Session_Get('user_id');
		if ($sUserId and $oUser=$this->GetUserById($sUserId) and $oUser->getActivate()) {
			if ($this->oSession=$oUser->getSession()) {
				/**
				 * Сюда можно вставить условие на проверку айпишника сессии
				 */
				$this->oUserCurrent=$oUser;
			}
		}
		/**
		 * Запускаем автозалогинивание
		 * В куках стоит время на сколько запоминать юзера
		 */
		$this->AutoLogin();
		/**
		 * Обновляем сессию
		 */
		if (isset($this->oSession)) {
			$this->UpdateSession();
		}
	}
	/**
	 * Возвращает список типов полей
	 *
	 * @return array
	 */
	public function GetUserFieldTypes() {
		return $this->aUserFieldTypes;
	}
	/**
	 * Добавляет новый тип с пользовательские поля
	 *
	 * @param string $sType	Тип
	 * @return bool
	 */
	public function AddUserFieldTypes($sType) {
		if (!in_array($sType,$this->aUserFieldTypes)) {
			$this->aUserFieldTypes[]=$sType;
			return true;
		}
		return false;
	}
	/**
	 * Получает дополнительные данные(объекты) для юзеров по их ID
	 *
	 * @param array $aUserId	Список ID пользователей
	 * @param array|null $aAllowData	Список типод дополнительных данных для подгрузки у пользователей
	 * @return array
	 */
	public function GetUsersAdditionalData($aUserId,$aAllowData=null) {
		if (is_null($aAllowData)) {
			$aAllowData=array('vote','session','friend','geo_target');
		}
		func_array_simpleflip($aAllowData);
		if (!is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		/**
		 * Получаем юзеров
		 */
		$aUsers=$this->GetUsersByArrayId($aUserId);
		/**
		 * Получаем дополнительные данные
		 */
		$aSessions=array();
		$aFriends=array();
		$aVote=array();
		$aGeoTargets=array();
		if (isset($aAllowData['session'])) {
			$aSessions=$this->GetSessionsByArrayId($aUserId);
		}
		if (isset($aAllowData['friend']) and $this->oUserCurrent) {
			$aFriends=$this->GetFriendsByArray($aUserId,$this->oUserCurrent->getId());
		}

		if (isset($aAllowData['vote']) and $this->oUserCurrent) {
			$aVote=$this->Vote_GetVoteByArray($aUserId,'user',$this->oUserCurrent->getId());
		}
		if (isset($aAllowData['geo_target'])) {
			$aGeoTargets=$this->Geo_GetTargetsByTargetArray('user',$aUserId);
		}
		/**
		 * Добавляем данные к результату
		 */
		foreach ($aUsers as $oUser) {
			if (isset($aSessions[$oUser->getId()])) {
				$oUser->setSession($aSessions[$oUser->getId()]);
			} else {
				$oUser->setSession(null); // или $oUser->setSession(new ModuleUser_EntitySession());
			}
			if ($aFriends&&isset($aFriends[$oUser->getId()])) {
				$oUser->setUserFriend($aFriends[$oUser->getId()]);
			} else {
				$oUser->setUserFriend(null);
			}

			if (isset($aVote[$oUser->getId()])) {
				$oUser->setVote($aVote[$oUser->getId()]);
			} else {
				$oUser->setVote(null);
			}
			if (isset($aGeoTargets[$oUser->getId()])) {
				$aTargets=$aGeoTargets[$oUser->getId()];
				$oUser->setGeoTarget(isset($aTargets[0]) ? $aTargets[0] : null);
			} else {
				$oUser->setGeoTarget(null);
			}
		}

		return $aUsers;
	}
	/**
	 * Список юзеров по ID
	 *
	 * @param array $aUserId Список ID пользователей
	 * @return array
	 */
	public function GetUsersByArrayId($aUserId) {
		if (!$aUserId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetUsersByArrayIdSolid($aUserId);
		}
		if (!is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		$aUserId=array_unique($aUserId);
		$aUsers=array();
		$aUserIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aUserId,'user_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey]) {
						$aUsers[$data[$sKey]->getId()]=$data[$sKey];
					} else {
						$aUserIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких юзеров не было в кеше и делаем запрос в БД
		 */
		$aUserIdNeedQuery=array_diff($aUserId,array_keys($aUsers));
		$aUserIdNeedQuery=array_diff($aUserIdNeedQuery,$aUserIdNotNeedQuery);
		$aUserIdNeedStore=$aUserIdNeedQuery;
		if ($data = $this->oMapper->GetUsersByArrayId($aUserIdNeedQuery)) {
			foreach ($data as $oUser) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aUsers[$oUser->getId()]=$oUser;
				$this->Cache_Set($oUser, "user_{$oUser->getId()}", array(), 60*60*24*4);
				$aUserIdNeedStore=array_diff($aUserIdNeedStore,array($oUser->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aUserIdNeedStore as $sId) {
			$this->Cache_Set(null, "user_{$sId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aUsers=func_array_sort_by_keys($aUsers,$aUserId);
		return $aUsers;
	}
	/**
	 * Алиас для корректной работы ORM
	 *
	 * @param array $aUserId	Список ID пользователей
	 * @return array
	 */
	public function GetUserItemsByArrayId($aUserId) {
		return $this->GetUsersByArrayId($aUserId);
	}
	/**
	 * Получение пользователей по списку ID используя общий кеш
	 *
	 * @param array $aUserId	Список ID пользователей
	 * @return array
	 */
	public function GetUsersByArrayIdSolid($aUserId) {
		if (!is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		$aUserId=array_unique($aUserId);
		$aUsers=array();
		$s=join(',',$aUserId);
		if (false === ($data = $this->Cache_Get("user_id_{$s}"))) {
			$data = $this->oMapper->GetUsersByArrayId($aUserId);
			foreach ($data as $oUser) {
				$aUsers[$oUser->getId()]=$oUser;
			}
			$this->Cache_Set($aUsers, "user_id_{$s}", array("user_update","user_new"), 60*60*24*1);
			return $aUsers;
		}
		return $data;
	}
	/**
	 * Список сессий юзеров по ID
	 *
	 * @param array $aUserId	Список ID пользователей
	 * @return array
	 */
	public function GetSessionsByArrayId($aUserId) {
		if (!$aUserId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetSessionsByArrayIdSolid($aUserId);
		}
		if (!is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		$aUserId=array_unique($aUserId);
		$aSessions=array();
		$aUserIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aUserId,'user_session_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey] and $data[$sKey]['session']) {
						$aSessions[$data[$sKey]['session']->getUserId()]=$data[$sKey]['session'];
					} else {
						$aUserIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких юзеров не было в кеше и делаем запрос в БД
		 */
		$aUserIdNeedQuery=array_diff($aUserId,array_keys($aSessions));
		$aUserIdNeedQuery=array_diff($aUserIdNeedQuery,$aUserIdNotNeedQuery);
		$aUserIdNeedStore=$aUserIdNeedQuery;
		if ($data = $this->oMapper->GetSessionsByArrayId($aUserIdNeedQuery)) {
			foreach ($data as $oSession) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aSessions[$oSession->getUserId()]=$oSession;
				$this->Cache_Set(array('time'=>time(),'session'=>$oSession), "user_session_{$oSession->getUserId()}", array(), 60*60*24*4);
				$aUserIdNeedStore=array_diff($aUserIdNeedStore,array($oSession->getUserId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aUserIdNeedStore as $sId) {
			$this->Cache_Set(array('time'=>time(),'session'=>null), "user_session_{$sId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aSessions=func_array_sort_by_keys($aSessions,$aUserId);
		return $aSessions;
	}
	/**
	 * Получить список сессий по списку айдишников, но используя единый кеш
	 *
	 * @param array $aUserId	Список ID пользователей
	 * @return array
	 */
	public function GetSessionsByArrayIdSolid($aUserId) {
		if (!is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		$aUserId=array_unique($aUserId);
		$aSessions=array();
		$s=join(',',$aUserId);
		if (false === ($data = $this->Cache_Get("user_session_id_{$s}"))) {
			$data = $this->oMapper->GetSessionsByArrayId($aUserId);
			foreach ($data as $oSession) {
				$aSessions[$oSession->getUserId()]=$oSession;
			}
			$this->Cache_Set($aSessions, "user_session_id_{$s}", array("user_session_update"), 60*60*24*1);
			return $aSessions;
		}
		return $data;
	}
	/**
	 * Получает сессию юзера
	 *
	 * @param int $sUserId	ID пользователя
	 * @return ModuleUser_EntitySession|null
	 */
	public function GetSessionByUserId($sUserId) {
		$aSessions=$this->GetSessionsByArrayId($sUserId);
		if (isset($aSessions[$sUserId])) {
			return $aSessions[$sUserId];
		}
		return null;
	}
	/**
	 * При завершенни модуля загружаем в шалон объект текущего юзера
	 *
	 */
	public function Shutdown() {
		if ($this->oUserCurrent) {
			$this->Viewer_Assign('iUserCurrentCountTalkNew',$this->Talk_GetCountTalkNew($this->oUserCurrent->getId()));
			$this->Viewer_Assign('iUserCurrentCountTopicDraft',$this->Topic_GetCountDraftTopicsByUserId($this->oUserCurrent->getId()));
		}
		$this->Viewer_Assign('oUserCurrent',$this->oUserCurrent);
	}
	/**
	 * Добавляет юзера
	 *
	 * @param ModuleUser_EntityUser $oUser	Объект пользователя
	 * @return ModuleUser_EntityUser|bool
	 */
	public function Add(ModuleUser_EntityUser $oUser) {
		if ($sId=$this->oMapper->Add($oUser)) {
			$oUser->setId($sId);
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_new'));
			/**
			 * Создаем персональный блог
			 */
			$this->Blog_CreatePersonalBlog($oUser);
			return $oUser;
		}
		return false;
	}
	/**
	 * Получить юзера по ключу активации
	 *
	 * @param string $sKey	Ключ активации
	 * @return ModuleUser_EntityUser|null
	 */
	public function GetUserByActivateKey($sKey) {
		$id=$this->oMapper->GetUserByActivateKey($sKey);
		return $this->GetUserById($id);
	}
	/**
	 * Получить юзера по ключу сессии
	 *
	 * @param string $sKey	Сессионный ключ
	 * @return ModuleUser_EntityUser|null
	 */
	public function GetUserBySessionKey($sKey) {
		$id=$this->oMapper->GetUserBySessionKey($sKey);
		return $this->GetUserById($id);
	}
	/**
	 * Получить юзера по мылу
	 *
	 * @param string $sMail	Емайл
	 * @return ModuleUser_EntityUser|null
	 */
	public function GetUserByMail($sMail) {
		$id=$this->oMapper->GetUserByMail($sMail);
		return $this->GetUserById($id);
	}
	/**
	 * Получить юзера по логину
	 *
	 * @param string $sLogin Логин пользователя
	 * @return ModuleUser_EntityUser|null
	 */
	public function GetUserByLogin($sLogin) {
		$s=strtolower($sLogin);
		if (false === ($id = $this->Cache_Get("user_login_{$s}"))) {
			if ($id = $this->oMapper->GetUserByLogin($sLogin)) {
				$this->Cache_Set($id, "user_login_{$s}", array(), 60*60*24*1);
			}
		}
		return $this->GetUserById($id);
	}
	/**
	 * Получить юзера по айдишнику
	 *
	 * @param int $sId	ID пользователя
	 * @return ModuleUser_EntityUser|null
	 */
	public function GetUserById($sId) {
		$aUsers=$this->GetUsersAdditionalData($sId);
		if (isset($aUsers[$sId])) {
			return $aUsers[$sId];
		}
		return null;
	}
	/**
	 * Обновляет юзера
	 *
	 * @param ModuleUser_EntityUser $oUser	Объект пользователя
	 * @return bool
	 */
	public function Update(ModuleUser_EntityUser $oUser) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_update'));
		$this->Cache_Delete("user_{$oUser->getId()}");
		return $this->oMapper->Update($oUser);
	}
	/**
	 * Авторизовывает юзера
	 *
	 * @param ModuleUser_EntityUser $oUser	Объект пользователя
	 * @param bool $bRemember	Запоминать пользователя или нет
	 * @param string $sKey	Ключ авторизации для куков
	 * @return bool
	 */
	public function Authorization(ModuleUser_EntityUser $oUser,$bRemember=true,$sKey=null) {
		if (!$oUser->getId() or !$oUser->getActivate()) {
			return false;
		}
		/**
		 * Генерим новый ключ авторизаии для куков
		 */
		if(is_null($sKey)){
			$sKey=md5(func_generator().time().$oUser->getLogin());
		}
		/**
		 * Создаём новую сессию
		 */
		if (!$this->CreateSession($oUser,$sKey)) {
			return false;
		}
		/**
		 * Запоминаем в сесси юзера
		 */
		$this->Session_Set('user_id',$oUser->getId());
		$this->oUserCurrent=$oUser;
		/**
		 * Ставим куку
		 */
		if ($bRemember) {
			setcookie('key',$sKey,time()+Config::Get('sys.cookie.time'),Config::Get('sys.cookie.path'),Config::Get('sys.cookie.host'));
		}
		return true;
	}
	/**
	 * Автоматическое заллогинивание по ключу из куков
	 *
	 */
	protected function AutoLogin() {
		if ($this->oUserCurrent) {
			return;
		}
		if (isset($_COOKIE['key']) and is_string($_COOKIE['key']) and $sKey=$_COOKIE['key']) {
			if ($oUser=$this->GetUserBySessionKey($sKey)) {
				$this->Authorization($oUser);
			} else {
				$this->Logout();
			}
		}
	}
	/**
	 * Авторизован ли юзер
	 *
	 * @return bool
	 */
	public function IsAuthorization() {
		if ($this->oUserCurrent) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Получить текущего юзера
	 *
	 * @return ModuleUser_EntityUser|null
	 */
	public function GetUserCurrent() {
		return $this->oUserCurrent;
	}
	/**
	 * Разлогинивание
	 *
	 */
	public function Logout() {
		$this->oUserCurrent=null;
		$this->oSession=null;
		/**
		 * Дропаем из сессии
		 */
		$this->Session_Drop('user_id');
		/**
		 * Дропаем куку
		 */
		setcookie('key','',1,Config::Get('sys.cookie.path'),Config::Get('sys.cookie.host'));
	}
	/**
	 * Обновление данных сессии
	 * Важный момент: сессию обновляем в кеше и раз в 10 минут скидываем в БД
	 */
	protected function UpdateSession() {
		$this->oSession->setDateLast(date("Y-m-d H:i:s"));
		$this->oSession->setIpLast(func_getIp());
		if (false === ($data = $this->Cache_Get("user_session_{$this->oSession->getUserId()}"))) {
			$data=array(
				'time'=>time(),
				'session'=>$this->oSession
			);
		} else {
			$data['session']=$this->oSession;
		}
		if (!Config::Get('sys.cache.use') or $data['time']<time()-60*10) {
			$data['time']=time();
			$this->oMapper->UpdateSession($this->oSession);
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_session_update'));
		}
		$this->Cache_Set($data, "user_session_{$this->oSession->getUserId()}", array(), 60*60*24*4);
	}
	/**
	 * Создание пользовательской сессии
	 *
	 * @param ModuleUser_EntityUser $oUser	Объект пользователя
	 * @param string $sKey	Сессионный ключ
	 * @return bool
	 */
	protected function CreateSession(ModuleUser_EntityUser $oUser,$sKey) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_session_update'));
		$this->Cache_Delete("user_session_{$oUser->getId()}");
		$oSession=Engine::GetEntity('User_Session');
		$oSession->setUserId($oUser->getId());
		$oSession->setKey($sKey);
		$oSession->setIpLast(func_getIp());
		$oSession->setIpCreate(func_getIp());
		$oSession->setDateLast(date("Y-m-d H:i:s"));
		$oSession->setDateCreate(date("Y-m-d H:i:s"));
		if ($this->oMapper->CreateSession($oSession)) {
			$this->oSession=$oSession;
			return true;
		}
		return false;
	}
	/**
	 * Получить список юзеров по дате последнего визита
	 *
	 * @param int $iLimit Количество
	 * @return array
	 */
	public function GetUsersByDateLast($iLimit=20) {
		if ($this->IsAuthorization()) {
			$data=$this->oMapper->GetUsersByDateLast($iLimit);
		} elseif (false === ($data = $this->Cache_Get("user_date_last_{$iLimit}"))) {
			$data = $this->oMapper->GetUsersByDateLast($iLimit);
			$this->Cache_Set($data, "user_date_last_{$iLimit}", array("user_session_update"), 60*60*24*2);
		}
		$data=$this->GetUsersAdditionalData($data);
		return $data;
	}
	/**
	 * Возвращает список пользователей по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param array $aOrder	Сортировка
	 * @param int $iCurrPage	Номер страницы
	 * @param int $iPerPage	Количество элментов на страницу
	 * @param array $aAllowData	Список типо данных для подгрузки к пользователям
	 * @return array('collection'=>array,'count'=>int)
	 */
	public function GetUsersByFilter($aFilter,$aOrder,$iCurrPage,$iPerPage,$aAllowData=null) {
		$sKey="user_filter_".serialize($aFilter).serialize($aOrder)."_{$iCurrPage}_{$iPerPage}";
		if (false === ($data = $this->Cache_Get($sKey))) {
			$data = array('collection'=>$this->oMapper->GetUsersByFilter($aFilter,$aOrder,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, $sKey, array("user_update","user_new"), 60*60*24*2);
		}
		$data['collection']=$this->GetUsersAdditionalData($data['collection'],$aAllowData);
		return $data;
	}
	/**
	 * Получить список юзеров по дате регистрации
	 *
	 * @param int $iLimit	Количество
	 * @return array
	 */
	public function GetUsersByDateRegister($iLimit=20) {
		$aResult=$this->GetUsersByFilter(array('activate'=>1),array('id'=>'desc'),1,$iLimit);
		return $aResult['collection'];
	}
	/**
	 * Получить статистику по юзерам
	 *
	 * @return array
	 */
	public function GetStatUsers() {
		if (false === ($aStat = $this->Cache_Get("user_stats"))) {
			$aStat['count_all']=$this->oMapper->GetCountUsers();
			$sDate=date("Y-m-d H:i:s",time()-Config::Get('module.user.time_active'));
			$aStat['count_active']=$this->oMapper->GetCountUsersActive($sDate);
			$aStat['count_inactive']=$aStat['count_all']-$aStat['count_active'];
			$aSex=$this->oMapper->GetCountUsersSex();
			$aStat['count_sex_man']=(isset($aSex['man']) ? $aSex['man']['count'] : 0);
			$aStat['count_sex_woman']=(isset($aSex['woman']) ? $aSex['woman']['count'] : 0);
			$aStat['count_sex_other']=(isset($aSex['other']) ? $aSex['other']['count'] : 0);

			$this->Cache_Set($aStat, "user_stats", array("user_update","user_new"), 60*60*24*4);
		}
		return $aStat;
	}
	/**
	 * Получить список юзеров по первым  буквам логина
	 *
	 * @param string $sUserLogin	Логин
	 * @param int $iLimit	Количество
	 * @return array
	 */
	public function GetUsersByLoginLike($sUserLogin,$iLimit) {
		if (false === ($data = $this->Cache_Get("user_like_{$sUserLogin}_{$iLimit}"))) {
			$data = $this->oMapper->GetUsersByLoginLike($sUserLogin,$iLimit);
			$this->Cache_Set($data, "user_like_{$sUserLogin}_{$iLimit}", array("user_new"), 60*60*24*2);
		}
		$data=$this->GetUsersAdditionalData($data);
		return $data;
	}
	/**
	 * Получить список отношений друзей
	 *
	 * @param  array $aUserId	Список ID пользователей проверяемых на дружбу
	 * @param  int $sUserId	ID пользователя у которого проверяем друзей
	 * @return array
	 */
	public function GetFriendsByArray($aUserId,$sUserId) {
		if (!$aUserId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetFriendsByArraySolid($aUserId,$sUserId);
		}
		if (!is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		$aUserId=array_unique($aUserId);
		$aFriends=array();
		$aUserIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aUserId,'user_friend_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey]) {
						$aFriends[$data[$sKey]->getFriendId()]=$data[$sKey];
					} else {
						$aUserIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких френдов не было в кеше и делаем запрос в БД
		 */
		$aUserIdNeedQuery=array_diff($aUserId,array_keys($aFriends));
		$aUserIdNeedQuery=array_diff($aUserIdNeedQuery,$aUserIdNotNeedQuery);
		$aUserIdNeedStore=$aUserIdNeedQuery;
		if ($data = $this->oMapper->GetFriendsByArrayId($aUserIdNeedQuery,$sUserId)) {
			foreach ($data as $oFriend) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aFriends[$oFriend->getFriendId($sUserId)]=$oFriend;
				/**
				 * Тут кеш нужно будет продумать как-то по другому.
				 * Пока не трогаю, ибо этот код все равно не выполняется.
				 * by Kachaev
				 */
				$this->Cache_Set($oFriend, "user_friend_{$oFriend->getFriendId()}_{$oFriend->getUserId()}", array(), 60*60*24*4);
				$aUserIdNeedStore=array_diff($aUserIdNeedStore,array($oFriend->getFriendId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aUserIdNeedStore as $sId) {
			$this->Cache_Set(null, "user_friend_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aFriends=func_array_sort_by_keys($aFriends,$aUserId);
		return $aFriends;
	}
	/**
	 * Получить список отношений друзей используя единый кеш
	 *
	 * @param  array $aUserId	Список ID пользователей проверяемых на дружбу
	 * @param  int $sUserId	ID пользователя у которого проверяем друзей
	 * @return array
	 */
	public function GetFriendsByArraySolid($aUserId,$sUserId) {
		if (!is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		$aUserId=array_unique($aUserId);
		$aFriends=array();
		$s=join(',',$aUserId);
		if (false === ($data = $this->Cache_Get("user_friend_{$sUserId}_id_{$s}"))) {
			$data = $this->oMapper->GetFriendsByArrayId($aUserId,$sUserId);
			foreach ($data as $oFriend) {
				$aFriends[$oFriend->getFriendId($sUserId)]=$oFriend;
			}

			$this->Cache_Set($aFriends, "user_friend_{$sUserId}_id_{$s}", array("friend_change_user_{$sUserId}"), 60*60*24*1);
			return $aFriends;
		}
		return $data;
	}
	/**
	 * Получаем привязку друга к юзеру(есть ли у юзера данный друг)
	 *
	 * @param  int $sFriendId	ID пользователя друга
	 * @param  int $sUserId	ID пользователя
	 * @return ModuleUser_EntityFriend|null
	 */
	public function GetFriend($sFriendId,$sUserId) {
		$data=$this->GetFriendsByArray($sFriendId,$sUserId);
		if (isset($data[$sFriendId])) {
			return $data[$sFriendId];
		}
		return null;
	}
	/**
	 * Добавляет друга
	 *
	 * @param  ModuleUser_EntityFriend $oFriend	Объект дружбы(связи пользователей)
	 * @return bool
	 */
	public function AddFriend(ModuleUser_EntityFriend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");

		return $this->oMapper->AddFriend($oFriend);
	}
	/**
	 * Удаляет друга
	 *
	 * @param  ModuleUser_EntityFriend $oFriend Объект дружбы(связи пользователей)
	 * @return bool
	 */
	public function DeleteFriend(ModuleUser_EntityFriend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");

		// устанавливаем статус дружбы "удалено"
		$oFriend->setStatusByUserId(ModuleUser::USER_FRIEND_DELETE,$oFriend->getUserId());
		return $this->oMapper->UpdateFriend($oFriend);
	}
	/**
	 * Удаляет информацию о дружбе из базы данных
	 *
	 * @param  ModuleUser_EntityFriend $oFriend	Объект дружбы(связи пользователей)
	 * @return bool
	 */
	public function EraseFriend(ModuleUser_EntityFriend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
		return $this->oMapper->EraseFriend($oFriend);
	}
	/**
	 * Обновляет информацию о друге
	 *
	 * @param  ModuleUser_EntityFriend $oFriend	Объект дружбы(связи пользователей)
	 * @return bool
	 */
	public function UpdateFriend(ModuleUser_EntityFriend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
		return $this->oMapper->UpdateFriend($oFriend);
	}
	/**
	 * Получает список друзей
	 *
	 * @param  int $sUserId	ID пользователя
	 * @param  int $iPage	Номер страницы
	 * @param  int $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetUsersFriend($sUserId,$iPage=1,$iPerPage=10) {
		$sKey="user_friend_{$sUserId}_{$iPage}_{$iPerPage}";
		if (false === ($data = $this->Cache_Get($sKey))) {
			$data = array('collection'=>$this->oMapper->GetUsersFriend($sUserId,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, $sKey, array("friend_change_user_{$sUserId}"), 60*60*24*2);
		}
		$data['collection']=$this->GetUsersAdditionalData($data['collection']);
		return $data;
	}
	/**
	 * Получает количество друзей
	 *
	 * @param  int $sUserId	ID пользователя
	 * @return int
	 */
	public function GetCountUsersFriend($sUserId) {
		$sKey="count_user_friend_{$sUserId}";
		if (false === ($data = $this->Cache_Get($sKey))) {
			$data = $this->oMapper->GetCountUsersFriend($sUserId);
			$this->Cache_Set($data, $sKey, array("friend_change_user_{$sUserId}"), 60*60*24*2);
		}
		return $data;
	}
	/**
	 * Получает инвайт по его коду
	 *
	 * @param  string $sCode	Код инвайта
	 * @param  int    $iUsed	Флаг испольщования инвайта
	 * @return ModuleUser_EntityInvite|null
	 */
	public function GetInviteByCode($sCode,$iUsed=0) {
		return $this->oMapper->GetInviteByCode($sCode,$iUsed);
	}
	/**
	 * Добавляет новый инвайт
	 *
	 * @param ModuleUser_EntityInvite $oInvite	Объект инвайта
	 * @return ModuleUser_EntityInvite|bool
	 */
	public function AddInvite(ModuleUser_EntityInvite $oInvite) {
		if ($sId=$this->oMapper->AddInvite($oInvite)) {
			$oInvite->setId($sId);
			return $oInvite;
		}
		return false;
	}
	/**
	 * Обновляет инвайт
	 *
	 * @param ModuleUser_EntityInvite $oInvite	бъект инвайта
	 * @return bool
	 */
	public function UpdateInvite(ModuleUser_EntityInvite $oInvite) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("invate_new_to_{$oInvite->getUserToId()}","invate_new_from_{$oInvite->getUserFromId()}"));
		return $this->oMapper->UpdateInvite($oInvite);
	}
	/**
	 * Генерирует новый инвайт
	 *
	 * @param ModuleUser_EntityUser $oUser	Объект пользователя
	 * @return ModuleUser_EntityInvite|bool
	 */
	public function GenerateInvite($oUser) {
		$oInvite=Engine::GetEntity('User_Invite');
		$oInvite->setCode(func_generator(32));
		$oInvite->setDateAdd(date("Y-m-d H:i:s"));
		$oInvite->setUserFromId($oUser->getId());
		return $this->AddInvite($oInvite);
	}
	/**
	 * Получает число использованых приглашений юзером за определенную дату
	 *
	 * @param int $sUserIdFrom	ID пользователя
	 * @param string $sDate	Дата
	 * @return int
	 */
	public function GetCountInviteUsedByDate($sUserIdFrom,$sDate) {
		return $this->oMapper->GetCountInviteUsedByDate($sUserIdFrom,$sDate);
	}
	/**
	 * Получает полное число использованных приглашений юзера
	 *
	 * @param int $sUserIdFrom	ID пользователя
	 * @return int
	 */
	public function GetCountInviteUsed($sUserIdFrom) {
		return $this->oMapper->GetCountInviteUsed($sUserIdFrom);
	}
	/**
	 * Получаем число доступных приглашений для юзера
	 *
	 * @param ModuleUser_EntityUser $oUserFrom Объект пользователя
	 * @return int
	 */
	public function GetCountInviteAvailable(ModuleUser_EntityUser $oUserFrom) {
		$sDay=7;
		$iCountUsed=$this->GetCountInviteUsedByDate($oUserFrom->getId(),date("Y-m-d 00:00:00",mktime(0,0,0,date("m"),date("d")-$sDay,date("Y"))));
		$iCountAllAvailable=round($oUserFrom->getRating()+$oUserFrom->getSkill());
		$iCountAllAvailable = $iCountAllAvailable<0 ? 0 : $iCountAllAvailable;
		$iCountAvailable=$iCountAllAvailable-$iCountUsed;
		$iCountAvailable = $iCountAvailable<0 ? 0 : $iCountAvailable;
		return $iCountAvailable;
	}
	/**
	 * Получает список приглашенных юзеров
	 *
	 * @param int $sUserId	ID пользователя
	 * @return array
	 */
	public function GetUsersInvite($sUserId) {
		if (false === ($data = $this->Cache_Get("users_invite_{$sUserId}"))) {
			$data = $this->oMapper->GetUsersInvite($sUserId);
			$this->Cache_Set($data, "users_invite_{$sUserId}", array("invate_new_from_{$sUserId}"), 60*60*24*1);
		}
		$data=$this->GetUsersAdditionalData($data);
		return $data;
	}
	/**
	 * Получает юзера который пригласил
	 *
	 * @param int $sUserIdTo	ID пользователя
	 * @return ModuleUser_EntityUser|null
	 */
	public function GetUserInviteFrom($sUserIdTo) {
		if (false === ($id = $this->Cache_Get("user_invite_from_{$sUserIdTo}"))) {
			$id = $this->oMapper->GetUserInviteFrom($sUserIdTo);
			$this->Cache_Set($id, "user_invite_from_{$sUserIdTo}", array("invate_new_to_{$sUserIdTo}"), 60*60*24*1);
		}
		return $this->GetUserById($id);
	}
	/**
	 * Добавляем воспоминание(восстановление) пароля
	 *
	 * @param ModuleUser_EntityReminder $oReminder	Объект восстановления пароля
	 * @return bool
	 */
	public function AddReminder(ModuleUser_EntityReminder $oReminder) {
		return $this->oMapper->AddReminder($oReminder);
	}
	/**
	 * Сохраняем воспомнинание(восстановление) пароля
	 *
	 * @param ModuleUser_EntityReminder $oReminder	Объект восстановления пароля
	 * @return bool
	 */
	public function UpdateReminder(ModuleUser_EntityReminder $oReminder) {
		return $this->oMapper->UpdateReminder($oReminder);
	}
	/**
	 * Получаем запись восстановления пароля по коду
	 *
	 * @param string $sCode	Код восстановления пароля
	 * @return ModuleUser_EntityReminder|null
	 */
	public function GetReminderByCode($sCode) {
		return $this->oMapper->GetReminderByCode($sCode);
	}
	/**
	 * Загрузка аватара пользователя
	 *
	 * @param  string	$sFileTmp	Серверный путь до временного аватара
	 * @param  ModuleUser_EntityUser $oUser	Объект пользователя
	 * @param  array $aSize Размер области из которой нужно вырезать картинку - array('x1'=>0,'y1'=>0,'x2'=>100,'y2'=>100)
	 * @return string|bool
	 */
	public function UploadAvatar($sFileTmp,$oUser,$aSize=array()) {
		if (!file_exists($sFileTmp)) {
			return false;
		}
		$sPath = $this->Image_GetIdDir($oUser->getId());
		$aParams=$this->Image_BuildParams('avatar');

		/**
		 * Срезаем квадрат
		 */
		$oImage = $this->Image_CreateImageObject($sFileTmp);
		/**
		 * Если объект изображения не создан,
		 * возвращаем ошибку
		 */
		if($sError=$oImage->get_last_error()) {
			// Вывод сообщения об ошибки, произошедшей при создании объекта изображения
			// $this->Message_AddError($sError,$this->Lang_Get('error'));
			@unlink($sFileTmp);
			return false;
		}

		if (!$aSize) {
			$oImage = $this->Image_CropSquare($oImage);
			$oImage->set_jpg_quality($aParams['jpg_quality']);
			$oImage->output(null,$sFileTmp);
		} else {
			$iWSource=$oImage->get_image_params('width');
			$iHSource=$oImage->get_image_params('height');
			/**
			 * Достаем переменные x1 и т.п. из $aSize
			 */
			extract($aSize,EXTR_PREFIX_SAME,'ops');
			if ($x1>$x2) {
				// меняем значения переменных
				$x1 = $x1 + $x2;
				$x2 = $x1 - $x2;
				$x1 = $x1 - $x2;
			}
			if ($y1>$y2) {
				$y1 = $y1 + $y2;
				$y2 = $y1 - $y2;
				$y1 = $y1 - $y2;
			}
			if ($x1<0) {
				$x1=0;
			}
			if ($y1<0) {
				$y1=0;
			}
			if ($x2>$iWSource) {
				$x2=$iWSource;
			}
			if ($y2>$iHSource) {
				$y2=$iHSource;
			}

			$iW=$x2-$x1;
			// Допускаем минимальный клип в 32px (исключая маленькие изображения)
			if ($iW<32 && $x1+32<=$iWSource) {
				$iW=32;
			}
			$iH=$iW;
			if ($iH+$y1>$iHSource) {
				$iH=$iHSource-$y1;
			}
			$oImage->crop($iW,$iH,$x1,$y1);
			$oImage->output(null,$sFileTmp);
		}

		if ($sFileAvatar=$this->Image_Resize($sFileTmp,$sPath,'avatar_100x100',Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),100,100,false,$aParams)) {
			$aSize=Config::Get('module.user.avatar_size');
			foreach ($aSize as $iSize) {
				if ($iSize==0) {
					$this->Image_Resize($sFileTmp,$sPath,'avatar',Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),null,null,false,$aParams);
				} else {
					$this->Image_Resize($sFileTmp,$sPath,"avatar_{$iSize}x{$iSize}",Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),$iSize,$iSize,false,$aParams);
				}
			}
			@unlink($sFileTmp);
			/**
			 * Если все нормально, возвращаем расширение загруженного аватара
			 */
			return $this->Image_GetWebPath($sFileAvatar);
		}
		@unlink($sFileTmp);
		/**
		 * В случае ошибки, возвращаем false
		 */
		return false;
	}
	/**
	 * Удаляет аватар пользователя
	 *
	 * @param ModuleUser_EntityUser $oUser Объект пользователя
	 */
	public function DeleteAvatar($oUser) {
		/**
		 * Если аватар есть, удаляем его и его рейсайзы
		 */
		if($oUser->getProfileAvatar()) {
			$aSize=array_merge(Config::Get('module.user.avatar_size'),array(100));
			foreach ($aSize as $iSize) {
				$this->Image_RemoveFile($this->Image_GetServerPath($oUser->getProfileAvatarPath($iSize)));
			}
		}
	}
	/**
	 * загрузка фотографии пользователя
	 *
	 * @param  string	$sFileTmp	Серверный путь до временной фотографии
	 * @param  ModuleUser_EntityUser $oUser	Объект пользователя
	 * @param  array $aSize Размер области из которой нужно вырезать картинку - array('x1'=>0,'y1'=>0,'x2'=>100,'y2'=>100)
	 * @return string|bool
	 */
	public function UploadFoto($sFileTmp,$oUser,$aSize=array()) {
		if (!file_exists($sFileTmp)) {
			return false;
		}
		$sDirUpload=$this->Image_GetIdDir($oUser->getId());
		$aParams=$this->Image_BuildParams('foto');


		if ($aSize) {
			$oImage = $this->Image_CreateImageObject($sFileTmp);
			/**
			 * Если объект изображения не создан,
			 * возвращаем ошибку
			 */
			if($sError=$oImage->get_last_error()) {
				// Вывод сообщения об ошибки, произошедшей при создании объекта изображения
				// $this->Message_AddError($sError,$this->Lang_Get('error'));
				@unlink($sFileTmp);
				return false;
			}

			$iWSource=$oImage->get_image_params('width');
			$iHSource=$oImage->get_image_params('height');
			/**
			 * Достаем переменные x1 и т.п. из $aSize
			 */
			extract($aSize,EXTR_PREFIX_SAME,'ops');
			if ($x1>$x2) {
				// меняем значения переменных
				$x1 = $x1 + $x2;
				$x2 = $x1 - $x2;
				$x1 = $x1 - $x2;
			}
			if ($y1>$y2) {
				$y1 = $y1 + $y2;
				$y2 = $y1 - $y2;
				$y1 = $y1 - $y2;
			}
			if ($x1<0) {
				$x1=0;
			}
			if ($y1<0) {
				$y1=0;
			}
			if ($x2>$iWSource) {
				$x2=$iWSource;
			}
			if ($y2>$iHSource) {
				$y2=$iHSource;
			}

			$iW=$x2-$x1;
			// Допускаем минимальный клип в 32px (исключая маленькие изображения)
			if ($iW<32 && $x1+32<=$iWSource) {
				$iW=32;
			}
			$iH=$y2-$y1;
			$oImage->crop($iW,$iH,$x1,$y1);
			$oImage->output(null,$sFileTmp);
		}

		if ($sFileFoto=$this->Image_Resize($sFileTmp,$sDirUpload,func_generator(6),Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),Config::Get('module.user.profile_photo_width'),null,true,$aParams)) {
			@unlink($sFileTmp);
			/**
			 * удаляем старое фото
			 */
			$this->DeleteFoto($oUser);
			return $this->Image_GetWebPath($sFileFoto);
		}
		@unlink($sFileTmp);
		return false;
	}
	/**
	 * Удаляет фото пользователя
	 *
	 * @param ModuleUser_EntityUser $oUser
	 */
	public function DeleteFoto($oUser) {
		$this->Image_RemoveFile($this->Image_GetServerPath($oUser->getProfileFoto()));
	}
	/**
	 * Проверяет логин на корректность
	 *
	 * @param string $sLogin	Логин пользователя
	 * @return bool
	 */
	public function CheckLogin($sLogin) {
		if (preg_match("/^[\da-z\_\-]{".Config::Get('module.user.login.min_size').','.Config::Get('module.user.login.max_size')."}$/i",$sLogin)){
			return true;
		}
		return false;
	}
	/**
	 * Получить дополнительные поля профиля пользователя
	 *
	 * @param array|null $aType Типы полей, null - все типы
	 * @return array
	 */
	public function getUserFields($aType=null) {
		return $this->oMapper->getUserFields($aType);
	}
	/**
	 * Получить значения дополнительных полей профиля пользователя
	 *
	 * @param int $iUserId ID пользователя
	 * @param bool $bOnlyNoEmpty Загружать только непустые поля
	 * @param array $aType Типы полей, null - все типы
	 * @return array
	 */
	public function getUserFieldsValues($iUserId, $bOnlyNoEmpty = true, $aType=array('')) {
		return $this->oMapper->getUserFieldsValues($iUserId, $bOnlyNoEmpty, $aType);
	}
	/**
	 * Получить по имени поля его значение дял определённого пользователя
	 *
	 * @param int $iUserId	ID пользователя
	 * @param string $sName Имя поля
	 * @return string
	 */
	public function getUserFieldValueByName($iUserId, $sName) {
		return $this->oMapper->getUserFieldValueByName($iUserId, $sName);
	}
	/**
	 * Установить значения дополнительных полей профиля пользователя
	 *
	 * @param int $iUserId	ID пользователя
	 * @param array $aFields Ассоциативный массив полей id => value
	 * @param int $iCountMax Максимальное количество одинаковых полей
	 * @return bool
	 */
	public function setUserFieldsValues($iUserId, $aFields, $iCountMax=1) {
		return $this->oMapper->setUserFieldsValues($iUserId, $aFields, $iCountMax);
	}
	/**
	 * Добавить поле
	 *
	 * @param ModuleUser_EntityField $oField	Объект пользовательского поля
	 * @return bool
	 */
	public function addUserField($oField) {
		return $this->oMapper->addUserField($oField);
	}
	/**
	 * Изменить поле
	 *
	 * @param ModuleUser_EntityField $oField	Объект пользовательского поля
	 * @return bool
	 */
	public function updateUserField($oField) {
		return $this->oMapper->updateUserField($oField);
	}
	/**
	 * Удалить поле
	 *
	 * @param int $iId	ID пользовательского поля
	 * @return bool
	 */
	public function deleteUserField($iId) {
		return $this->oMapper->deleteUserField($iId);
	}
	/**
	 * Проверяет существует ли поле с таким именем
	 *
	 * @param string $sName Имя поля
	 * @param int|null $iId	ID поля
	 * @return bool
	 */
	public function userFieldExistsByName($sName, $iId = null) {
		return $this->oMapper->userFieldExistsByName($sName, $iId);
	}
	/**
	 * Проверяет существует ли поле с таким ID
	 *
	 * @param int $iId	ID поля
	 * @return bool
	 */
	public function userFieldExistsById($iId) {
		return $this->oMapper->userFieldExistsById($iId);
	}
	/**
	 * Удаляет у пользователя значения полей
	 *
	 * @param int $iUserId	ID пользователя
	 * @param array|null $aType	Список типов для удаления
	 * @return bool
	 */
	public function DeleteUserFieldValues($iUserId,$aType=null) {
		return $this->oMapper->DeleteUserFieldValues($iUserId,$aType);
	}
	/**
	 * Возвращает список заметок пользователя
	 *
	 * @param int $iUserId	ID пользователя
	 * @param int $iCurrPage	Номер страницы
	 * @param int $iPerPage	Количество элементов на страницу
	 * @return array('collection'=>array,'count'=>int)
	 */
	public function GetUserNotesByUserId($iUserId,$iCurrPage,$iPerPage) {
		$aResult=$this->oMapper->GetUserNotesByUserId($iUserId,$iCount,$iCurrPage,$iPerPage);
		/**
		 * Цепляем пользователей
		 */
		$aUserId=array();
		foreach($aResult as $oNote) {
			$aUserId[]=$oNote->getTargetUserId();
		}
		$aUsers=$this->GetUsersAdditionalData($aUserId,array());
		foreach($aResult as $oNote) {
			if (isset($aUsers[$oNote->getTargetUserId()])) {
				$oNote->setTargetUser($aUsers[$oNote->getTargetUserId()]);
			} else {
				$oNote->setTargetUser(Engine::GetEntity('User')); // пустого пользователя во избеания ошибок, т.к. пользователь всегда должен быть
			}
		}
		return array('collection'=>$aResult,'count'=>$iCount);
	}
	/**
	 * Возвращает количество заметок у пользователя
	 *
	 * @param int $iUserId	ID пользователя
	 * @return int
	 */
	public function GetCountUserNotesByUserId($iUserId) {
		return $this->oMapper->GetCountUserNotesByUserId($iUserId);
	}
	/**
	 * Возвращет заметку по автору и пользователю
	 *
	 * @param int $iTargetUserId	ID пользователя о ком заметка
	 * @param int $iUserId	ID пользователя автора заметки
	 * @return ModuleUser_EntityNote
	 */
	public function GetUserNote($iTargetUserId,$iUserId) {
		return $this->oMapper->GetUserNote($iTargetUserId,$iUserId);
	}
	/**
	 * Возвращает заметку по ID
	 *
	 * @param int $iId	ID заметки
	 * @return ModuleUser_EntityNote
	 */
	public function GetUserNoteById($iId) {
		return $this->oMapper->GetUserNoteById($iId);
	}
	/**
	 * Удаляет заметку по ID
	 *
	 * @param int $iId	ID заметки
	 * @return bool
	 */
	public function DeleteUserNoteById($iId) {
		return $this->oMapper->DeleteUserNoteById($iId);
	}
	/**
	 * Сохраняет заметку в БД, если ее нет то создает новую
	 *
	 * @param ModuleUser_EntityNote $oNote	Объект заметки
	 * @return bool|ModuleUser_EntityNote
	 */
	public function SaveNote($oNote) {
		if (!$oNote->getDateAdd()) {
			$oNote->setDateAdd(date("Y-m-d H:i:s"));
		}

		if ($oNoteOld=$this->GetUserNote($oNote->getTargetUserId(),$oNote->getUserId()) ) {
			$oNoteOld->setText($oNote->getText());
			$this->oMapper->UpdateUserNote($oNoteOld);
			return $oNoteOld;
		} else {
			if ($iId=$this->oMapper->AddUserNote($oNote)) {
				$oNote->setId($iId);
				return $oNote;
			}
		}
		return false;
	}
	/**
	 * Возвращает список префиксов логинов пользователей (для алфавитного указателя)
	 *
	 * @param int $iPrefixLength	Длина префикса
	 * @return array
	 */
	public function GetGroupPrefixUser($iPrefixLength=1) {
		if (false === ($data = $this->Cache_Get("group_prefix_user_{$iPrefixLength}"))) {
			$data = $this->oMapper->GetGroupPrefixUser($iPrefixLength);
			$this->Cache_Set($data, "group_prefix_user_{$iPrefixLength}", array("user_new"), 60*60*24*1);
		}
		return $data;
	}
}
?>
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
require_once('mapper/User.mapper.class.php');

/**
 * Модуль для работы с пользователями
 *
 */
class LsUser extends Module {
	/**
	 * Статусы дружбы между пользователями
	 */
	const USER_FRIEND_OFFER  = 1;
	const USER_FRIEND_ACCEPT = 2;
	const USER_FRIEND_DELETE = 4;
	const USER_FRIEND_REJECT = 8;
	const USER_FRIEND_NULL   = 16;

	protected $oMapper;
	protected $oUserCurrent=null;
	protected $oSession=null;

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->oMapper=new Mapper_User($this->Database_GetConnect());
		/**
		 * Проверяем есть ли у юзера сессия, т.е. залогинен или нет
		 */
		$sUserId=$this->Session_Get('user_id');
		if ($sUserId and $oUser=$this->GetUserById($sUserId) and $oUser->getActivate()) {
			if ($this->oSession=$this->GetSessionByUserId($oUser->getId())) {
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

		$this->oMapper->SetUserCurrent($this->oUserCurrent);
		/**
		 * Обновляем сессию
		 */
		if (isset($this->oSession)) {
			$this->UpdateSession();
		}
	}
	/**
	 * Получает дополнительные данные(объекты) для юзеров по их ID
	 *
	 */
	public function GetUsersAdditionalData($aUserId,$aAllowData=array('vote','session','friend')) {
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
		if (isset($aAllowData['session'])) {
			$aSessions=$this->GetSessionsByArrayId($aUserId);
		}
		if (isset($aAllowData['friend']) and $this->oUserCurrent) {
			$aFriends=$this->GetFriendsByArray($aUserId,$this->oUserCurrent->getId());
		}

		if (isset($aAllowData['vote']) and $this->oUserCurrent) {
			$aVote=$this->Vote_GetVoteByArray($aUserId,'user',$this->oUserCurrent->getId());
		}
		/**
		 * Добавляем данные к результату
		 */
		foreach ($aUsers as $oUser) {
			if (isset($aSessions[$oUser->getId()])) {
				$oUser->setSession($aSessions[$oUser->getId()]);
			} else {
				$oUser->setSession(null); // или $oUser->setSession(new UserEntity_Session());
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
		}

		return $aUsers;
	}
	/**
	 * Список юзеров по ID
	 *
	 * @param array $aUserId
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
			$this->Cache_Set($aUsers, "user_id_{$s}", array("user_update"), 60*60*24*1);
			return $aUsers;
		}
		return $data;
	}
	/**
	 * Список сессий юзеров по ID
	 *
	 * @param array $aUserId
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
	 * @param unknown_type $aUserId
	 * @return unknown
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
	 * @param unknown_type $sUserId
	 * @return unknown
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
			$iCountTalkNew=$this->Talk_GetCountTalkNew($this->oUserCurrent->getId());
			$this->Viewer_Assign('iUserCurrentCountTalkNew',$iCountTalkNew);
		}
		$this->Viewer_Assign('oUserCurrent',$this->oUserCurrent);
	}
	/**
	 * Добавляет юзера
	 *
	 * @param UserEntity_User $oUser
	 * @return unknown
	 */
	public function Add(UserEntity_User $oUser) {
		if ($sId=$this->oMapper->Add($oUser)) {
			$oUser->setId($sId);
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_new'));
			return $oUser;
		}
		return false;
	}
	/**
	 * Получить юзера по ключу активации
	 *
	 * @param unknown_type $sKey
	 * @return unknown
	 */
	public function GetUserByActivateKey($sKey) {
		$id=$this->oMapper->GetUserByActivateKey($sKey);
		return $this->GetUserById($id);
	}
	/**
	 * Получить юзера по ключу сессии
	 *
	 * @param unknown_type $sKey
	 * @return unknown
	 */
	public function GetUserBySessionKey($sKey) {
		$id=$this->oMapper->GetUserBySessionKey($sKey);
		return $this->GetUserById($id);
	}
	/**
	 * Получить юзера по мылу
	 *
	 * @param unknown_type $sMail
	 * @return unknown
	 */
	public function GetUserByMail($sMail) {
		$id=$this->oMapper->GetUserByMail($sMail);
		return $this->GetUserById($id);
	}
	/**
	 * Получить юзера по логину
	 *
	 * @param unknown_type $sLogin
	 * @return unknown
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
	 * @param unknown_type $sId
	 * @return unknown
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
	 * @param UserEntity_User $oUser
	 * @return unknown
	 */
	public function Update(UserEntity_User $oUser) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_update'));
		$this->Cache_Delete("user_{$oUser->getId()}");
		return $this->oMapper->Update($oUser);
	}
	/**
	 * Авторизовывает юзера
	 *
	 * @param UserEntity_User $oUser
	 * @return unknown
	 */
	public function Authorization(UserEntity_User $oUser,$bRemember=true) {	
		if (!$oUser->getId() or !$oUser->getActivate()) {
			return false;
		}
		/**
		 * Генерим новый ключ авторизаии для куков
		 */
		$sKey=md5(func_generator().time().$oUser->getLogin());
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
			setcookie('key',$sKey,time()+60*60*24*3,Config::Get('sys.cookie.path'),Config::Get('sys.cookie.host'));
		}
	}
	/**
	 * Автоматическое заллогинивание по ключу из куков
	 *
	 */
	protected function AutoLogin() {
		if ($this->oUserCurrent) {
			return;
		}
		if (isset($_COOKIE['key'])) {
			$sKey=$_COOKIE['key'];
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
	 * @return unknown
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
	 * @return unknown
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

	protected function CreateSession(UserEntity_User $oUser,$sKey) {
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
	 * @param unknown_type $iLimit
	 * @return unknown
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
	 * Получить список юзеров по дате регистрации
	 *
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetUsersByDateRegister($iLimit=20) {
		if (false === ($data = $this->Cache_Get("user_date_register_{$iLimit}"))) {
			$data = $this->oMapper->GetUsersByDateRegister($iLimit);
			$this->Cache_Set($data, "user_date_register_{$iLimit}", array("user_new"), 60*60*24*3);
		}
		$data=$this->GetUsersAdditionalData($data);
		return $data;
	}
	/**
	 * Получить список юзеров по рейтингу
	 *
	 * @param unknown_type $sType
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetUsersRating($sType,$iPage,$iPerPage) {
		if (false === ($data = $this->Cache_Get("user_rating_{$sType}_{$iPage}_{$iPerPage}"))) {
			$data = array('collection'=>$this->oMapper->GetUsersRating($sType,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "user_rating_{$sType}_{$iPage}_{$iPerPage}", array("user_new","user_update"), 60*60*24*2);
		}
		$data['collection']=$this->GetUsersAdditionalData($data['collection']);
		return $data;
	}
	/**
	 * Получить спиок юзеров по стране
	 *
	 * @param unknown_type $sCountry
	 * @param unknown_type $iCurrPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetUsersByCountry($sCountry,$iPage,$iPerPage) {
		if (false === ($data = $this->Cache_Get("user_country_{$sCountry}_{$iPage}_{$iPerPage}"))) {
			$data = array('collection'=>$this->oMapper->GetUsersByCountry($sCountry,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "user_country_{$sCountry}_{$iPage}_{$iPerPage}", array("user_update"), 60*60*24*2);
		}
		$data['collection']=$this->GetUsersAdditionalData($data['collection']);
		return $data;
	}
	/**
	 * Получить список юзеров по городу
	 *
	 * @param unknown_type $sCity
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetUsersByCity($sCity,$iPage,$iPerPage) {
		if (false === ($data = $this->Cache_Get("user_city_{$sCity}_{$iPage}_{$iPerPage}"))) {
			$data = array('collection'=>$this->oMapper->GetUsersByCity($sCity,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "user_city_{$sCity}_{$iPage}_{$iPerPage}", array("user_update"), 60*60*24*2);
		}
		$data['collection']=$this->GetUsersAdditionalData($data['collection']);
		return $data;
	}
	/**
	 * Получить статистику по юзерам
	 *
	 * @return unknown
	 */
	public function GetStatUsers() {
		if (false === ($aStat = $this->Cache_Get("user_stats"))) {
			$aStat['count_all']=$this->oMapper->GetCountUsers();
			$sDate=date("Y-m-d H:i:s",time()-60*60*24*7);
			$aStat['count_active']=$this->oMapper->GetCountUsersActive($sDate);
			$aStat['count_inactive']=$aStat['count_all']-$aStat['count_active'];
			$aSex=$this->oMapper->GetCountUsersSex();
			$aStat['count_sex_man']=(isset($aSex['man']) ? $aSex['man']['count'] : 0);
			$aStat['count_sex_woman']=(isset($aSex['woman']) ? $aSex['woman']['count'] : 0);
			$aStat['count_sex_other']=(isset($aSex['other']) ? $aSex['other']['count'] : 0);
			$aStat['count_country']=$this->oMapper->GetCountUsersCountry(15);
			$aStat['count_city']=$this->oMapper->GetCountUsersCity(15);

			$this->Cache_Set($aStat, "user_stats", array("user_update","user_new"), 60*60*24*4);
		}
		return $aStat;
	}
	/**
	 * Получить список логинов по первым  буквам
	 *
	 * @param unknown_type $sUserLogin
	 * @param unknown_type $iLimit
	 * @return unknown
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
	 * @param  array $aUserId
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
	 * Получить список отношений с френдами по списку айдишников, но используя единый кеш
	 *
	 * @param  array $aUserId
	 * @param  string $sUserId
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
	 * @param  string $sFriendId
	 * @param  string $sUserId
	 * @return UserEntity_Friend
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
	 * @param  UserEntity_Friend $oFriend
	 * @return bool
	 */
	public function AddFriend(UserEntity_Friend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
		
		return $this->oMapper->AddFriend($oFriend);
	}
	/**
	 * Удаляет друга
	 *
	 * @param  UserEntity_Friend $oFriend
	 * @return bool
	 */
	public function DeleteFriend(UserEntity_Friend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
		
		// устанавливаем статус дружбы "удалено"
		$oFriend->setStatusByUserId(LsUser::USER_FRIEND_DELETE,$oFriend->getUserId());
		return $this->oMapper->UpdateFriend($oFriend);
	}
	/**
	 * Удаляет информацию о дружбе из базы данных
	 *
	 * @param  UserEntity_Friend $oFriend
	 * @return bool
	 */
	public function EraseFriend(UserEntity_Friend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
		return $this->oMapper->EraseFriend($oFriend);		
	}
	
	/**
	 * Обновляет информацию о друге
	 *
	 * @param  UserEntity_Friend $oFriend
	 * @return bool
	 */
	public function UpdateFriend(UserEntity_Friend $oFriend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("friend_change_user_{$oFriend->getUserFrom()}","friend_change_user_{$oFriend->getUserTo()}"));
		$this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
		$this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
		return $this->oMapper->UpdateFriend($oFriend);
	}

	/**
	 * Получает список друзей
	 *
	 * @param  string $sUserId
	 * @return array	 
	 */
	public function GetUsersFriend($sUserId) {
		if (false === ($data = $this->Cache_Get("user_friend_{$sUserId}"))) {
			$data = $this->oMapper->GetUsersFriend($sUserId);
			$this->Cache_Set($data, "user_friend_{$sUserId}", array("friend_change_user_{$sUserId}"), 60*60*24*2);
		}
		$data=$this->GetUsersAdditionalData($data);
		return $data;
	}

	/**
	 * Получает инвайт по его коду
	 *
	 * @param  string $sCode
	 * @param  int    $iUsed
	 * @return string
	 */
	public function GetInviteByCode($sCode,$iUsed=0) {
		return $this->oMapper->GetInviteByCode($sCode,$iUsed);
	}
	/**
	 * Добавляет новый инвайт
	 *
	 * @param UserEntity_Invite $oInvite
	 * @return unknown
	 */
	public function AddInvite(UserEntity_Invite $oInvite) {
		if ($sId=$this->oMapper->AddInvite($oInvite)) {
			$oInvite->setId($sId);
			return $oInvite;
		}
		return false;
	}
	/**
	 * Обновляет инвайт
	 *
	 * @param UserEntity_Invite $oInvite
	 * @return unknown
	 */
	public function UpdateInvite(UserEntity_Invite $oInvite) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("invate_new_to_{$oInvite->getUserToId()}","invate_new_from_{$oInvite->getUserFromId()}"));
		return $this->oMapper->UpdateInvite($oInvite);
	}
	/**
	 * Генерирует новый инвайт
	 *
	 * @param unknown_type $oUser
	 * @return unknown
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
	 * @param unknown_type $sUserIdFrom
	 * @param unknown_type $sDate
	 * @return unknown
	 */
	public function GetCountInviteUsedByDate($sUserIdFrom,$sDate) {
		return $this->oMapper->GetCountInviteUsedByDate($sUserIdFrom,$sDate);
	}
	/**
	 * Получает полное число использованных приглашений юзера
	 *
	 * @param unknown_type $sUserIdFrom
	 * @return unknown
	 */
	public function GetCountInviteUsed($sUserIdFrom) {
		return $this->oMapper->GetCountInviteUsed($sUserIdFrom);
	}
	/**
	 * Получаем число доступных приглашений для юзера
	 *
	 * @param unknown_type $oUserFrom
	 * @return unknown
	 */
	public function GetCountInviteAvailable(UserEntity_User $oUserFrom) {
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
	 * @param unknown_type $sUserId
	 * @return unknown
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
	 * @param unknown_type $sUserIdTo
	 * @return unknown
	 */
	public function GetUserInviteFrom($sUserIdTo) {
		if (false === ($id = $this->Cache_Get("user_invite_from_{$sUserIdTo}"))) {
			$id = $this->oMapper->GetUserInviteFrom($sUserIdTo);
			$this->Cache_Set($id, "user_invite_from_{$sUserIdTo}", array("invate_new_to_{$sUserIdTo}"), 60*60*24*1);
		}
		return $this->GetUserById($id);
	}
	/**
	 * Привязывает страну к пользователю
	 *
	 * @param unknown_type $sCountryId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function SetCountryUser($sCountryId,$sUserId) {
		return $this->oMapper->SetCountryUser($sCountryId,$sUserId);
	}
	/**
	 * Получает страну по имени
	 *
	 * @param unknown_type $sName
	 * @return unknown
	 */
	public function GetCountryByName($sName) {
		return $this->oMapper->GetCountryByName($sName);
	}
	/**
	 * Добавляет страну
	 *
	 * @param UserEntity_Country $oCountry
	 * @return unknown
	 */
	public function AddCountry(UserEntity_Country $oCountry) {
		if ($sId=$this->oMapper->AddCountry($oCountry)) {
			$oCountry->setId($sId);
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("country_new"));
			return $oCountry;
		}
		return false;
	}
	/**
	 * Привязывает город к пользователю
	 *
	 * @param unknown_type $sCityId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function SetCityUser($sCityId,$sUserId) {
		return $this->oMapper->SetCityUser($sCityId,$sUserId);
	}
	/**
	 * Получает город по имени
	 *
	 * @param unknown_type $sName
	 * @return unknown
	 */
	public function GetCityByName($sName) {
		return $this->oMapper->GetCityByName($sName);
	}
	/**
	 * Добавляет город
	 *
	 * @param UserEntity_City $oCity
	 * @return unknown
	 */
	public function AddCity(UserEntity_City $oCity) {
		if ($sId=$this->oMapper->AddCity($oCity)) {
			$oCity->setId($sId);
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("city_new"));
			return $oCity;
		}
		return false;
	}
	/**
	 * Получает список похожих городов
	 *
	 * @param unknown_type $sName
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetCityByNameLike($sName,$iLimit) {
		if (false === ($data = $this->Cache_Get("city_like_{$sName}_{$iLimit}"))) {
			$data = $this->oMapper->GetCityByNameLike($sName,$iLimit);
			$this->Cache_Set($data, "city_like_{$sName}_{$iLimit}", array("city_new"), 60*60*24*1);
		}
		return $data;
	}
	/**
	 * Получает список похожих стран
	 *
	 * @param unknown_type $sName
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetCountryByNameLike($sName,$iLimit) {
		if (false === ($data = $this->Cache_Get("country_like_{$sName}_{$iLimit}"))) {
			$data = $this->oMapper->GetCountryByNameLike($sName,$iLimit);
			$this->Cache_Set($data, "country_like_{$sName}_{$iLimit}", array("country_new"), 60*60*24*1);
		}
		return $data;
	}
	/**
	 * Добавляем воспоминание(восстановление) пароля
	 *
	 * @param unknown_type $oReminder
	 * @return unknown
	 */
	public function AddReminder(UserEntity_Reminder $oReminder) {
		return $this->oMapper->AddReminder($oReminder);
	}
	/**
	 * Сохраняем воспомнинание(восстановление) пароля
	 *
	 * @param unknown_type $oReminder
	 * @return unknown
	 */
	public function UpdateReminder(UserEntity_Reminder $oReminder) {
		return $this->oMapper->UpdateReminder($oReminder);
	}
	/**
	 * Получаем запись восстановления пароля по коду
	 *
	 * @param unknown_type $sCode
	 * @return unknown
	 */
	public function GetReminderByCode($sCode) {
		return $this->oMapper->GetReminderByCode($sCode);
	}
	
	/**
	 * Upload user avatar on server
	 * Make resized images
	 *
	 * @param  array           $aFile
	 * @param  UserEntity_User $oUser
	 * @return (string|bool)
	 */
	public function UploadAvatar($aFile,$oUser) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
		
		$sFileTmp=$aFile['tmp_name'];
		$sPath = $this->Image_GetIdDir($oUser->getId());
		$aParams=$this->Image_BuildParams('avatar');

		/**
		 * Срезаем квадрат
		 */
		$oImage = $this->Image_CropSquare(new LiveImage($sFileTmp));
		$oImage->set_jpg_quality($aParams['jpg_quality']);
		$oImage->output(null,$sFileTmp);
		
		if ($sFileAvatar=$this->Image_Resize($sFileTmp,$sPath,'avatar_100x100',3000,3000,100,100,false,$aParams)) {
			$this->Image_Resize($sFileTmp,$sPath,'avatar_64x64',3000,3000,64,64,false,$aParams);
			$this->Image_Resize($sFileTmp,$sPath,'avatar_48x48',3000,3000,48,48,false,$aParams);
			$this->Image_Resize($sFileTmp,$sPath,'avatar_24x24',3000,3000,24,24,false,$aParams);
			$this->Image_Resize($sFileTmp,$sPath,'avatar',3000,3000,null,null,false,$aParams);
			
			/**
			 * Если все нормально, возвращаем расширение загруженного аватара
			 */
			return $this->Image_GetWebPath($sFileAvatar);
		}
		/**
		 * В случае ошибки, возвращаем false
		 */
		return false;
	}	
	/**
	 * Delete avatar from server
	 *
	 * @param UserEntity_User $oUser
	 */
	public function DeleteAvatar($oUser) {
		/**
		 * Если аватар есть, удаляем его и его рейсайзы
		 */
		if($oUser->getProfileAvatar()) {
			@unlink($this->Image_GetServerPath($oUser->getProfileAvatarPath(100)));
			@unlink($this->Image_GetServerPath($oUser->getProfileAvatarPath(64)));
			@unlink($this->Image_GetServerPath($oUser->getProfileAvatarPath(48)));
			@unlink($this->Image_GetServerPath($oUser->getProfileAvatarPath(24)));
			@unlink($this->Image_GetServerPath($oUser->getProfileAvatarPath(0)));
		}
	}
		
	/**
	 * Upload user foto
	 *
	 * @param  array           $aFile
	 * @param  UserEntity_User $oUser
	 * @return string
	 */
	public function UploadFoto($aFile,$oUser) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
		
		$sDirUpload=$this->Image_GetIdDir($oUser->getId());
		$sFileTmp=$aFile['tmp_name'];
		$aParams=$this->Image_BuildParams('foto');
		
		if ($sFileFoto=$this->Image_Resize($sFileTmp,$sDirUpload,func_generator(6),3000,3000,250,null,true,$aParams)) {
			/**
			 * удаляем старое фото
			 */
			$this->DeleteFoto($oUser);
			return $this->Image_GetWebPath($sFileFoto);
		}
		return false;
	}
	/**
	 * Delete user foto from server
	 *
	 * @param UserEntity_User $oUser
	 */
	public function DeleteFoto($oUser) {
		@unlink($this->Image_GetServerPath($oUser->getProfileFoto()));
	}
}
?>
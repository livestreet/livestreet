<?
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
class User extends Module {	
	protected $oMapper;
	protected $oUserCurrent=null;
	
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
			$this->oUserCurrent=$oUser;
		}		
		/**
		 * Запускаем атозалогинивание
		 * В куках стоит время на сколько запоминать юзера
		 */
		$this->AutoLogin();
		
		$this->oMapper->SetUserCurrent($this->oUserCurrent);
		/**
		 * Обновляем данные о юзере
		 */
		$this->AutoUpdateUser();				
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
		return $this->oMapper->GetUserByActivateKey($sKey);
	}
	/**
	 * Получить юзера по ключу сессии
	 *
	 * @param unknown_type $sKey
	 * @return unknown
	 */
	public function GetUserByKey($sKey) {		
		return $this->oMapper->GetUserByKey($sKey);
	}
	/**
	 * Получить юзера по мылу
	 *
	 * @param unknown_type $sMail
	 * @return unknown
	 */
	public function GetUserByMail($sMail) {		
		return $this->oMapper->GetUserByMail($sMail);
	}
	/**
	 * Получить юзера по логину
	 *
	 * @param unknown_type $sLogin
	 * @return unknown
	 */
	public function GetUserByLogin($sLogin) {	
		$s=strtolower($sLogin);
		$s2=-1;		
		if ($this->oUserCurrent) {
			$s2=$this->oUserCurrent->getId();
		}
		if (false === ($data = $this->Cache_Get("user_login_{$s}_{$s2}"))) {						
			if ($data = $this->oMapper->GetUserByLogin($sLogin)) {
				$this->Cache_Set($data, "user_login_{$s}_{$s2}", array("user_update_{$data->getId()}","frend_change_frend_{$data->getId()}"), 60*5);
			}						
		}
		return $data;		 
	}
	/**
	 * Получить юзера по айдишнику
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetUserById($sId) {			
		if (false === ($data = $this->Cache_Get("user_{$sId}"))) {						
			$data = $this->oMapper->GetUserById($sId);
			$this->Cache_Set($data, "user_{$sId}", array("user_update_{$sId}"), 60*5);			
		}
		return $data;		
	}
	/**
	 * Обновляет юзера
	 *
	 * @param UserEntity_User $oUser
	 * @return unknown
	 */
	public function Update(UserEntity_User $oUser) {	
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_update',"user_update_{$oUser->getId()}"));	
		return $this->oMapper->Update($oUser);
	}
	/**
	 * Авторизовывает юзера
	 *
	 * @param UserEntity_User $oUser
	 * @return unknown
	 */
	public function Authorization(UserEntity_User $oUser) {
		if (!$oUser->getId() or !$oUser->getActivate()) {
			return false;
		}
		/**
		 * Генерим новый ключ авторизаии для куков
		 */
		$sKey=md5(func_generator().time().$oUser->getLogin());
		$oUser->setKey($sKey);
		/**
		 * Запоминаем в сесси юзера
		 */
		$this->Session_Set('user_id',$oUser->getId());
		$this->oUserCurrent=$oUser;
		$this->Update($oUser);
		/**
		 * Ставим куку
		 */
		setcookie('key',$sKey,time()+60*60*24*3,SYS_COOKIE_PATH,SYS_COOKIE_HOST);
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
			if ($oUser=$this->GetUserByKey($sKey)) {
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
		/**
		 * Дропаем из сессии
		 */
		$this->Session_Drop('user_id');
		/**
		 * Дропаем куку
		 */
		setcookie('key','',1,SYS_COOKIE_PATH,SYS_COOKIE_HOST);
	}
	/**
	 * Автообновление данных о юзере
	 *
	 */
	protected function AutoUpdateUser() {
		if (!$this->oUserCurrent) {
			return;
		}
		$this->oUserCurrent->setDateLast(date("Y-m-d H:i:s"));
		$this->oUserCurrent->setIpLast(func_getIp());	
		/**
		 * сохраняем
		 * делаем это не через метод Update, а напрямую через мапер, т.к. нам не нужно чтоб сбрасывался кеш при полном апдейте
		 */		
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_update_last'));
		$this->oMapper->Update($this->oUserCurrent);
	}
	/**
	 * Получить голосование за юзера
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $sVoterId
	 * @return unknown
	 */
	public function GetUserVote($sUserId,$sVoterId) {
		return $this->oMapper->GetUserVote($sUserId,$sVoterId);
	}
	/**
	 * Проголосовать за юзера
	 *
	 * @param UserEntity_UserVote $oUserVote
	 * @return unknown
	 */
	public function AddUserVote(UserEntity_UserVote $oUserVote) {
		return $this->oMapper->AddUserVote($oUserVote);
	}
	/**
	 * Получить список юзеров по дате последнего визита
	 *
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetUsersByDateLast($iLimit=20) {
		if ($this->IsAuthorization()) {
			return $this->oMapper->GetUsersByDateLast($iLimit);
		}
		if (false === ($data = $this->Cache_Get("user_date_last_{$iLimit}"))) {						
			$data = $this->oMapper->GetUsersByDateLast($iLimit);
			$this->Cache_Set($data, "user_date_last_{$iLimit}", array("user_update_last"), 60*5);			
		}
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
			$this->Cache_Set($data, "user_date_register_{$iLimit}", array("user_new"), 60*5);			
		}
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
	public function GetUsersRating($sType,$iCount,$iPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("user_rating_{$sType}_{$iPage}_{$iPerPage}"))) {						
			$data = array('collection'=>$this->oMapper->GetUsersRating($sType,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "user_rating_{$sType}_{$iPage}_{$iPerPage}", array("user_new","user_update"), 60*5);			
		}
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
			$aStat['count_inactive']=$this->oMapper->GetCountUsersInactive($sDate);
			$aSex=$this->oMapper->GetCountUsersSex();
			$aStat['count_sex_man']=(isset($aSex['man']) ? $aSex['man']['count'] : 0);
			$aStat['count_sex_woman']=(isset($aSex['woman']) ? $aSex['woman']['count'] : 0);
			$aStat['count_sex_other']=(isset($aSex['other']) ? $aSex['other']['count'] : 0);
			$aStat['count_country']=$this->oMapper->GetCountUsersCountry();
			$aStat['count_city']=$this->oMapper->GetCountUsersCity();
			
			$this->Cache_Set($aStat, "user_stats", array("user_update","user_new"), 60*5);
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
			$this->Cache_Set($data, "user_like_{$sUserLogin}_{$iLimit}", array("user_update","user_new"), 60*15);
		}
		return $data;		
	}
	/**
	 * Получаем привязку друга к юзеру(есть ли у юзера данный друг)
	 *
	 * @param unknown_type $sFrendId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetFrend($sFrendId,$sUserId) {
		return $this->oMapper->GetFrend($sFrendId,$sUserId);
	}
	/**
	 * Добавляет друга
	 *
	 * @param UserEntity_Frend $oFrend
	 * @return unknown
	 */
	public function AddFrend(UserEntity_Frend $oFrend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("frend_change_user_{$oFrend->getUserId()}","frend_change_frend_{$oFrend->getFrendId()}"));						
		return $this->oMapper->AddFrend($oFrend);
	}
	/**
	 * Удаляет друга
	 *
	 * @param UserEntity_Frend $oFrend
	 * @return unknown
	 */
	public function DeleteFrend(UserEntity_Frend $oFrend) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("frend_change_user_{$oFrend->getUserId()}","frend_change_frend_{$oFrend->getFrendId()}"));
		return $this->oMapper->DeleteFrend($oFrend);
	}
	/**
	 * Получает список друзей
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetUsersFrend($sUserId) {
		if (false === ($data = $this->Cache_Get("user_frend_{$sUserId}"))) {			
			$data = $this->oMapper->GetUsersFrend($sUserId);
			$this->Cache_Set($data, "user_frend_{$sUserId}", array("frend_change_user_{$sUserId}"), 60*5);
		}
		return $data;		 
	}
	/**
	 * Получает список тех у кого в друзьях
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetUsersSelfFrend($sUserId) {
		if (false === ($data = $this->Cache_Get("user_self_frend_{$sUserId}"))) {			
			$data = $this->oMapper->GetUsersSelfFrend($sUserId);
			$this->Cache_Set($data, "user_self_frend_{$sUserId}", array("frend_change_frend_{$sUserId}"), 60*5);
		}
		return $data;		 
	}
}
?>
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

class ModuleUser_MapperUser extends Mapper {
	protected $oUserCurrent=null;
	
	public function SetUserCurrent($oUserCurrent)  {
		$this->oUserCurrent=$oUserCurrent;
	}
	
	public function Add(ModuleUser_EntityUser $oUser) {
		$sql = "INSERT INTO ".Config::Get('db.table.user')." 
			(user_login,
			user_password,
			user_mail,
			user_date_register,
			user_ip_register,
			user_activate,
			user_activate_key
			)
			VALUES(?,  ?,	?,	?,	?,	?,	?)
		";			
		if ($iId=$this->oDb->query($sql,$oUser->getLogin(),$oUser->getPassword(),$oUser->getMail(),$oUser->getDateRegister(),$oUser->getIpRegister(),$oUser->getActivate(),$oUser->getActivateKey())) {
			return $iId;
		}		
		return false;
	}
	
	public function Update(ModuleUser_EntityUser $oUser) {
		$sql = "UPDATE ".Config::Get('db.table.user')." 
			SET 
				user_password = ? ,
				user_mail = ? ,					
				user_skill = ? ,				
				user_date_activate = ? ,
				user_date_comment_last = ? ,				
				user_rating = ? ,
				user_count_vote = ? ,
				user_activate = ? , 
				user_profile_name = ? , 
				user_profile_sex = ? , 
				user_profile_country = ? , 
				user_profile_region = ? , 
				user_profile_city = ? , 
				user_profile_birthday = ? , 
				user_profile_site = ? , 
				user_profile_site_name = ? , 
				user_profile_icq = ? , 
				user_profile_about = ? ,
				user_profile_date = ? ,
				user_profile_avatar = ?	,
				user_profile_foto = ? ,	
				user_settings_notice_new_topic = ?	,
				user_settings_notice_new_comment = ? ,
				user_settings_notice_new_talk = ?	,
				user_settings_notice_reply_comment = ? ,
				user_settings_notice_new_friend = ? 		
			WHERE user_id = ?
		";			
		if ($this->oDb->query($sql,$oUser->getPassword(),
								   $oUser->getMail(),								   
								   $oUser->getSkill(),								   
								   $oUser->getDateActivate(),
								   $oUser->getDateCommentLast(),							   
								   $oUser->getRating(),
								   $oUser->getCountVote(),
								   $oUser->getActivate(),								   
								   $oUser->getProfileName(),
								   $oUser->getProfileSex(),
								   $oUser->getProfileCountry(),
								   $oUser->getProfileRegion(),
								   $oUser->getProfileCity(),
								   $oUser->getProfileBirthday(),
								   $oUser->getProfileSite(),
								   $oUser->getProfileSiteName(),
								   $oUser->getProfileIcq(),
								   $oUser->getProfileAbout(),	
								   $oUser->getProfileDate(),	
								   $oUser->getProfileAvatar(),
								   $oUser->getProfileFoto(),
								   $oUser->getSettingsNoticeNewTopic(),
								   $oUser->getSettingsNoticeNewComment(),
								   $oUser->getSettingsNoticeNewTalk(),
								   $oUser->getSettingsNoticeReplyComment(),	
								   $oUser->getSettingsNoticeNewFriend(),			   
								   $oUser->getId())) {
			return true;
		}		
		return false;
	}
	
		
	public function GetUserBySessionKey($sKey) {
		$sql = "SELECT 
					s.user_id 
				FROM					
					".Config::Get('db.table.session')." as s
				WHERE 
					s.session_key = ? 					
				";
		if ($aRow=$this->oDb->selectRow($sql,$sKey)) {
			return $aRow['user_id'];
		}
		return null;
	}
	
	public function CreateSession(ModuleUser_EntitySession $oSession) {
		$sql = "REPLACE INTO ".Config::Get('db.table.session')." 
			SET 
				session_key = ? ,
				user_id = ? ,
				session_ip_create = ? ,
				session_ip_last = ? ,
				session_date_create = ? ,		
				session_date_last = ? 
		";			
		return $this->oDb->query($sql,$oSession->getKey(), $oSession->getUserId(), $oSession->getIpCreate(), $oSession->getIpLast(), $oSession->getDateCreate(), $oSession->getDateLast());
	}
	
	public function UpdateSession(ModuleUser_EntitySession $oSession) {
		$sql = "UPDATE ".Config::Get('db.table.session')." 
			SET 
				session_ip_last = ? ,	
				session_date_last = ? 
			WHERE user_id = ?
		";			
		return $this->oDb->query($sql,$oSession->getIpLast(), $oSession->getDateLast(), $oSession->getUserId());
	}
	
	public function GetSessionsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					s.*						 
				FROM 
					".Config::Get('db.table.session')." as s					
				WHERE 
					s.user_id IN(?a) ";
		$aRes=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aRes[]=Engine::GetEntity('User_Session',$aRow);
			}
		}		
		return $aRes;
	}
	
	public function GetUsersByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					u.*	,
					IF(ua.user_id IS NULL,0,1) as user_is_administrator 						 
				FROM 
					".Config::Get('db.table.user')." as u	
					LEFT JOIN ".Config::Get('db.table.user_administrator')." AS ua ON u.user_id=ua.user_id 
				WHERE 
					u.user_id IN(?a) 								
				ORDER BY FIELD(u.user_id,?a) ";
		$aUsers=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aUser) {
				$aUsers[]=Engine::GetEntity('User',$aUser);
			}
		}		
		return $aUsers;
	}
	
	public function GetUserByActivateKey($sKey) {		
		$sql = "SELECT 
				u.user_id
			FROM 
				".Config::Get('db.table.user')." as u				
			WHERE u.user_activate_key = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sKey)) {
			return $aRow['user_id'];
		}
		return null;
	}
	
		
	public function GetUserByMail($sMail) {		
		$sql = "SELECT 
				u.user_id
			FROM 
				".Config::Get('db.table.user')." as u 				
			WHERE u.user_mail = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sMail)) {
			return $aRow['user_id'];
		}
		return null;
	}
	
	public function GetUserByLogin($sLogin) {		
		$sql = "SELECT 
				u.user_id  
			FROM 
				".Config::Get('db.table.user')." as u 	
			WHERE 
				u.user_login = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sLogin)) {
			return $aRow['user_id'];
		}
		return null;
	}
	
	
	public function GetUsersByDateLast($iLimit) {
		$sql = "SELECT 
			user_id		 
			FROM 
				".Config::Get('db.table.session')."				
			ORDER BY 
				session_date_last DESC		
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}
		return $aReturn;
	}
	
	public function GetUsersByDateRegister($iLimit) {
		$sql = "SELECT 
			user_id		 
			FROM 
				".Config::Get('db.table.user')."	  
			WHERE
				 user_activate = 1			
			ORDER BY 
				user_id DESC		
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}
		return $aReturn;
	}
	
	public function GetUsersRating($sType,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 
			user_id		 
			FROM 
				".Config::Get('db.table.user')."
			WHERE 
				user_rating ".($sType=='good' ? '>=0' : '<0')."	 and user_activate = 1			
			ORDER BY 
				user_rating ".($sType=='good' ? 'DESC' : 'ASC').", user_skill desc	
			LIMIT ?d, ?d				
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}
		return $aReturn;
	}
	
	
	public function GetCountUsers() {
		$sql = "SELECT count(user_id) as count FROM ".Config::Get('db.table.user')."  WHERE user_activate = 1";			
		$result=$this->oDb->selectRow($sql);
		return $result['count'];
	}
	
	public function GetCountUsersActive($sDateActive) {
		$sql = "SELECT count(user_id) as count FROM ".Config::Get('db.table.session')." WHERE session_date_last >= ? ";			
		$result=$this->oDb->selectRow($sql,$sDateActive);
		return $result['count'];
	}
	
	
	public function GetCountUsersSex() {
		$sql = "SELECT user_profile_sex  AS ARRAY_KEY, count(user_id) as count FROM ".Config::Get('db.table.user')." WHERE user_activate = 1 GROUP BY user_profile_sex ";			
		$result=$this->oDb->select($sql);
		return $result;
	}
	
	public function GetCountUsersCountry($sLimit) {
		$sql = "
			SELECT 
				cu.count,
				c.country_name as name
			FROM ( 
					SELECT 
						count(user_id) as count,
						country_id 
					FROM 
						".Config::Get('db.table.country_user')."
					GROUP BY country_id ORDER BY count DESC LIMIT 0, ?d 
				) as cu
				JOIN ".Config::Get('db.table.country')." as c on cu.country_id=c.country_id	
			ORDER BY c.country_name		
		";		
		$result=$this->oDb->select($sql,$sLimit);
		return $result;
	}
	
	public function GetCountUsersCity($sLimit) {
		$sql = "
			SELECT 
				cu.count,
				c.city_name as name
			FROM ( 
					SELECT 
						count(user_id) as count,
						city_id 
					FROM 
						".Config::Get('db.table.city_user')."
					GROUP BY city_id ORDER BY count DESC LIMIT 0, ?d
				) as cu
				JOIN ".Config::Get('db.table.city')." as c on cu.city_id=c.city_id		
			ORDER BY c.city_name	
		";		
		$result=$this->oDb->select($sql,$sLimit);
		return $result;
	}
	
	public function GetUsersByLoginLike($sUserLogin,$iLimit) {		
		$sql = "SELECT 
				user_id					 
			FROM 
				".Config::Get('db.table.user')."	
			WHERE
				user_activate = 1
				and
				user_login LIKE ?			
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserLogin.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}
		return $aReturn;
	}
	
	
	
	
	
	public function AddFriend(ModuleUser_EntityFriend $oFriend) {
		$sql = "INSERT INTO ".Config::Get('db.table.friend')." 
			(user_from,
			user_to,
			status_from,
			status_to		
			)
			VALUES(?d, ?d, ?d, ?d)
		";			
		if (
			$this->oDb->query(
				$sql,
				$oFriend->getUserFrom(),
				$oFriend->getUserTo(),
				$oFriend->getStatusFrom(),
				$oFriend->getStatusTo()
			)===0
		) {
			return true;
		}		
		return false;
	}
	
	public function EraseFriend(ModuleUser_EntityFriend $oFriend) {
		$sql = "DELETE FROM ".Config::Get('db.table.friend')." 
			WHERE
				user_from = ?d
				AND
				user_to = ?d				
		";			
		if ($this->oDb->query($sql,$oFriend->getUserFrom(),$oFriend->getUserTo())) 
		{
			return true;
		}		
		return false;
	}
	
	public function UpdateFriend(ModuleUser_EntityFriend $oFriend) {
		$sql = "
			UPDATE ".Config::Get('db.table.friend')."
			SET 
				status_from = ?d,
				status_to   = ?d 
			WHERE
				user_from = ?d
				AND
				user_to = ?d		
		";			
		if(
			$this->oDb->query(
				$sql,
				$oFriend->getStatusFrom(),
				$oFriend->getStatusTo(),
				$oFriend->getUserFrom(),
				$oFriend->getUserTo()
			)
		) {
			return true;
		}		
		return false;
	}
	
	/**
	 * Получить отношей дружбы по массиву идентификаторов
	 *
	 * @param  array  $aArrayId
	 * @param  string $sUserId
	 * @param  int    $iStatus
	 * @return array
	 */
	public function GetFriendsByArrayId($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT 
					*						 
				FROM 
					".Config::Get('db.table.friend')." 				
				WHERE 
					( `user_from`=?d AND `user_to` IN(?a) )
					OR
					( `user_from` IN(?a) AND `user_to`=?d )
				";			
		$aRows=$this->oDb->select(
			$sql,
			$sUserId,$aArrayId,
			$aArrayId,$sUserId
		);			
		$aRes=array();
		if ($aRows) {
			foreach ($aRows as $aRow) {
				$aRow['user']=$sUserId;
				$aRes[]=Engine::GetEntity('User_Friend',$aRow);
			}
		}			
		return $aRes;
	}
	
	/**
	 * Получить список друзей указанного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int    $iStatus
	 * @return array
	 */
	public function GetUsersFriend($sUserId) {
		$sql = "SELECT 
					uf.user_from,
					uf.user_to							
				FROM 
					".Config::Get('db.table.friend')." as uf				
				WHERE 	
					( uf.user_from = ?d
					OR
					uf.user_to = ?d )
					AND 
					( 	uf.status_from + uf.status_to = ?d 
					OR
						(uf.status_from = ?d AND uf.status_to = ?d )
					)
					;";
		$aUsers=array();
		if ($aRows=$this->oDb->select(
				$sql,
				$sUserId,
				$sUserId,
				ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_OFFER,
				ModuleUser::USER_FRIEND_ACCEPT,
				ModuleUser::USER_FRIEND_ACCEPT 
			)
		) {
			foreach ($aRows as $aUser) {
				$aUsers[]=($aUser['user_from']==$sUserId)
							? $aUser['user_to']
							: $aUser['user_from'];
			}
		}
		return array_unique($aUsers);
	}
	
	/**
	 * Получить список заявок на добавление в друзья от указанного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int    $iStatus Статус запроса со стороны добавляемого
	 * @return array
	 */
	public function GetUsersFriendOffer($sUserId,$iStatus=ModuleUser::USER_FRIEND_NULL) {
		$sql = "SELECT 
					uf.user_to							
				FROM 
					".Config::Get('db.table.friend')." as uf				
				WHERE 	
					uf.user_from = ?d
					AND 
					uf.status_from = ?d
					AND
					uf.status_to = ?d
				;";
		$aUsers=array();
		if ($aRows=$this->oDb->select(
				$sql,
				$sUserId,
				ModuleUser::USER_FRIEND_OFFER,
				$iStatus 
			)
		) {
			foreach ($aRows as $aUser) {
				$aUsers[]=$aUser['user_to'];
			}
		}
		return $aUsers;
	}
	
	/**
	 * Получить список заявок на добавление в друзья от указанного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int    $iStatus Статус запроса со стороны самого пользователя
	 * @return array
	 */
	public function GetUserSelfFriendOffer($sUserId,$iStatus=ModuleUser::USER_FRIEND_NULL) {
		$sql = "SELECT 
					uf.user_from
				FROM 
					".Config::Get('db.table.friend')." as uf				
				WHERE 	
					uf.user_to = ?d
					AND 
					uf.status_from = ?d
					AND
					uf.status_to = ?d
				;";
		$aUsers=array();
		if ($aRows=$this->oDb->select(
				$sql,
				$sUserId,
				ModuleUser::USER_FRIEND_OFFER,
				$iStatus 
			)
		) {
			foreach ($aRows as $aUser) {
				$aUsers[]=$aUser['user_from'];
			}
		}
		return $aUsers;
	}
	
	
	
	
	
	
	public function GetInviteByCode($sCode,$iUsed=0) {
		$sql = "SELECT * FROM ".Config::Get('db.table.invite')." WHERE invite_code = ? and invite_used = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sCode,$iUsed)) {
			return Engine::GetEntity('User_Invite',$aRow);
		}
		return null;
	}
	
	public function AddInvite(ModuleUser_EntityInvite $oInvite) {
		$sql = "INSERT INTO ".Config::Get('db.table.invite')." 
			(invite_code,
			user_from_id,
			invite_date_add			
			)
			VALUES(?,  ?,	?)
		";			
		if ($iId=$this->oDb->query($sql,$oInvite->getCode(),$oInvite->getUserFromId(),$oInvite->getDateAdd())) {
			return $iId;
		}		
		return false;
	}
	
	public function UpdateInvite(ModuleUser_EntityInvite $oInvite) {
		$sql = "UPDATE ".Config::Get('db.table.invite')." 
			SET 
				user_to_id = ? ,
				invite_date_used = ? ,	
				invite_used =? 		
			WHERE invite_id = ?
		";			
		if ($this->oDb->query($sql,$oInvite->getUserToId(), $oInvite->getDateUsed(), $oInvite->getUsed(), $oInvite->getId())) {
			return true;
		}		
		return false;
	}
	
	public function GetCountInviteUsedByDate($sUserIdFrom,$sDate) {
		$sql = "SELECT count(invite_id) as count FROM ".Config::Get('db.table.invite')." WHERE user_from_id = ?d and invite_date_add >= ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sUserIdFrom,$sDate)) {
			return $aRow['count'];
		}
		return 0;
	}
	
	public function GetCountInviteUsed($sUserIdFrom) {
		$sql = "SELECT count(invite_id) as count FROM ".Config::Get('db.table.invite')." WHERE user_from_id = ?d";
		if ($aRow=$this->oDb->selectRow($sql,$sUserIdFrom)) {
			return $aRow['count'];
		}
		return 0;
	}
	
	public function GetUsersInvite($sUserId) {					
		$sql = "SELECT 
					i.user_to_id										
				FROM 
					".Config::Get('db.table.invite')." as i				
				WHERE 	
					i.user_from_id = ?d	";
		$aUsers=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aUser) {
				$aUsers[]=$aUser['user_to_id'];
			}
		}
		return $aUsers;
	}
	
	public function GetUserInviteFrom($sUserIdTo) {
		$sql = "SELECT 
					i.user_from_id										
				FROM 
					".Config::Get('db.table.invite')." as i				
				WHERE 	
					i.user_to_id = ?d																		
				LIMIT 0,1;	
					";
		if ($aRow=$this->oDb->selectRow($sql,$sUserIdTo)) {
			return $aRow['user_from_id'];
		}
		return null;
	}
	
	public function SetCountryUser($sCountryId,$sUserId) {		
		$sql = "REPLACE ".Config::Get('db.table.country_user')." 
			SET 
				country_id = ? ,
				user_id = ? 
		";			
		return $this->oDb->query($sql,$sCountryId,$sUserId);
	}
	
	public function GetCountryByName($sName) {
		$sql = "SELECT * FROM ".Config::Get('db.table.country')." WHERE country_name = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sName)) {
			return Engine::GetEntity('User_Country',$aRow);
		}
		return null;
	}
	
	public function GetUsersByCountry($sCountry,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "
			SELECT cu.user_id
			FROM
				".Config::Get('db.table.country')." as c,
				".Config::Get('db.table.country_user')." as cu 
			WHERE
				c.country_name = ?
				AND
				c.country_id=cu.country_id 				
			ORDER BY cu.user_id DESC
			LIMIT ?d, ?d ";	
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sCountry,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}
		return $aReturn;
	}
	
	public function GetUsersByCity($sCity,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "
			SELECT cu.user_id
			FROM
				".Config::Get('db.table.city')." as c,
				".Config::Get('db.table.city_user')." as cu
			WHERE
				c.city_name = ?
				AND
				c.city_id=cu.city_id 				
			ORDER BY cu.user_id DESC
			LIMIT ?d, ?d ";	
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sCity,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}
		return $aReturn;
	}
	
	public function AddCountry(ModuleUser_EntityCountry $oCountry) {
		$sql = "INSERT INTO ".Config::Get('db.table.country')." 
			(country_name)
			VALUES(?)
		";			
		if ($iId=$this->oDb->query($sql,$oCountry->getName())) {
			return $iId;
		}		
		return false;
	}
	
	
	public function SetCityUser($sCityId,$sUserId) {		
		$sql = "REPLACE ".Config::Get('db.table.city_user')." 
			SET 
				city_id = ? ,
				user_id = ? 
		";			
		return $this->oDb->query($sql,$sCityId,$sUserId);
	}
	
	public function GetCityByName($sName) {
		$sql = "SELECT * FROM ".Config::Get('db.table.city')." WHERE city_name = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sName)) {
			return Engine::GetEntity('User_City',$aRow);
		}
		return null;
	}
	
	public function AddCity(ModuleUser_EntityCity $oCity) {
		$sql = "INSERT INTO ".Config::Get('db.table.city')." 
			(city_name)
			VALUES(?)
		";			
		if ($iId=$this->oDb->query($sql,$oCity->getName())) {
			return $iId;
		}		
		return false;
	}
	
	public function GetCityByNameLike($sName,$iLimit) {		
		$sql = "SELECT 
				*					 
			FROM 
				".Config::Get('db.table.city')."	
			WHERE
				city_name LIKE ?														
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sName.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('User_City',$aRow);
			}
		}
		return $aReturn;
	}
	
	public function GetCountryByNameLike($sName,$iLimit) {		
		$sql = "SELECT 
				*					 
			FROM 
				".Config::Get('db.table.country')."	
			WHERE
				country_name LIKE ?														
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sName.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('User_Country',$aRow);
			}
		}
		return $aReturn;
	}
	
	public function AddReminder(ModuleUser_EntityReminder $oReminder) {		
		$sql = "REPLACE ".Config::Get('db.table.reminder')." 
			SET 
				reminder_code = ? ,
				user_id = ? ,
				reminder_date_add = ? ,
				reminder_date_used = ? ,
				reminder_date_expire = ? ,
				reminde_is_used = ? 				
		";			
		return $this->oDb->query($sql,$oReminder->getCode(),$oReminder->getUserId(),$oReminder->getDateAdd(),$oReminder->getDateUsed(),$oReminder->getDateExpire(),$oReminder->getIsUsed());
	}
	
	public function UpdateReminder(ModuleUser_EntityReminder $oReminder) {
		return $this->AddReminder($oReminder);
	}
	
	public function GetReminderByCode($sCode) {
		$sql = "SELECT 
					*										
				FROM 
					".Config::Get('db.table.reminder')." 				
				WHERE 	
					reminder_code = ?";
		if ($aRow=$this->oDb->selectRow($sql,$sCode)) {
			return Engine::GetEntity('User_Reminder',$aRow);
		}
		return null;
	}
}
?>
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

class Mapper_User extends Mapper {
	protected $oUserCurrent=null;
	
	public function SetUserCurrent($oUserCurrent)  {
		$this->oUserCurrent=$oUserCurrent;
	}
	
	public function Add(UserEntity_User $oUser) {
		$sql = "INSERT INTO ".DB_TABLE_USER." 
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
	
	public function Update(UserEntity_User $oUser) {
		$sql = "UPDATE ".DB_TABLE_USER." 
			SET 
				user_password = ? ,
				user_mail = ? ,	
				user_key =? ,		
				user_skill = ? ,
				user_date_last = ? ,
				user_date_activate = ? ,
				user_ip_last = ?, 
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
				user_profile_avatar_type = ? ,	
				user_settings_notice_new_topic = ?	,
				user_settings_notice_new_comment = ? ,
				user_settings_notice_new_talk = ?	,
				user_settings_notice_reply_comment = ?		
			WHERE user_id = ?
		";			
		if ($this->oDb->query($sql,$oUser->getPassword(),
								   $oUser->getMail(),
								   $oUser->getKey(),
								   $oUser->getSkill(),
								   $oUser->getDateLast(),
								   $oUser->getDateActivate(),
								   $oUser->getIpLast(),
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
								   $oUser->getProfileAvatarType(),
								   $oUser->getSettingsNoticeNewTopic(),
								   $oUser->getSettingsNoticeNewComment(),
								   $oUser->getSettingsNoticeNewTalk(),
								   $oUser->getSettingsNoticeReplyComment(),				   
								   $oUser->getId())) {
			return true;
		}		
		return false;
	}
	
	
	public function GetUserByActivateKey($sKey) {
		$sql = "SELECT * FROM ".DB_TABLE_USER." WHERE user_activate_key = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sKey)) {
			return new UserEntity_User($aRow);
		}
		return null;
	}
	
	public function GetUserByKey($sKey) {
		$sql = "SELECT * FROM ".DB_TABLE_USER." WHERE user_key = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sKey)) {
			return new UserEntity_User($aRow);
		}
		return null;
	}
	
	public function GetUserById($sKey) {
		$sql = "SELECT 
				u.*,
				IF(ua.user_id IS NULL,0,1) as user_is_administrator 
				FROM 
					".DB_TABLE_USER." as u
					LEFT JOIN ".DB_TABLE_USER_ADMINISTRATOR." AS ua ON u.user_id=ua.user_id
				WHERE 
					u.user_id = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sKey)) {
			return new UserEntity_User($aRow);
		}
		return null;
	}
	
	public function GetUserByMail($sMail) {
		$sql = "SELECT * FROM ".DB_TABLE_USER." WHERE LOWER(user_mail) = ? ";
		if ($aRow=$this->oDb->selectRow($sql,strtolower($sMail))) {
			return new UserEntity_User($aRow);
		}
		return null;
	}
	
	public function GetUserByLogin($sLogin) {
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}
		$sql = "SELECT 
				u.*,
				IF(uv.user_id IS NULL,0,1) as user_is_vote,
				uv.vote_delta as user_vote_delta,
				IF(uf.user_id IS NULL,0,1) as user_is_frend 
			FROM 
				".DB_TABLE_USER." as u
				
				LEFT JOIN (
						SELECT
							user_id,
							vote_delta												
						FROM ".DB_TABLE_USER_VOTE." 
						WHERE user_voter_id = ?d
					) AS uv ON uv.user_id = u.user_id
					
				LEFT JOIN (
						SELECT
							user_id,
							user_frend_id												
						FROM ".DB_TABLE_FREND." 
						WHERE user_id = ?d
					) AS uf ON uf.user_frend_id = u.user_id
				
			WHERE 
				LOWER(u.user_login) = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$iCurrentUserId,$iCurrentUserId,strtolower($sLogin))) {
			return new UserEntity_User($aRow);
		}
		return null;
	}
	
	public function GetUserVote($sUserId,$sVoterId) {
		$sql = "SELECT * FROM ".DB_TABLE_USER_VOTE." WHERE user_id = ?d and user_voter_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId,$sVoterId)) {
			return new UserEntity_UserVote($aRow);
		}
		return null;
	}
	
	public function AddUserVote(UserEntity_UserVote $oUserVote) {
		$sql = "INSERT INTO ".DB_TABLE_USER_VOTE." 
			(user_id,
			user_voter_id,
			vote_delta		
			)
			VALUES(?d,  ?d,	?f)
		";			
		if ($this->oDb->query($sql,$oUserVote->getUserId(),$oUserVote->getVoterId(),$oUserVote->getDelta())===0) 
		{
			return true;
		}		
		return false;
	}
	
	public function GetUsersByDateLast($iLimit) {
		$sql = "SELECT 
			*		 
			FROM 
				".DB_TABLE_USER."	
			 WHERE 
			  user_activate = 1			
			ORDER BY 
				user_date_last DESC		
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new UserEntity_User($aRow);
			}
		}
		return $aReturn;
	}
	
	public function GetUsersByDateRegister($iLimit) {
		$sql = "SELECT 
			*		 
			FROM 
				".DB_TABLE_USER."	  
			WHERE
				 user_activate = 1			
			ORDER BY 
				user_date_register DESC		
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new UserEntity_User($aRow);
			}
		}
		return $aReturn;
	}
	
	public function GetUsersRating($sType,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 
			*		 
			FROM 
				".DB_TABLE_USER."
			WHERE 
				user_rating ".($sType=='good' ? '>=0' : '<0')."	 and user_activate = 1			
			ORDER BY 
				user_rating ".($sType=='good' ? 'DESC' : 'ASC').", user_skill desc	
			LIMIT ?d, ?d				
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new UserEntity_User($aRow);
			}
		}
		return $aReturn;
	}
	
	
	public function GetCountUsers() {
		$sql = "SELECT count(user_id) as count FROM ".DB_TABLE_USER."  WHERE user_activate = 1";			
		$result=$this->oDb->selectRow($sql);
		return $result['count'];
	}
	
	public function GetCountUsersActive($sDateActive) {
		$sql = "SELECT count(user_id) as count FROM ".DB_TABLE_USER." WHERE user_date_last >= ?  and user_activate = 1";			
		$result=$this->oDb->selectRow($sql,$sDateActive);
		return $result['count'];
	}
	
	public function GetCountUsersInactive($sDateInactive) {
		$sql = "SELECT count(user_id) as count FROM ".DB_TABLE_USER." WHERE user_date_last < ? and user_activate = 1";			
		$result=$this->oDb->selectRow($sql,$sDateInactive);
		return $result['count'];
	}
	
	public function GetCountUsersSex() {
		$sql = "SELECT user_profile_sex  AS ARRAY_KEY, count(user_id) as count FROM ".DB_TABLE_USER." GROUP BY user_profile_sex ";			
		$result=$this->oDb->select($sql);
		return $result;
	}
	
	public function GetCountUsersCountry() {
		$sql = "SELECT user_profile_country  AS ARRAY_KEY, count(user_id) as count FROM ".DB_TABLE_USER." WHERE NOT user_profile_country IS NULL and user_profile_country<>'' GROUP BY user_profile_country ";			
		$result=$this->oDb->select($sql);
		return $result;
	}
	
	public function GetCountUsersCity() {
		$sql = "SELECT user_profile_city  AS ARRAY_KEY, count(user_id) as count FROM ".DB_TABLE_USER." WHERE NOT user_profile_city IS NULL and user_profile_city<>'' GROUP BY user_profile_city ";			
		$result=$this->oDb->select($sql);
		return $result;
	}
	
	public function GetUsersByLoginLike($sUserLogin,$iLimit) {
		$sUserLogin=mb_strtolower($sUserLogin,"UTF-8");
		$sql = "SELECT 
				user_login					 
			FROM 
				".DB_TABLE_USER."	
			WHERE
				LOWER(user_login) LIKE ?		
				and 
				user_activate = 1								
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserLogin.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new UserEntity_User($aRow);
			}
		}
		return $aReturn;
	}
	
	
	public function AddFrend(UserEntity_Frend $oFrend) {
		$sql = "INSERT INTO ".DB_TABLE_FREND." 
			(user_id,
			user_frend_id		
			)
			VALUES(?d,  ?d)
		";			
		if ($this->oDb->query($sql,$oFrend->getUserId(),$oFrend->getFrendId())===0) 
		{
			return true;
		}		
		return false;
	}
	
	public function DeleteFrend(UserEntity_Frend $oFrend) {
		$sql = "DELETE FROM ".DB_TABLE_FREND." 
			WHERE
				user_id = ?d
				AND
				user_frend_id = ?d				
		";			
		if ($this->oDb->query($sql,$oFrend->getUserId(),$oFrend->getFrendId())) 
		{
			return true;
		}		
		return false;
	}
	
	public function GetFrend($sFrendId,$sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_FREND." WHERE user_id = ?d and user_frend_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId,$sFrendId)) {
			return new UserEntity_Frend($aRow);
		}
		return null;
	}
	
	public function GetUsersFrend($sUserId) {					
		$sql = "SELECT 
					u.*										
				FROM 
					".DB_TABLE_FREND." as uf,
					".DB_TABLE_USER." as u					
				WHERE 	
					uf.user_id = ?d	
					AND
					uf.user_frend_id = u.user_id
					AND			
					u.user_activate = 1												
				ORDER BY u.user_login;	
					";
		$aUsers=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aUser) {
				$aUsers[]=new UserEntity_User($aUser);
			}
		}
		return $aUsers;
	}
	
	public function GetUsersSelfFrend($sUserId) {					
		$sql = "SELECT 
					u.*										
				FROM 
					".DB_TABLE_FREND." as uf,
					".DB_TABLE_USER." as u					
				WHERE 	
					uf.user_frend_id = ?d	
					AND
					uf.user_id = u.user_id
					AND			
					u.user_activate = 1												
				ORDER BY u.user_login;	
					";
		$aUsers=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aUser) {
				$aUsers[]=new UserEntity_User($aUser);
			}
		}
		return $aUsers;
	}
	
	public function GetInviteByCode($sCode,$iUsed=0) {
		$sql = "SELECT * FROM ".DB_TABLE_INVITE." WHERE invite_code = ? and invite_used = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sCode,$iUsed)) {
			return new UserEntity_Invite($aRow);
		}
		return null;
	}
	
	public function AddInvite(UserEntity_Invite $oInvite) {
		$sql = "INSERT INTO ".DB_TABLE_INVITE." 
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
	
	public function UpdateInvite(UserEntity_Invite $oInvite) {
		$sql = "UPDATE ".DB_TABLE_INVITE." 
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
}
?>
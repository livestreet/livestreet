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
                user_activate_key = ? ,
				user_profile_name = ? ,
				user_profile_sex = ? ,
				user_profile_country = ? ,
				user_profile_region = ? ,
				user_profile_city = ? ,
				user_profile_birthday = ? ,
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
								   $oUser->getActivateKey(),
								   $oUser->getProfileName(),
								   $oUser->getProfileSex(),
								   $oUser->getProfileCountry(),
								   $oUser->getProfileRegion(),
								   $oUser->getProfileCity(),
								   $oUser->getProfileBirthday(),
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


	public function GetCountUsers() {
		$sql = "SELECT count(*) as count FROM ".Config::Get('db.table.user')."  WHERE user_activate = 1";
		$result=$this->oDb->selectRow($sql);
		return $result['count'];
	}

	public function GetCountUsersActive($sDateActive) {
		$sql = "SELECT count(*) as count FROM ".Config::Get('db.table.session')." WHERE session_date_last >= ? ";
		$result=$this->oDb->selectRow($sql,$sDateActive);
		return $result['count'];
	}


	public function GetCountUsersSex() {
		$sql = "SELECT user_profile_sex  AS ARRAY_KEY, count(*) as count FROM ".Config::Get('db.table.user')." WHERE user_activate = 1 GROUP BY user_profile_sex ";
		$result=$this->oDb->select($sql);
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
	public function GetUsersFriend($sUserId,&$iCount,$iCurrPage,$iPerPage) {
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
				LIMIT ?d, ?d ;";
		$aUsers=array();
		if ($aRows=$this->oDb->selectPage(
				$iCount,
				$sql,
				$sUserId,
				$sUserId,
				ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_OFFER,
				ModuleUser::USER_FRIEND_ACCEPT,
				ModuleUser::USER_FRIEND_ACCEPT,
				($iCurrPage-1)*$iPerPage, $iPerPage
			)
		) {
			foreach ($aRows as $aUser) {
				$aUsers[]=($aUser['user_from']==$sUserId)
							? $aUser['user_to']
							: $aUser['user_from'];
			}
		}
		rsort($aUsers,SORT_NUMERIC);
		return array_unique($aUsers);
	}

	public function GetCountUsersFriend($sUserId) {
		$sql = "SELECT
					count(*) as c
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
					)";
		if ($aRow=$this->oDb->selectRow(
			$sql,
			$sUserId,
			$sUserId,
			ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_OFFER,
			ModuleUser::USER_FRIEND_ACCEPT,
			ModuleUser::USER_FRIEND_ACCEPT
		)
		) {
			return $aRow['c'];
		}
		return 0;
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

	public function getUserFields($aType) {
		if (!is_null($aType) and !is_array($aType)) {
			$aType=array($aType);
		}
		$sql = 'SELECT * FROM '.Config::Get('db.table.user_field').' WHERE 1=1 { and type IN (?a) }';
		$aFields = $this->oDb->select($sql,(is_null($aType) or !count($aType)) ? DBSIMPLE_SKIP : $aType);
		if (!count($aFields)) {
			return array();
		}
		$aResult = array();
		foreach($aFields as $aField) {
			$aResult[$aField['id']] = Engine::GetEntity('User_Field', $aField);
		}
		return $aResult;
	}

	public function getUserFieldValueByName($iUserId, $sName) {
		$sql = 'SELECT value FROM '.Config::Get('db.table.user_field_value').'  WHERE
                        user_id = ?d
                        AND
                        field_id = (SELECT id FROM '.Config::Get('db.table.user_field').' WHERE name =?)';
		$ret = $this->oDb->selectCol($sql, $iUserId, $sName);
		return $ret[0];
	}

	public function getUserFieldsValues($iUserId, $bOnlyNoEmpty, $aType) {
		if (!is_null($aType) and !is_array($aType)) {
			$aType=array($aType);
		}

		/**
		 * Если запрашиваем без типа, то необходимо вернуть ВСЕ возможные поля с этим типом в не звависимости указал ли их пользователь у себя в профили или нет
		 * Выглядит костыльно
		 */
		if (is_array($aType) and count($aType)==1 and $aType[0]=='') {
			$sql='SELECT f.*, v.value FROM '.Config::Get('db.table.user_field').' as f LEFT JOIN '.Config::Get('db.table.user_field_value').' as v ON f.id = v.field_id WHERE v.user_id = ?d and f.type IN (?a)';

		} else {
			$sql = 'SELECT v.value, f.* FROM '.Config::Get('db.table.user_field_value').' as v, '.Config::Get('db.table.user_field').' as f
			WHERE v.user_id = ?d AND v.field_id = f.id { and f.type IN (?a) }';
		}
		$aResult=array();
		if ($aRows=$this->oDb->select($sql, $iUserId,(is_null($aType) or !count($aType)) ? DBSIMPLE_SKIP : $aType)) {
			foreach($aRows as $aRow) {
				if ($bOnlyNoEmpty and !$aRow['value']) {
					continue;
				}
				$aResult[]=Engine::GetEntity('User_Field', $aRow);
			}
		}
		return $aResult;
	}

	public function setUserFieldsValues($iUserId, $aFields, $iCountMax) {
		if (!count($aFields)) return;
		foreach ($aFields as $iId =>$sValue) {
			$sql = 'SELECT count(*) as c FROM '.Config::Get('db.table.user_field_value').' WHERE user_id = ?d AND field_id = ?';
			$aRow=$this->oDb->selectRow($sql, $iUserId, $iId);
			$iCount=isset($aRow['c']) ? $aRow['c'] : 0;
			if ($iCount<$iCountMax) {
				$sql = 'INSERT INTO '.Config::Get('db.table.user_field_value').' SET value = ?, user_id = ?d, field_id = ?';
			} elseif ($iCount==$iCountMax and $iCount==1) {
				$sql = 'UPDATE '.Config::Get('db.table.user_field_value').' SET value = ? WHERE user_id = ?d AND field_id = ?';
			} else {
				continue;
			}
			$this->oDb->query($sql, $sValue, $iUserId, $iId);
		}
	}

	public function addUserField($oField) {
		$sql =  'INSERT INTO '.Config::Get('db.table.user_field').' SET
                    name = ?, title = ?, pattern = ?, type = ?';
		return $this->oDb->query($sql, $oField->getName(), $oField->getTitle(), $oField->getPattern(), $oField->getType());
	}

	public function deleteUserField($iId) {
		$sql = 'DELETE FROM '.Config::Get('db.table.user_field_value').' WHERE field_id = ?d';
		$this->oDb->query($sql, $iId);
		$sql =  'DELETE FROM '.Config::Get('db.table.user_field').' WHERE
                    id = ?d';
		$this->oDb->query($sql, $iId);
	}

	public function updateUserField($oField) {
		$sql =  'UPDATE '.Config::Get('db.table.user_field').' SET
                    name = ?, title = ?, pattern = ?, type = ?
                    WHERE id = ?d';
		$this->oDb->query($sql, $oField->getName(), $oField->getTitle(), $oField->getPattern(), $oField->getType(), $oField->getId());
	}

	public function userFieldExistsByName($sName, $iId) {
		$sql = 'SELECT id FROM  '.Config::Get('db.table.user_field').' WHERE name = ? {AND id != ?d}';
		return $this->oDb->select($sql, $sName, $iId ? $iId : DBSIMPLE_SKIP);
	}

	public function userFieldExistsById($iId) {
		$sql = 'SELECT id FROM  '.Config::Get('db.table.user_field').' WHERE id = ?d';
		return $this->oDb->select($sql, $iId);
	}

	public function DeleteUserFieldValues($iUserId,$aType) {
		if (!is_null($aType) and !is_array($aType)) {
			$aType=array($aType);
		}
		$sql = 'DELETE FROM '.Config::Get('db.table.user_field_value').'
			WHERE user_id = ?d AND field_id IN (
				SELECT id FROM '.Config::Get('db.table.user_field').' WHERE 1=1 { and type IN (?a) }
			)';
		return $this->oDb->query($sql,$iUserId,(is_null($aType) or !count($aType)) ? DBSIMPLE_SKIP : $aType);
	}


	public function GetUserNotesByUserId($iUserId,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "
			SELECT *
			FROM
				".Config::Get('db.table.user_note')."
			WHERE
				user_id = ?d
			ORDER BY id DESC
			LIMIT ?d, ?d ";
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$iUserId,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('ModuleUser_EntityNote',$aRow);
			}
		}
		return $aReturn;
	}

	public function GetCountUserNotesByUserId($iUserId) {
		$sql = "
			SELECT count(*) as c
			FROM
				".Config::Get('db.table.user_note')."
			WHERE
				user_id = ?d
			";
		if ($aRow=$this->oDb->selectRow($sql,$iUserId)) {
			return $aRow['c'];
		}
		return 0;
	}

	public function GetUserNote($iTargetUserId,$iUserId) {
		$sql = "SELECT * FROM ".Config::Get('db.table.user_note')." WHERE target_user_id = ?d and user_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$iTargetUserId,$iUserId)) {
			return Engine::GetEntity('ModuleUser_EntityNote',$aRow);
		}
		return null;
	}

	public function GetUserNoteById($iId) {
		$sql = "SELECT * FROM ".Config::Get('db.table.user_note')." WHERE id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$iId)) {
			return Engine::GetEntity('ModuleUser_EntityNote',$aRow);
		}
		return null;
	}

	public function DeleteUserNoteById($iId) {
		$sql = "DELETE FROM ".Config::Get('db.table.user_note')." WHERE id = ?d ";
		return $this->oDb->query($sql,$iId);
	}

	public function AddUserNote($oNote) {
		$sql = "INSERT INTO ".Config::Get('db.table.user_note')." SET ?a ";
		if ($iId=$this->oDb->query($sql,$oNote->_getData())) {
			return $iId;
		}
		return false;
	}


	public function UpdateUserNote($oNote) {
		$sql = "UPDATE ".Config::Get('db.table.user_note')."
			SET
			 	text = ?
			WHERE id = ?d
		";
		return $this->oDb->query($sql,$oNote->getText(),
								 $oNote->getId());
	}

	public function GetUsersByFilter($aFilter,$aOrder,&$iCount,$iCurrPage,$iPerPage) {
		$aOrderAllow=array('user_id','user_login','user_date_register','user_rating','user_skill','user_profile_name');
		$sOrder='';
		foreach ($aOrder as $key=>$value) {
			if (!in_array($key,$aOrderAllow)) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');
		if ($sOrder=='') {
			$sOrder=' user_id desc ';
		}

		$sql = "SELECT
					user_id
				FROM
					".Config::Get('db.table.user')."
				WHERE
					1 = 1
					{ AND user_id = ?d }
					{ AND user_mail = ? }
					{ AND user_password = ? }
					{ AND user_ip_register = ? }
					{ AND user_activate = ?d }
					{ AND user_activate_key = ? }
					{ AND user_profile_sex = ? }
					{ AND user_login LIKE ? }
					{ AND user_profile_name LIKE ? }
				ORDER by {$sOrder}
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['mail']) ? $aFilter['mail'] : DBSIMPLE_SKIP,
										  isset($aFilter['password']) ? $aFilter['password'] : DBSIMPLE_SKIP,
										  isset($aFilter['ip_register']) ? $aFilter['ip_register'] : DBSIMPLE_SKIP,
										  isset($aFilter['activate']) ? $aFilter['activate'] : DBSIMPLE_SKIP,
										  isset($aFilter['activate_key']) ? $aFilter['activate_key'] : DBSIMPLE_SKIP,
										  isset($aFilter['profile_sex']) ? $aFilter['profile_sex'] : DBSIMPLE_SKIP,
										  isset($aFilter['login']) ? $aFilter['login'] : DBSIMPLE_SKIP,
										  isset($aFilter['profile_name']) ? $aFilter['profile_name'] : DBSIMPLE_SKIP,
										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=$aRow['user_id'];
			}
		}
		return $aResult;
	}


	public function GetGroupPrefixUser($iPrefixLength=1) {
		$sql = "
			SELECT SUBSTRING(`user_login` FROM 1 FOR ?d ) as prefix
			FROM
				".Config::Get('db.table.user')."
			WHERE
				user_activate = 1
			GROUP BY prefix
			ORDER BY prefix ";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$iPrefixLength)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=mb_strtoupper($aRow['prefix'],'utf-8');
			}
		}
		return $aReturn;
	}
}
?>
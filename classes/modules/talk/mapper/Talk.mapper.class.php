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
 * Объект маппера для работы с БД
 *
 * @package modules.talk
 * @since 1.0
 */
class ModuleTalk_MapperTalk extends Mapper {
	/**
	 * Добавляет новую тему разговора
	 *
	 * @param ModuleTalk_EntityTalk $oTalk Объект сообщения
	 * @return int|bool
	 */
	public function AddTalk(ModuleTalk_EntityTalk $oTalk) {
		$sql = "INSERT INTO ".Config::Get('db.table.talk')." 
			(user_id,
			talk_title,
			talk_text,
			talk_date,
			talk_date_last,
			talk_user_id_last,
			talk_user_ip
			)
			VALUES(?d,	?,	?,	?,  ?, ?, ?)
		";
		if ($iId=$this->oDb->query($sql,$oTalk->getUserId(),$oTalk->getTitle(),$oTalk->getText(),$oTalk->getDate(),$oTalk->getDateLast(),$oTalk->getUserIdLast(),$oTalk->getUserIp()))
		{
			return $iId;
		}
		return false;
	}
	/**
	 * Удаление письма из БД
	 *
	 * @param int $iTalkId	ID разговора
	 */
	public function DeleteTalk($iTalkId) {
		// Удаление беседы
		$sql = 'DELETE FROM '.Config::Get('db.table.talk').'  WHERE talk_id = ?d';
		$this->oDb->query($sql,$iTalkId);
		// Физическое удаление пользователей беседы (не флагом)
		$sql = 'DELETE FROM '.Config::Get('db.table.talk_user').'  WHERE talk_id = ?d';
		$this->oDb->query($sql,$iTalkId);
	}
	/**
	 * Обновление разговора
	 *
	 * @param ModuleTalk_EntityTalk $oTalk	Объект сообщения
	 * @return int
	 */
	public function UpdateTalk(ModuleTalk_EntityTalk $oTalk) {
		$sql = "UPDATE ".Config::Get('db.table.talk')." SET			
				talk_date_last = ? ,
				talk_user_id_last = ? ,
				talk_comment_id_last = ? ,
				talk_count_comment = ?
			WHERE 
				talk_id = ?d
		";
		return $this->oDb->query($sql,$oTalk->getDateLast(),$oTalk->getUserIdLast(),$oTalk->getCommentIdLast(),$oTalk->getCountComment(),$oTalk->getId());
	}
	/**
	 * Получить список разговоров по списку айдишников
	 *
	 * @param array $aArrayId	Список ID сообщений
	 * @return array
	 */
	public function GetTalksByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT 
					t.*							 
				FROM 
					".Config::Get('db.table.talk')." as t 
				WHERE 
					t.talk_id IN(?a) 									
				ORDER BY FIELD(t.talk_id,?a) ";
		$aTalks=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aTalks[]=Engine::GetEntity('Talk',$aRow);
			}
		}
		return $aTalks;
	}
	/**
	 * Получить список отношений разговор-юзер по списку айдишников
	 *
	 * @param array $aArrayId	Список ID сообщений
	 * @param int $sUserId	ID пользователя
	 * @return array
	 */
	public function GetTalkUserByArray($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT 
					t.*							 
				FROM 
					".Config::Get('db.table.talk_user')." as t 
				WHERE 
					t.talk_id IN(?a)
					AND
					t.user_id = ?d 								
				";
		$aTalkUsers=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aTalkUsers[]=Engine::GetEntity('Talk_TalkUser',$aRow);
			}
		}
		return $aTalkUsers;
	}
	/**
	 * Получает тему разговора по айдишнику
	 *
	 * @param int $sId	ID сообщения
	 * @return ModuleTalk_EntityTalk|null
	 */
	public function GetTalkById($sId) {

		$sql = "SELECT 
				t.*,
				u.user_login as user_login							 
				FROM 
					".Config::Get('db.table.talk')." as t,
					".Config::Get('db.table.user')." as u
				WHERE 
					t.talk_id = ?d 					
					AND
					t.user_id=u.user_id					
					";

		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return Engine::GetEntity('Talk',$aRow);
		}
		return null;
	}
	/**
	 * Добавляет юзера к разговору(теме)
	 *
	 * @param ModuleTalk_EntityTalkUser $oTalkUser	Объект связи пользователя и сообщения(разговора)
	 * @return bool
	 */
	public function AddTalkUser(ModuleTalk_EntityTalkUser $oTalkUser) {
		$sql = "INSERT INTO ".Config::Get('db.table.talk_user')." 
			(talk_id,
			user_id,
			date_last,
			talk_user_active		
			)
			VALUES(?d,  ?d, ?, ?d)
			ON DUPLICATE KEY 
				UPDATE talk_user_active = ?d 
		";
		if ($this->oDb->query($sql,
							  $oTalkUser->getTalkId(),
							  $oTalkUser->getUserId(),
							  $oTalkUser->getDateLast(),
							  $oTalkUser->getUserActive(),
							  $oTalkUser->getUserActive()
		)===0) {
			return true;
		}
		return false;
	}
	/**
	 * Обновляет связку разговор-юзер
	 *
	 * @param ModuleTalk_EntityTalkUser $oTalkUser	Объект связи пользователя с разговором
	 * @return bool
	 */
	public function UpdateTalkUser(ModuleTalk_EntityTalkUser $oTalkUser) {
		$sql = "UPDATE ".Config::Get('db.table.talk_user')." 
			SET 
				date_last = ?, 				
				comment_id_last = ?d, 				
				comment_count_new = ?d, 	
				talk_user_active = ?d			
			WHERE
				talk_id = ?d
				AND
				user_id = ?d
		";

		if (
			$this->oDb->query(
				$sql,
				$oTalkUser->getDateLast(),
				$oTalkUser->getCommentIdLast(),
				$oTalkUser->getCommentCountNew(),
				$oTalkUser->getUserActive(),
				$oTalkUser->getTalkId(),
				$oTalkUser->getUserId()
			)
		) {
			return true;
		}
		return false;
	}
	/**
	 * Удаляет юзера из разговора
	 *
	 * @param array $aTalkId	Список ID сообщений
	 * @param int $sUserId	ID пользователя
	 * @param int $iActive	Статус связи
	 * @return bool
	 */
	public function DeleteTalkUserByArray($aTalkId,$sUserId,$iActive) {
		if (!is_array($aTalkId)) {
			$aTalkId=array($aTalkId);
		}
		$sql = "
			UPDATE ".Config::Get('db.table.talk_user')." 
			SET 
				talk_user_active = ?d
			WHERE
				talk_id IN (?a)
				AND
				user_id = ?d				
		";
		if ($this->oDb->query($sql,$iActive,$aTalkId,$sUserId))
		{
			return true;
		}
		return false;
	}
	/**
	 * Возвращает количество новых комментариев
	 *
	 * @param $sUserId
	 * @return bool
	 */
	public function GetCountCommentNew($sUserId) {
		$sql = "
					SELECT
						SUM(tu.comment_count_new) as count_new												
					FROM   						
  						".Config::Get('db.table.talk_user')." as tu
					WHERE   						
  						tu.user_id = ?d  
  						AND
  						tu.talk_user_active=?d							
		";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId, ModuleTalk::TALK_USER_ACTIVE)) {
			return $aRow['count_new'];
		}
		return false;
	}
	/**
	 * Получает число новых тем и комментов где есть юзер
	 *
	 * @param int $sUserId	ID пользователя
	 * @return int
	 */
	public function GetCountTalkNew($sUserId) {
		$sql = "
					SELECT
						COUNT(tu.talk_id) as count_new												
					FROM   						
  						".Config::Get('db.table.talk_user')." as tu
					WHERE
						tu.user_id = ?d 
  						AND
  						tu.date_last IS NULL 
  						AND
  						tu.talk_user_active=?d						
		";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId,ModuleTalk::TALK_USER_ACTIVE)) {
			return $aRow['count_new'];
		}
		return false;
	}
	/**
	 * Получить все темы разговора где есть юзер
	 *
	 * @param  int $sUserId	ID пользователя
	 * @param  int $iCount	Возвращает общее количество элементов
	 * @param  int    $iCurrPage	Номер страницы
	 * @param  int    $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetTalksByUserId($sUserId,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 
					tu.talk_id									
				FROM 
					".Config::Get('db.table.talk_user')." as tu, 					
					".Config::Get('db.table.talk')." as t							 
				WHERE 
					tu.user_id = ?d 
					AND
					tu.talk_id=t.talk_id
					AND
					tu.talk_user_active = ?d	
				ORDER BY t.talk_date_last desc, t.talk_date desc
				LIMIT ?d, ?d	
					";

		$aTalks=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sUserId,ModuleTalk::TALK_USER_ACTIVE,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aTalks[]=$aRow['talk_id'];
			}
		}
		return $aTalks;
	}
	/**
	 * Получает список юзеров в теме разговора
	 *
	 * @param  int $sTalkId	ID разговора
	 * @param  array  $aUserActive	Список статусов
	 * @return array
	 */
	public function GetUsersTalk($sTalkId,$aUserActive=array()) {
		$sql = "
			SELECT 
				user_id	 
			FROM 
				".Config::Get('db.table.talk_user')." 	  
			WHERE
				talk_id = ? 
				{ AND talk_user_active IN(?a) }
			";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sTalkId,
			(count($aUserActive) ? $aUserActive : DBSIMPLE_SKIP )
		)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}

		return $aReturn;
	}
	/**
	 * Увеличивает число новых комментов у юзеров
	 *
	 * @param int $sTalkId	ID разговора
	 * @param array $aExcludeId	Список ID пользователей для исключения
	 * @return int
	 */
	public function increaseCountCommentNew($sTalkId,$aExcludeId) {
		if (!is_null($aExcludeId) and !is_array($aExcludeId)) {
			$aExcludeId=array($aExcludeId);
		}

		$sql = "UPDATE 			  
				".Config::Get('db.table.talk_user')."   
				SET comment_count_new=comment_count_new+1 
			WHERE
				talk_id = ? 
				{ AND user_id NOT IN (?a) }";
		return $this->oDb->select($sql,$sTalkId,!is_null($aExcludeId) ? $aExcludeId : DBSIMPLE_SKIP);
	}
	/**
	 * Возвращает массив пользователей, участвующих в разговоре
	 *
	 * @param  int $sTalkId	ID разговора
	 * @return array
	 */
	public function GetTalkUsers($sTalkId) {
		$sql = "
			SELECT 
				t.* 
			FROM 
				".Config::Get('db.table.talk_user')." as t 	  
			WHERE
				talk_id = ? 

			";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sTalkId)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('Talk_TalkUser',$aRow);
			}
		}

		return $aReturn;
	}
	/**
	 * Получить все темы разговора по фильтру
	 *
	 * @param  array  $aFilter	Фильтр
	 * @param  int    $iCount	Возвращает общее количество элементов
	 * @param  int    $iCurrPage	Номер страницы
	 * @param  int    $iPerPage	Количество элементов на страницу
	 * @return array('collection'=>array,'count'=>int)
	 */
	public function GetTalksByFilter($aFilter,&$iCount,$iCurrPage,$iPerPage) {
		if (isset($aFilter['id']) and !is_array($aFilter['id'])) {
			$aFilter['id']=array($aFilter['id']);
		}
		$sql = "SELECT 
					tu.talk_id									
				FROM 
					".Config::Get('db.table.talk_user')." as tu,
					".Config::Get('db.table.talk')." as t,
					".Config::Get('db.table.user')." as u	 
				WHERE 
					tu.talk_id=t.talk_id
					AND tu.talk_user_active = ?d
					AND u.user_id=t.user_id
					{ AND tu.user_id = ?d }
					{ AND tu.talk_id IN (?a) }
					{ AND ( tu.comment_count_new > ?d OR tu.date_last IS NULL ) }
					{ AND t.talk_date <= ? }
					{ AND t.talk_date >= ? }
					{ AND t.talk_title LIKE ? }
					{ AND t.talk_text LIKE ? }
					{ AND u.user_login = ? }
					{ AND t.user_id = ? }
				ORDER BY t.talk_date_last desc, t.talk_date desc
				LIMIT ?d, ?d	
					";

		$aTalks=array();
		if (
			$aRows=$this->oDb->selectPage(
				$iCount,
				$sql,
				ModuleTalk::TALK_USER_ACTIVE,
				(!empty($aFilter['user_id']) ? $aFilter['user_id'] : DBSIMPLE_SKIP),
				((isset($aFilter['id']) and count($aFilter['id'])) ? $aFilter['id'] : DBSIMPLE_SKIP),
				(!empty($aFilter['only_new']) ? 0 : DBSIMPLE_SKIP),
				(!empty($aFilter['date_max']) ? $aFilter['date_max'] : DBSIMPLE_SKIP),
				(!empty($aFilter['date_min']) ? $aFilter['date_min'] : DBSIMPLE_SKIP),
				(!empty($aFilter['keyword']) ? $aFilter['keyword'] : DBSIMPLE_SKIP),
				(!empty($aFilter['text_like']) ? $aFilter['text_like'] : DBSIMPLE_SKIP),
				(!empty($aFilter['user_login']) ? $aFilter['user_login'] : DBSIMPLE_SKIP),
				(!empty($aFilter['sender_id']) ? $aFilter['sender_id'] : DBSIMPLE_SKIP),
				($iCurrPage-1)*$iPerPage,
				$iPerPage
			)
		) {
			foreach ($aRows as $aRow) {
				$aTalks[]=$aRow['talk_id'];
			}
		}
		return $aTalks;
	}
	/**
	 * Получает информацию о пользователях, занесенных в блеклист
	 *
	 * @param  int $sUserId	ID пользователя
	 * @return array
	 */
	public function GetBlacklistByUserId($sUserId) {
		$sql = "SELECT 
					tb.user_target_id							 
				FROM 
					".Config::Get('db.table.talk_blacklist')." as tb 
				WHERE 
					tb.user_id = ?d";
		$aTargetId=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aTargetId[]=$aRow['user_target_id'];
			}
		}
		return $aTargetId;
	}
	/**
	 * Возвращает пользователей, у которых данный занесен в Blacklist
	 *
	 * @param  int $sUserId ID пользователя
	 * @return array
	 */
	public function GetBlacklistByTargetId($sUserId) {
		$sql = "SELECT 
					tb.user_id							 
				FROM 
					".Config::Get('db.table.talk_blacklist')." as tb 
				WHERE 
					tb.user_target_id = ?d";
		$aUserId=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aUserId[]=$aRow['user_id'];
			}
		}
		return $aUserId;
	}
	/**
	 * Добавление пользователя в блеклист по переданному идентификатору
	 *
	 * @param  int $sTargetId	ID пользователя, которого добавляем в блэклист
	 * @param  int $sUserId	ID пользователя
	 * @return bool
	 */
	public function AddUserToBlacklist($sTargetId, $sUserId) {
		$sql = "
			INSERT INTO ".Config::Get('db.table.talk_blacklist')." 
				( user_id, user_target_id )
			VALUES
				(?d, ?d)
		";
		if ($this->oDb->query($sql,$sUserId,$sTargetId)===0) {
			return true;
		}
		return false;
	}
	/**
	 * Удаляем пользователя из блеклиста
	 *
	 * @param  int $sTargetId	ID пользователя, которого удаляем из блэклиста
	 * @param  int $sUserId	ID пользователя
	 * @return bool
	 */
	public function DeleteUserFromBlacklist($sTargetId, $sUserId) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.talk_blacklist')." 
			WHERE
				user_id = ?d
			AND
				user_target_id = ?d
		";
		if ($this->oDb->query($sql,$sUserId,$sTargetId)) {
			return true;
		}
		return false;
	}
	/**
	 * Добавление пользователя в блеклист по списку идентификаторов
	 *
	 * @param  array $aTargetId	Список ID пользователей, которых добавляем в блэклист
	 * @param  int $sUserId	ID пользователя
	 * @return bool
	 */
	public function AddUserArrayToBlacklist($aTargetId, $sUserId) {
		$sql = "
			INSERT INTO ".Config::Get('db.table.talk_blacklist')." 
				( user_id, user_target_id )
			VALUES
				(?d, ?d)
		";
		$bOk=true;
		foreach ($aTargetId as $sTarget) {
			$bOk = $bOk && $this->oDb->query($sql, $sUserId, $sTarget);
		}
		return $bOk;
	}
}
?>
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

class Mapper_Talk extends Mapper {	
	public function AddTalk(TalkEntity_Talk $oTalk) {
		$sql = "INSERT INTO ".DB_TABLE_TALK." 
			(user_id,
			talk_title,
			talk_text,
			talk_date,
			talk_date_last,
			talk_user_ip			
			)
			VALUES(?d,	?,	?,	?,  ?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oTalk->getUserId(),$oTalk->getTitle(),$oTalk->getText(),$oTalk->getDate(),$oTalk->getDateLast(),$oTalk->getUserIp())) 
		{
			return $iId;
		}		
		return false;
	}

	public function UpdateTalk(TalkEntity_Talk $oTalk) {
		$sql = "UPDATE ".DB_TABLE_TALK." SET			
				talk_date_last = ?
			WHERE 
				talk_id = ?d
		";			
		return $this->oDb->query($sql,$oTalk->getDateLast(),$oTalk->getId());
	}
	
	public function SetTalkUserDateLast($sTalkId,$sUserId) {
		$sDate=date("Y-m-d H:i:s");
		$sql = "UPDATE ".DB_TABLE_TALK_USER." 
			SET 
				date_last= ? 				
			WHERE
				talk_id = ?d
				AND
				user_id = ?d
		";			
		if ($this->oDb->query($sql,$sDate,$sTalkId,$sUserId)) {
			return true;
		}		
		return false;
	}
	
	public function GetTalkById($sId) {		
		$sql = "SELECT 
				t.*,
				u.user_login as user_login							 
				FROM 
					".DB_TABLE_TALK." as t,
					".DB_TABLE_USER." as u
				WHERE 
					t.talk_id = ?d 					
					AND
					t.user_id=u.user_id					
					";
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return new TalkEntity_Talk($aRow);
		}
		return null;
	}
		
	public function GetTalkByIdAndUserId($sTalkId,$sUserId) {		
		$sql = "SELECT 
				t.*,
				u.user_login as user_login,
				tc.count as count_comment,
				tu.date_last as date_last_read											 
				FROM 
					".DB_TABLE_TALK_USER." as tu,
					".DB_TABLE_USER." as u,
					".DB_TABLE_TALK." as t					
				LEFT JOIN (
					SELECT
						COUNT(talk_comment_id) as count,
						talk_id						
					FROM ".DB_TABLE_TALK_COMMENT." GROUP BY talk_id
					) AS tc ON tc.talk_id = t.talk_id
				WHERE 					
					tu.talk_id = ?d 					
					AND
					tu.user_id = ?d 					
					AND
					t.user_id=u.user_id	
					AND
					tu.talk_id=t.talk_id					
					";
		if ($aRow=$this->oDb->selectRow($sql,$sTalkId,$sUserId)) {
			return new TalkEntity_Talk($aRow);
		}
		return null;
	}
	
	public function AddTalkUser(TalkEntity_TalkUser $oTalkUser) {
		$sql = "INSERT INTO ".DB_TABLE_TALK_USER." 
			(talk_id,
			user_id,
			date_last		
			)
			VALUES(?d,  ?d, ?)
		";			
		if ($this->oDb->query($sql,$oTalkUser->getTalkId(),$oTalkUser->getUserId(),$oTalkUser->getDateLast())===0) 
		{
			return true;
		}		
		return false;
	}
	
	public function DeleteTalkUser(TalkEntity_TalkUser $oTalkUser) {
		$sql = "DELETE FROM ".DB_TABLE_TALK_USER." 
			WHERE
				talk_id = ?d
				AND
				user_id = ?d				
		";			
		if ($this->oDb->query($sql,$oTalkUser->getTalkId(),$oTalkUser->getUserId())) 
		{
			return true;
		}		
		return false;
	}
	
	public function GetTalkUser($sTalkId,$sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_TALK_USER." WHERE talk_id = ?d and user_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sTalkId,$sUserId)) {
			return new TalkEntity_TalkUser($aRow);
		}
		return null;
	}
	
	public function GetCountCommentNew($sUserId) {
		$sql = "
					SELECT
						COUNT(tc.talk_comment_id) as count_new												
					FROM 
  						".DB_TABLE_TALK_COMMENT." as tc,
  						".DB_TABLE_TALK_USER." as tu
					WHERE
  						(tc.talk_comment_date>tu.date_last or tu.date_last IS NULL)
  						AND
  						tu.user_id = ?d
  						AND
  						tu.talk_id=tc.talk_id		
		";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId)) {
			return $aRow['count_new'];
		}
		return false;
	}
	
	public function GetCountTalkNew($sUserId) {
		$sql = "
					SELECT
						COUNT(tu.talk_id) as count_new												
					FROM   						
  						".DB_TABLE_TALK_USER." as tu
					WHERE
  						tu.date_last IS NULL
  						AND
  						tu.user_id = ?d  							
		";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId)) {
			return $aRow['count_new'];
		}
		return false;
	}
	
	public function GetTalksByUserId($sUserId) {				
		$sql = "SELECT 
					t.*,
					u.user_login as user_login,
					tc.count as count_comment,
					tc_new.count_new as count_comment_new,
					tu.date_last as date_last_read									
				FROM 
					".DB_TABLE_TALK_USER." as tu,					
					".DB_TABLE_USER." as u,
					".DB_TABLE_TALK." as t
				LEFT JOIN (
					SELECT
						COUNT(talk_comment_id) as count,
						talk_id						
					FROM ".DB_TABLE_TALK_COMMENT." GROUP BY talk_id
					) AS tc ON tc.talk_id = t.talk_id	
				LEFT JOIN (
					SELECT
						COUNT(tc.talk_comment_id) as count_new,
						tc.talk_id						
					FROM 
  						".DB_TABLE_TALK_COMMENT." as tc,
  						".DB_TABLE_TALK_USER." as tu
					WHERE
  						(tc.talk_comment_date>tu.date_last or tu.date_last IS NULL)
  						AND
  						tu.talk_id=tc.talk_id
  						AND
  						tu.user_id = ?d  						
						GROUP BY tc.talk_id
					) AS tc_new ON tc_new.talk_id = t.talk_id		 
				WHERE 
					tu.talk_id=t.talk_id										
					AND	
					tu.user_id = ?d 								
					AND							
					t.user_id=u.user_id					
				ORDER BY t.talk_date_last desc, t.talk_date desc;	
					";
		$aTalks=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$sUserId)) {
			foreach ($aRows as $aTalk) {
				$aTalks[]=new TalkEntity_Talk($aTalk);
			}
		}
		return $aTalks;
	}	

	public function AddComment(TalkEntity_TalkComment $oComment) {
		$sql = "INSERT INTO ".DB_TABLE_TALK_COMMENT." 
			(talk_comment_pid,
			talk_id,
			user_id,
			talk_comment_date,
			talk_comment_user_ip,
			talk_comment_text		
			)
			VALUES(?,  ?d,	?d,	?,	?,	?)
		";			
		if ($iId=$this->oDb->query($sql,$oComment->getPid(),$oComment->getTalkId(),$oComment->getUserId(),$oComment->getDate(),$oComment->getUserIp(),$oComment->getText())) 
		{
			return $iId;
		}		
		return false;
	}
	
	public function GetCommentsByTalkId($sId) {
		$sql = "SELECT 
					c.*,
					u.user_login as user_login,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					c.talk_comment_id as ARRAY_KEY,
					c.talk_comment_pid as PARENT_KEY
				FROM 
					".DB_TABLE_TALK_COMMENT." as c,
					".DB_TABLE_USER." as u 
				WHERE 
					c.talk_id = ?d 
					AND
					c.user_id=u.user_id
				ORDER by c.talk_comment_id asc;	
					";
		if ($aRows=$this->oDb->select($sql,$sId)) {
			return $aRows;
		}
		return null;
	}
	
	public function GetCommentById($sId) {
		$sql = "SELECT * FROM ".DB_TABLE_TALK_COMMENT." WHERE talk_comment_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return new TalkEntity_TalkComment($aRow);
		}
		return null;
	}
	
	public function GetTalkUsers($sTalkId) {
		$sql = "SELECT 
			u.*		 
			FROM 
				".DB_TABLE_TALK_USER." as tu,
				".DB_TABLE_USER." as u	  
			WHERE
				tu.talk_id = ?
				AND
				tu.user_id = u.user_id
				AND
				u.user_activate = 1			
			ORDER BY 
				u.user_login ASC						
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sTalkId)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new UserEntity_User($aRow);
			}
		}
		return $aReturn;
	}
}
?>
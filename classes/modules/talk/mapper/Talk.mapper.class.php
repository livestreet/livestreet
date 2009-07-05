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
				talk_date_last = ? ,
				talk_count_comment = ? 
			WHERE 
				talk_id = ?d
		";			
		return $this->oDb->query($sql,$oTalk->getDateLast(),$oTalk->getCountComment(),$oTalk->getId());
	}
	
	public function GetTalksByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					t.*							 
				FROM 
					".DB_TABLE_TALK." as t 
				WHERE 
					t.talk_id IN(?a) 									
				ORDER BY FIELD(t.talk_id,?a) ";
		$aTalks=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aTalks[]=new TalkEntity_Talk($aRow);
			}
		}		
		return $aTalks;
	}
	
	public function GetTalkUserByArray($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					t.*							 
				FROM 
					".DB_TABLE_TALK_USER." as t 
				WHERE 
					t.user_id = ?d 
					AND
					t.talk_id IN(?a) 									
				";
		$aTalkUsers=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aTalkUsers[]=new TalkEntity_TalkUser($aRow);
			}
		}
		return $aTalkUsers;
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
	
	public function UpdateTalkUser(TalkEntity_TalkUser $oTalkUser) {
		$sql = "UPDATE ".DB_TABLE_TALK_USER." 
			SET 
				date_last = ?, 				
				comment_id_last = ?d, 				
				comment_count_new = ?d 				
			WHERE
				talk_id = ?d
				AND
				user_id = ?d
		";			
		if ($this->oDb->query($sql,$oTalkUser->getDateLast(),$oTalkUser->getCommentIdLast(),$oTalkUser->getCommentCountNew(),$oTalkUser->getTalkId(),$oTalkUser->getUserId())) {
			return true;
		}		
		return false;
	}
	
	
	public function DeleteTalkUserByArray($aTalkId,$sUserId) {
		if (!is_array($aTalkId)) {
			$aTalkId=array($aTalkId);
		}
		
		$sql = "DELETE FROM ".DB_TABLE_TALK_USER." 
			WHERE
				talk_id IN (?a)
				AND
				user_id = ?d				
		";			
		if ($this->oDb->query($sql,$aTalkId,$sUserId)) 
		{
			return true;
		}		
		return false;
	}
	
		
		
	public function GetCountCommentNew($sUserId) {
		$sql = "
					SELECT
						SUM(tu.comment_count_new) as count_new												
					FROM   						
  						".DB_TABLE_TALK_USER." as tu
					WHERE   						
  						tu.user_id = ?d  							
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
					tu.talk_id									
				FROM 
					".DB_TABLE_TALK_USER." as tu, 					
					".DB_TABLE_TALK." as t							 
				WHERE 
					tu.user_id = ?d 
					AND
					tu.talk_id=t.talk_id	
				ORDER BY t.talk_date_last desc, t.talk_date desc;	
					";
		$aTalks=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aTalks[]=$aRow['talk_id'];
			}
		}
		return $aTalks;
	}	

		
	public function GetUsersTalk($sTalkId) {
		$sql = "SELECT 
			user_id		 
			FROM 
				".DB_TABLE_TALK_USER." 	  
			WHERE
				talk_id = ? ";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sTalkId)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['user_id'];
			}
		}
		return $aReturn;
	}
	
	public function increaseCountCommentNew($sTalkId,$aExcludeId) {
		if (!is_null($aExcludeId) and !is_array($aExcludeId)) {
			$aExcludeId=array($aExcludeId);
		}
		
		$sql = "UPDATE 			  
				".DB_TABLE_TALK_USER."   
				SET comment_count_new=comment_count_new+1 
			WHERE
				talk_id = ? 
				{ AND user_id NOT IN (?a) }";	
		return $this->oDb->select($sql,$sTalkId,!is_null($aExcludeId) ? $aExcludeId : DBSIMPLE_SKIP);
	}
}
?>
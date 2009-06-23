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

class Mapper_Comment extends Mapper {	
	
		public function GetCommentsRatingByDate($sDate,$sTargetType,$iLimit) {
			$sql = "SELECT 
					comment_id				
				FROM 
					".DB_TABLE_COMMENT." 
				WHERE 
					target_type = ? 					 
					AND
					comment_delete = 0
					AND 
					comment_publish = 1 
					AND 
					comment_date >= ? 
					AND 
					comment_rating >= 0
				ORDER by comment_rating desc, comment_id desc
				LIMIT 0, ?d ";	
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$sTargetType,$sDate,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
		
	public function GetCommentUnique($sTargetId,$sTargetType,$sUserId,$sCommentPid,$sHash) {
		$sql = "SELECT comment_id FROM ".DB_TABLE_COMMENT." 
			WHERE 
				target_id = ?d 
				AND
				target_type = ? 
				AND
				user_id = ?d
				AND
				((comment_pid = ?) or (? is NULL and comment_pid is NULL))
				AND
				comment_text_hash =?
				";
		if ($aRow=$this->oDb->selectRow($sql,$sTargetId,$sTargetType,$sUserId,$sCommentPid,$sCommentPid,$sHash)) {
			return $aRow['comment_id'];
		}
		return null;
	}
	
	public function GetCommentsAll($sTargetType,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 					
					comment_id 				
				FROM 
					".DB_TABLE_COMMENT." 
				WHERE 								
					target_type = ?
					AND
					comment_delete = 0
					AND
					comment_publish = 1
				ORDER by comment_id desc
				LIMIT ?d, ?d ";			
		$aComments=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sTargetType,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}		
		}
		return $aComments;
	}
	
		
	public function GetCommentsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 					
					*				
				FROM 
					".DB_TABLE_COMMENT." 
				WHERE 	
					comment_id IN(?a) 					
				ORDER by FIELD(comment_id,?a)";
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aComments[]=new CommentEntity_Comment($aRow);
			}			
		}
		return $aComments;
	}
	
		
	public function GetCommentsOnline($sTargetType,$iLimit) {		
		$sql = "SELECT 					
					comment_id	
				FROM 
					".DB_TABLE_COMMENT_ONLINE." 
				WHERE 												
					target_type = ?										
				ORDER by comment_online_id desc limit 0, ?d ; "; 		
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$sTargetType,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
	
	public function GetCommentsByTargetId($sId,$sTargetType) {		
		$sql = "SELECT 
					comment_id,					
					comment_id as ARRAY_KEY,
					comment_pid as PARENT_KEY
				FROM 
					".DB_TABLE_COMMENT."				
				WHERE 
					target_id = ?d 
					AND			
					target_type = ? 				
				ORDER by comment_id asc;	
					";
		if ($aRows=$this->oDb->select($sql,$sId,$sTargetType)) {
			return $aRows;
		}
		return null;
	}
	
	public function GetCommentsNewByTargetId($sId,$sTargetType,$sIdCommentLast) {		
		$sql = "SELECT 
					comment_id
				FROM 
					".DB_TABLE_COMMENT." 									
				WHERE 
					target_id = ?d 
					AND			
					target_type = ?
					AND			
					comment_id > ?d 					
				ORDER by c.comment_id asc;	
					";
		if ($aRows=$this->oDb->select($sql,$sId,$sTargetType,$sIdCommentLast)) {
			return $aRows['comment_id'];
		}
		return array();
	}
	
	public function GetCommentsByUserId($sId,$sTargetType,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 
					comment_id 					
				FROM 
					".DB_TABLE_COMMENT." 
				WHERE 
					user_id = ?d 
					AND
					target_type= ? 
					AND
					comment_delete = 0
					AND
					comment_publish = 1 					
				ORDER by comment_id desc
				LIMIT ?d, ?d ";		
		$aComments=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sId,$sTargetType,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
	
	public function GetCountCommentsByUserId($sId,$sTargetType) {
		$sql = "SELECT 
					count(comment_id) as count					
				FROM 
					".DB_TABLE_COMMENT." 
				WHERE 
					user_id = ?d 
					AND
					target_type= ? 
					AND
					comment_delete = 0
					AND
					comment_publish = 1	
					";		
		if ($aRow=$this->oDb->selectRow($sql,$sId,$sTargetType)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function AddComment(CommentEntity_Comment $oComment) {
		$sql = "INSERT INTO ".DB_TABLE_COMMENT." 
			(comment_pid,
			target_id,
			target_type,
			user_id,
			comment_text,
			comment_date,
			comment_user_ip,
			comment_text_hash	
			)
			VALUES(?,  ?d, ?, ?d,	?,	?,	?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oComment->getPid(),$oComment->getTargetId(),$oComment->getTargetType(),$oComment->getUserId(),$oComment->getText(),$oComment->getDate(),$oComment->getUserIp(),$oComment->getTextHash())) 
		{
			return $iId;
		}		
		return false;
	}
	
	
	
	public function AddCommentOnline(CommentEntity_CommentOnline $oCommentOnline) {
		$sql = "REPLACE INTO ".DB_TABLE_COMMENT_ONLINE." 
			SET 
				target_id= ?d ,			
				target_type= ? ,			
				comment_id= ?d				
		";			
		if ($iId=$this->oDb->query($sql,$oCommentOnline->getTargetId(),$oCommentOnline->getTargetType(),$oCommentOnline->getCommentId())) 
		{
			return $iId;
		}		
		return false;
	}
	
	public function DeleteCommentOnlineByTargetId($sTargetId,$sTargetType) {
		$sql = "DELETE FROM ".DB_TABLE_COMMENT_ONLINE." WHERE target_id = ?d and target_type = ? ";			
		if ($this->oDb->query($sql,$sTargetId,$sTargetType)) 
		{
			return true;
		}		
		return false;
	}
	
	
	
	public function UpdateComment(CommentEntity_Comment $oComment) {		
		$sql = "UPDATE ".DB_TABLE_COMMENT." 
			SET 
				comment_text= ?,
				comment_rating= ?f,
				comment_count_vote= ?d,
				comment_delete = ?d ,
				comment_publish = ?d ,
				comment_text_hash = ?
			WHERE
				comment_id = ?d
		";			
		if ($this->oDb->query($sql,$oComment->getText(),$oComment->getRating(),$oComment->getCountVote(),$oComment->getDelete(),$oComment->getPublish(),$oComment->getTextHash(),$oComment->getId())) {
			return true;
		}		
		return false;
	}
	
	public function SetCommentsPublish($sTargetId,$sTargetType,$iPublish) {		
		$sql = "UPDATE ".DB_TABLE_COMMENT." 
			SET 
				comment_publish= ? 				
			WHERE
				target_id = ?d AND target_type = ? 
		";			
		if ($this->oDb->query($sql,$iPublish,$sTargetId,$sTargetType)) {
			return true;
		}		
		return false;
	}
}
?>
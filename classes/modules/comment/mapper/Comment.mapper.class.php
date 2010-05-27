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

class ModuleComment_MapperComment extends Mapper {	
	
		public function GetCommentsRatingByDate($sDate,$sTargetType,$iLimit,$aExcludeTarget=array(),$aExcludeParentTarget=array()) {
			$sql = "SELECT 
					comment_id				
				FROM 
					".Config::Get('db.table.comment')." 
				WHERE 
					target_type = ? 	
					AND 
					comment_date >= ?	
					AND 
					comment_rating >= 0			 
					AND
					comment_delete = 0
					AND 
					comment_publish = 1 
					{ AND target_id NOT IN(?a) }  
					{ AND target_parent_id NOT IN (?a) }
				ORDER by comment_rating desc, comment_id desc
				LIMIT 0, ?d ";	
		$aComments=array();		
		if ($aRows=$this->oDb->select(
				$sql,$sTargetType, $sDate,
				(is_array($aExcludeTarget)&&count($aExcludeTarget)) ? $aExcludeTarget : DBSIMPLE_SKIP,
				(count($aExcludeParentTarget) ? $aExcludeParentTarget : DBSIMPLE_SKIP),
				$iLimit
			)
		) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
		
	public function GetCommentUnique($sTargetId,$sTargetType,$sUserId,$sCommentPid,$sHash) {
		$sql = "SELECT comment_id FROM ".Config::Get('db.table.comment')." 
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
	
	public function GetCommentsAll($sTargetType,&$iCount,$iCurrPage,$iPerPage,$aExcludeTarget=array(),$aExcludeParentTarget=array()) {
		$sql = "SELECT 					
					comment_id 				
				FROM 
					".Config::Get('db.table.comment')." 
				WHERE 								
					target_type = ?
					AND
					comment_delete = 0
					AND
					comment_publish = 1
					{ AND target_id NOT IN(?a) }
					{ AND target_parent_id NOT IN(?a) }
				ORDER by comment_id desc
				LIMIT ?d, ?d ";			
		$aComments=array();
		if ($aRows=$this->oDb->selectPage(
				$iCount,$sql,$sTargetType,
				(count($aExcludeTarget)?$aExcludeTarget:DBSIMPLE_SKIP),
				(count($aExcludeParentTarget)?$aExcludeParentTarget:DBSIMPLE_SKIP),
				($iCurrPage-1)*$iPerPage, $iPerPage
			)
		) {
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
					".Config::Get('db.table.comment')." 
				WHERE 	
					comment_id IN(?a) 					
				ORDER by FIELD(comment_id,?a)";
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aComments[]=Engine::GetEntity('Comment',$aRow);
			}			
		}
		return $aComments;
	}
	
		
	public function GetCommentsOnline($sTargetType,$aExcludeTargets,$iLimit) {		
		$sql = "SELECT 					
					comment_id	
				FROM 
					".Config::Get('db.table.comment_online')." 
				WHERE 												
					target_type = ?
				{ AND target_parent_id NOT IN(?a) }
				ORDER by comment_online_id desc limit 0, ?d ; ";
		
		$aComments=array();
		if ($aRows=$this->oDb->select(
				$sql,$sTargetType,
				(count($aExcludeTargets)?$aExcludeTargets:DBSIMPLE_SKIP),
				$iLimit
			)
		) {
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
					".Config::Get('db.table.comment')."
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
					".Config::Get('db.table.comment')." 									
				WHERE 
					target_id = ?d 
					AND			
					target_type = ?
					AND			
					comment_id > ?d 					
				ORDER by comment_id asc;	
					";
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$sId,$sTargetType,$sIdCommentLast)) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
	
	public function GetCommentsByUserId($sId,$sTargetType,&$iCount,$iCurrPage,$iPerPage,$aExcludeTarget=array(),$aExcludeParentTarget=array()) {
		$sql = "SELECT 
					comment_id 					
				FROM 
					".Config::Get('db.table.comment')." 
				WHERE 
					user_id = ?d 
					AND
					target_type= ? 
					AND
					comment_delete = 0
					AND
					comment_publish = 1 
					{ AND target_id NOT IN (?a) }					
					{ AND target_parent_id NOT IN (?a) }					
				ORDER by comment_id desc
				LIMIT ?d, ?d ";		
		$aComments=array();
		if ($aRows=$this->oDb->selectPage(
				$iCount,$sql,$sId,
				$sTargetType,
				(count($aExcludeTarget) ? $aExcludeTarget : DBSIMPLE_SKIP),
				(count($aExcludeParentTarget) ? $aExcludeParentTarget : DBSIMPLE_SKIP),
				($iCurrPage-1)*$iPerPage, $iPerPage
			)
		) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
	
	public function GetCountCommentsByUserId($sId,$sTargetType,$aExcludeTarget=array(),$aExcludeParentTarget=array()) {
		$sql = "SELECT 
					count(comment_id) as count					
				FROM 
					".Config::Get('db.table.comment')." 
				WHERE 
					user_id = ?d 
					AND
					target_type= ? 
					AND
					comment_delete = 0
					AND
					comment_publish = 1	
					{ AND target_id NOT IN (?a) }					
					{ AND target_parent_id NOT IN (?a) }					
					";		
		if ($aRow=$this->oDb->selectRow(
				$sql,$sId,$sTargetType,
				(count($aExcludeTarget) ? $aExcludeTarget : DBSIMPLE_SKIP),
				(count($aExcludeParentTarget) ? $aExcludeParentTarget : DBSIMPLE_SKIP)
			)
		) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function AddComment(ModuleComment_EntityComment $oComment) {
		$sql = "INSERT INTO ".Config::Get('db.table.comment')." 
			(comment_pid,
			target_id,
			target_type,
			target_parent_id,
			user_id,
			comment_text,
			comment_date,
			comment_user_ip,
			comment_text_hash	
			)
			VALUES(?, ?d, ?, ?d, ?d, ?, ?, ?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oComment->getPid(),$oComment->getTargetId(),$oComment->getTargetType(),$oComment->getTargetParentId(),$oComment->getUserId(),$oComment->getText(),$oComment->getDate(),$oComment->getUserIp(),$oComment->getTextHash())) 
		{
			return $iId;
		}
		return false;
	}
	
	
	
	public function AddCommentOnline(ModuleComment_EntityCommentOnline $oCommentOnline) {
		$sql = "REPLACE INTO ".Config::Get('db.table.comment_online')." 
			SET 
				target_id= ?d ,			
				target_type= ? ,
				target_parent_id = ?d,
				comment_id= ?d				
		";			
		if ($iId=$this->oDb->query($sql,$oCommentOnline->getTargetId(),$oCommentOnline->getTargetType(),$oCommentOnline->getTargetParentId(),$oCommentOnline->getCommentId())) 
		{
			return $iId;
		}		
		return false;
	}
	
	public function DeleteCommentOnlineByTargetId($sTargetId,$sTargetType) {
		$sql = "DELETE FROM ".Config::Get('db.table.comment_online')." WHERE target_id = ?d and target_type = ? ";			
		if ($this->oDb->query($sql,$sTargetId,$sTargetType)) 
		{
			return true;
		}		
		return false;
	}
	
	
	
	public function UpdateComment(ModuleComment_EntityComment $oComment) {		
		$sql = "UPDATE ".Config::Get('db.table.comment')." 
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
		$sql = "UPDATE ".Config::Get('db.table.comment')." 
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
	
	public function DeleteCommentByTargetId($aTargetId,$sTargetType) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.comment')." 
			WHERE
				target_id IN (?a)
				AND
				target_type = ?
		";			
		if ($this->oDb->query($sql,$aTargetId,$sTargetType)) {
			return true;
		}
		return false;
	}
	
	public function DeleteCommentOnlineByArrayId($aCommentId,$sTargetType) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.comment_online')." 
			WHERE 
				comment_id IN (?a) 
				AND 
				target_type = ? 
		";			
		if ($this->oDb->query($sql,$aCommentId,$sTargetType)) {
			return true;
		}		
		return false;
	}
	
	public function UpdateTargetParentByTargetId($sParentId, $sTargetType, $aTargetId) {
		$sql = "
			UPDATE ".Config::Get('db.table.comment')." 
			SET 
				target_parent_id = ?d
			WHERE 
				target_id IN (?a)
				AND 
				target_type = ? 
		";			
		if ($this->oDb->query($sql,$sParentId,$aTargetId,$sTargetType)) {
			return true;
		}
		return false;		
	}
	
	public function UpdateTargetParentByTargetIdOnline($sParentId, $sTargetType, $aTargetId) {
		$sql = "
			UPDATE ".Config::Get('db.table.comment_online')." 
			SET 
				target_parent_id = ?d
			WHERE 
				target_id IN (?a)
				AND 
				target_type = ? 
		";			
		if ($this->oDb->query($sql,$sParentId,$aTargetId,$sTargetType)) {
			return true;
		}
		return false;		
	}	
	
	public function MoveTargetParent($sParentId, $sTargetType, $sParentIdNew) {
		$sql = "
			UPDATE ".Config::Get('db.table.comment')." 
			SET 
				target_parent_id = ?d
			WHERE 
				target_parent_id = ?d
				AND 
				target_type = ? 
		";			
		if ($this->oDb->query($sql,$sParentIdNew,$sParentId,$sTargetType)) {
			return true;
		}
		return false;		
	}
	
	public function MoveTargetParentOnline($sParentId, $sTargetType, $sParentIdNew) {
		$sql = "
			UPDATE ".Config::Get('db.table.comment_online')." 
			SET 
				target_parent_id = ?d
			WHERE 
				target_parent_id = ?d
				AND 
				target_type = ? 
		";			
		if ($this->oDb->query($sql,$sParentIdNew,$sParentId,$sTargetType)) {
			return true;
		}
		return false;		
	}
}
?>
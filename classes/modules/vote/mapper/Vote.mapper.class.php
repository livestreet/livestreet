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

class ModuleVote_MapperVote extends Mapper {	
		
	
	public function AddVote(ModuleVote_EntityVote $oVote) {
		$sql = "INSERT INTO ".Config::Get('db.table.vote')." 
			(target_id,
			target_type,
			user_voter_id,
			vote_direction,
			vote_value,			
			vote_date		
			)
			VALUES(?d, ?, ?d, ?d, ?f, ?)
		";			
		if ($this->oDb->query($sql,$oVote->getTargetId(),$oVote->getTargetType(),$oVote->getVoterId(),$oVote->getDirection(),$oVote->getValue(),$oVote->getDate())===0) 
		{
			return true;
		}		
		return false;
	}
	

	public function GetVoteByArray($aArrayId,$sTargetType,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}				
		$sql = "SELECT 
					*							 
				FROM 
					".Config::Get('db.table.vote')."
				WHERE 					
					target_id IN(?a) 	
					AND
					target_type = ? 
					AND
					user_voter_id = ?d ";
		$aVotes=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sTargetType,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aVotes[]=Engine::GetEntity('Vote',$aRow);
			}
		}		
		return $aVotes;
	}	
	
	public function DeleteVoteByTarget($aTargetId,$sTargetType) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.vote')." 
			WHERE
				target_id IN(?a)
				AND
				target_type = ?				
		";			
		if ($this->oDb->query($sql,$aTargetId,$sTargetType)) {
			return true;
		}
		return false;
	}
}
?>
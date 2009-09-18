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

class Mapper_Favourite extends Mapper {	
		
	public function AddFavourite(FavouriteEntity_Favourite $oFavourite) {
		$sql = "
			INSERT INTO ".Config::Get('db.table.favourite')." 
				( target_id, target_type, user_id )
			VALUES
				(?d, ?, ?d)
		";			
		if ($this->oDb->query(
			$sql,
			$oFavourite->getTargetId(),
			$oFavourite->getTargetType(),
			$oFavourite->getUserId()		
		)===0) {
			return true;
		}		
		return false;
	}
	
	public function GetFavouritesByArray($aArrayId,$sTargetType,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}				
		$sql = "SELECT *							 
				FROM ".Config::Get('db.table.favourite')."
				WHERE 					
					target_id IN(?a) 	
					AND
					target_type = ? 
					AND
					user_id = ?d";
		$aFavourites=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sTargetType,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aFavourites[]=Engine::GetEntity('Favourite',$aRow);
			}
		}		
		return $aFavourites;
	}	
	
	public function DeleteFavourite(FavouriteEntity_Favourite $oFavourite) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.favourite')." 
			WHERE
				user_id = ?d
			AND
				target_id = ?d
			AND 
				target_type = ?				
		";			
		if ($this->oDb->query(
			$sql,
			$oFavourite->getUserId(),
			$oFavourite->getTargetId(),
			$oFavourite->getTargetType()
		)) {
			return true;
		}		
		return false;
	}
	
	public function SetFavouriteTargetPublish($sTargetId,$sTargetType,$iPublish) {
		$sql = "
			UPDATE ".Config::Get('db.table.favourite')." 
			SET 
				target_publish = ?d
			WHERE				
				target_id = ?d
			AND
				target_type = ?				
		";			
		return $this->oDb->query($sql,$iPublish,$sTargetId,$sTargetType); 		
	}	
	
	public function GetFavouritesByUserId($sUserId,$sTargetType,$aExcludeTarget,&$iCount,$iCurrPage,$iPerPage) {	
		$sql = "			
			SELECT target_id										
			FROM ".Config::Get('db.table.favourite')."								
			WHERE 
					user_id = ?
				AND
					target_publish = 1
				AND
					target_type = ? 
				{ AND target_id NOT IN (?a) }		
            ORDER BY target_id DESC	
            LIMIT ?d, ?d ";
		
		$aFavourites=array();		
		if ($aRows=$this->oDb->selectPage(
				$iCount,
				$sql,
				$sUserId,
				$sTargetType,
				(count($aExcludeTarget) ? $aExcludeTarget : DBSIMPLE_SKIP),
				($iCurrPage-1)*$iPerPage, 
				$iPerPage
		)) {
			foreach ($aRows as $aFavourite) {
				$aFavourites[]=$aFavourite['target_id'];
			}			
		}		
		return $aFavourites;
	}
	
	public function GetCountFavouritesByUserId($sUserId,$sTargetType,$aExcludeTarget) {
		$sql = "SELECT 		
					count(target_id) as count									
				FROM 
					".Config::Get('db.table.favourite')."								
				WHERE 
						user_id = ?
					AND
						target_publish = 1
					AND
						target_type = ?
					{ AND target_id NOT IN (?a) }		
					;";				
		return ( $aRow=$this->oDb->selectRow(
						$sql,$sUserId,
						$sTargetType,
						(count($aExcludeTarget) ? $aExcludeTarget : DBSIMPLE_SKIP)
					) 
				)
					? $aRow['count']
					: false;
	}	
}
?>
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
 * Маппер для работы с БД
 *
 * @package modules.vote
 * @since 1.0
 */
class ModuleVote_MapperVote extends Mapper {
	/**
	 * Добавляет голосование
	 *
	 * @param ModuleVote_EntityVote $oVote	Объект голосования
	 * @return bool
	 */
	public function AddVote(ModuleVote_EntityVote $oVote) {
		$sql = "INSERT INTO ".Config::Get('db.table.vote')." 
			(target_id,
			target_type,
			user_voter_id,
			vote_direction,
			vote_value,			
			vote_date,
			vote_ip
			)
			VALUES(?d, ?, ?d, ?d, ?f, ?, ?)
		";
		if ($this->oDb->query($sql,$oVote->getTargetId(),$oVote->getTargetType(),$oVote->getVoterId(),$oVote->getDirection(),$oVote->getValue(),$oVote->getDate(),$oVote->getIp())===0)
		{
			return true;
		}
		return false;
	}
	/**
	 * Получить список голосований по списку айдишников
	 *
	 * @param array $aArrayId	Список ID владельцев
	 * @param string $sTargetType	Тип владельца
	 * @param int $sUserId	ID пользователя
	 * @return array
	 */
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
	/**
	 * Удаляет голосование из базы по списку идентификаторов таргета
	 *
	 * @param  array|int $aTargetId	Список ID владельцев
	 * @param  string    $sTargetType	Тип владельца
	 * @return bool
	 */
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
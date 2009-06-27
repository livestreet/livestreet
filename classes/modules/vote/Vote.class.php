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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once('mapper/Vote.mapper.class.php');

/**
 * Модуль для работы с голосованиями
 *
 */
class LsVote extends Module {		
	protected $oMapper;	
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapper=new Mapper_Vote($this->Database_GetConnect());
	}
	
	/**
	 * Добавляет голосование
	 *
	 * @param VoteEntity_Vote $oVote
	 * @return unknown
	 */
	public function AddVote(VoteEntity_Vote $oVote) {
		if ($this->oMapper->AddVote($oVote)) {
			$this->Cache_Delete("vote_{$oVote->getTargetType()}_{$oVote->getTargetId()}_{$oVote->getVoterId()}");
			return true;
		}
		return false;
	}
	
	/**
	 * Получает голосование
	 *
	 * @param unknown_type $sTargetId
	 * @param unknown_type $sTargetType
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetVote($sTargetId,$sTargetType,$sUserId) {
		$data=$this->GetVoteByArray($sTargetId,$sTargetType,$sUserId);
		if (isset($data[$sTargetId])) {
			return $data[$sTargetId];
		}
		return null;
	}
	
	/**
	 * Получить список голосований по списку айдишников
	 *
	 * @param unknown_type $sTargetId
	 * @param unknown_type $sTargetType
	 */
	public function GetVoteByArray($aTargetId,$sTargetType,$sUserId) {
		if (!is_array($aTargetId)) {
			$aTargetId=array($aTargetId);
		}
		$aTargetId=array_unique($aTargetId);
		$aVote=array();
		$aIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTargetId,"vote_{$sTargetType}_",'_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aVote[$data[$sKey]->getTargetId()]=$data[$sKey];
					} else {
						$aIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */		
		$aIdNeedQuery=array_diff($aTargetId,array_keys($aVote));		
		$aIdNeedQuery=array_diff($aIdNeedQuery,$aIdNotNeedQuery);		
		$aIdNeedStore=$aIdNeedQuery;
		if ($data = $this->oMapper->GetVoteByArray($aIdNeedQuery,$sTargetType,$sUserId)) {
			foreach ($data as $oVote) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aVote[$oVote->getTargetId()]=$oVote;
				$this->Cache_Set($oVote, "vote_{$oVote->getTargetType()}_{$oVote->getTargetId()}_{$oVote->getVoterId()}", array(), 60*60*24*7);
				$aIdNeedStore=array_diff($aIdNeedStore,array($oVote->getTargetId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aIdNeedStore as $sId) {
			$this->Cache_Set(null, "vote_{$sTargetType}_{$sId}_{$sUserId}", array(), 60*60*24*7);
		}		
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aVote=func_array_sort_by_keys($aVote,$aTargetId);
		return $aVote;		
	}
	
}
?>
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
 * Модуль для работы с голосованиями
 *
 * @package modules.vote
 * @since 1.0
 */
class ModuleVote extends Module {
	/**
	 * Объект маппера
	 *
	 * @var ModuleVote_MapperVote
	 */
	protected $oMapper;

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	/**
	 * Добавляет голосование
	 *
	 * @param ModuleVote_EntityVote $oVote	Объект голосования
	 * @return bool
	 */
	public function AddVote(ModuleVote_EntityVote $oVote) {
		if (!$oVote->getIp()) {
			$oVote->setIp(func_getIp());
		}
		if ($this->oMapper->AddVote($oVote)) {
			$this->Cache_Delete("vote_{$oVote->getTargetType()}_{$oVote->getTargetId()}_{$oVote->getVoterId()}");
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("vote_update_{$oVote->getTargetType()}_{$oVote->getVoterId()}"));
			return true;
		}
		return false;
	}
	/**
	 * Получает голосование
	 *
	 * @param int $sTargetId	ID владельца
	 * @param string $sTargetType	Тип владельца
	 * @param int $sUserId	ID пользователя
	 * @return ModuleVote_EntityVote|null
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
	 * @param array $aTargetId	Список ID владельцев
	 * @param string $sTargetType	Тип владельца
	 * @param int $sUserId	ID пользователя
	 * @return array
	 */
	public function GetVoteByArray($aTargetId,$sTargetType,$sUserId) {
		if (!$aTargetId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetVoteByArraySolid($aTargetId,$sTargetType,$sUserId);
		}
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
	/**
	 * Получить список голосований по списку айдишников, но используя единый кеш
	 *
	 * @param array $aTargetId	Список ID владельцев
	 * @param string $sTargetType	Тип владельца
	 * @param int $sUserId	ID пользователя
	 * @return array
	 */
	public function GetVoteByArraySolid($aTargetId,$sTargetType,$sUserId) {
		if (!is_array($aTargetId)) {
			$aTargetId=array($aTargetId);
		}
		$aTargetId=array_unique($aTargetId);
		$aVote=array();
		$s=join(',',$aTargetId);
		if (false === ($data = $this->Cache_Get("vote_{$sTargetType}_{$sUserId}_id_{$s}"))) {
			$data = $this->oMapper->GetVoteByArray($aTargetId,$sTargetType,$sUserId);
			foreach ($data as $oVote) {
				$aVote[$oVote->getTargetId()]=$oVote;
			}
			$this->Cache_Set(
				$aVote, "vote_{$sTargetType}_{$sUserId}_id_{$s}",
				array("vote_update_{$sTargetType}_{$sUserId}","vote_update_{$sTargetType}"),
				60*60*24*1
			);
			return $aVote;
		}
		return $data;
	}
	/**
	 * Удаляет голосование из базы по списку идентификаторов таргета
	 *
	 * @param  array|int $aTargetId	Список ID владельцев
	 * @param  string    $sTargetType	Тип владельца
	 * @return bool
	 */
	public function DeleteVoteByTarget($aTargetId, $sTargetType) {
		if (!is_array($aTargetId)) $aTargetId=array($aTargetId);
		$aTargetId=array_unique($aTargetId);
		/**
		 * Чистим зависимые кеши
		 */
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("vote_update_{$sTargetType}"));
		return $this->oMapper->DeleteVoteByTarget($aTargetId,$sTargetType);
	}
}
?>
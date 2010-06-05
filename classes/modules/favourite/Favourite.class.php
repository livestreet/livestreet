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
 */
class ModuleFavourite extends Module {		
	protected $oMapper;	
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	
	/**
	 * Получает информацию о том, найден ли таргет в избранном или нет
	 *
	 * @param  string $sTargetId
	 * @param  string $sTargetType
	 * @param  string $sUserId
	 * @return ModuleFavourite_EntityFavourite|null
	 */
	public function GetFavourite($sTargetId,$sTargetType,$sUserId) {
		$data=$this->GetFavouritesByArray($sTargetId,$sTargetType,$sUserId);
		return (isset($data[$sTargetId]))
			? $data[$sTargetId]
			: null;
	}
	
	/**
	 * Получить список избранного по списку айдишников
	 *
	 * @param  array  $aTargetId
	 * @param  string $sTargetType
	 * @param  string $sUserId
	 * @return array
	 */
	public function GetFavouritesByArray($aTargetId,$sTargetType,$sUserId) {
		if (!$aTargetId) {
			return array();
		}	
		if (Config::Get('sys.cache.solid')) {
			return $this->GetFavouritesByArraySolid($aTargetId,$sTargetType,$sUserId);
		}
		if (!is_array($aTargetId)) {
			$aTargetId=array($aTargetId);
		}
		$aTargetId=array_unique($aTargetId);
		$aFavourite=array();
		$aIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTargetId,"favourite_{$sTargetType}_",'_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aFavourite[$data[$sKey]->getTargetId()]=$data[$sKey];
					} else {
						$aIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим чего не было в кеше и делаем запрос в БД
		 */		
		$aIdNeedQuery=array_diff($aTargetId,array_keys($aFavourite));		
		$aIdNeedQuery=array_diff($aIdNeedQuery,$aIdNotNeedQuery);		
		$aIdNeedStore=$aIdNeedQuery;
		if ($data = $this->oMapper->GetFavouritesByArray($aIdNeedQuery,$sTargetType,$sUserId)) {
			foreach ($data as $oFavourite) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aFavourite[$oFavourite->getTargetId()]=$oFavourite;
				$this->Cache_Set($oFavourite, "favourite_{$oFavourite->getTargetType()}_{$oFavourite->getTargetId()}_{$sUserId}", array(), 60*60*24*7);
				$aIdNeedStore=array_diff($aIdNeedStore,array($oFavourite->getTargetId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aIdNeedStore as $sId) {
			$this->Cache_Set(null, "favourite_{$sTargetType}_{$sId}_{$sUserId}", array(), 60*60*24*7);
		}		
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aFavourite=func_array_sort_by_keys($aFavourite,$aTargetId);
		return $aFavourite;		
	}
	/**
	 * Получить список избранного по списку айдишников, но используя единый кеш
	 *
	 * @param  array  $aTargetId
	 * @param  string $sTargetType
	 * @param  string $sUserId
	 * @return array
	 */
	public function GetFavouritesByArraySolid($aTargetId,$sTargetType,$sUserId) {
		if (!is_array($aTargetId)) {
			$aTargetId=array($aTargetId);
		}
		$aTargetId=array_unique($aTargetId);	
		$aFavourites=array();	
		$s=join(',',$aTargetId);	
		if (false === ($data = $this->Cache_Get("favourite_{$sTargetType}_{$sUserId}_id_{$s}"))) {			
			$data = $this->oMapper->GetFavouritesByArray($aTargetId,$sTargetType,$sUserId);
			foreach ($data as $oFavourite) {
				$aFavourites[$oFavourite->getTargetId()]=$oFavourite;
			}
			$this->Cache_Set($aFavourites, "favourite_{$sTargetType}_{$sUserId}_id_{$s}", array("favourite_{$sTargetType}_change_user_{$sUserId}"), 60*60*24*1);
			return $aFavourites;
		}		
		return $data;
	}
	
	/**
	 * Получает список таргеов из избранного
	 *
	 * @param  string $sUserId
	 * @param  string $sTargetType
	 * @param  int $iCount
	 * @param  int $iCurrPage
	 * @param  int $iPerPage
	 * @return array
	 */
	public function GetFavouritesByUserId($sUserId,$sTargetType,$iCurrPage,$iPerPage,$aExcludeTarget=array()) {		
		$s=serialize($aExcludeTarget);
		if (false === ($data = $this->Cache_Get("{$sTargetType}_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}_{$s}"))) {			
			$data = array(
				'collection' => $this->oMapper->GetFavouritesByUserId($sUserId,$sTargetType,$iCount,$iCurrPage,$iPerPage,$aExcludeTarget),
				'count'      => $iCount
			);
			$this->Cache_Set(
				$data, 
				"{$sTargetType}_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}_{$s}", 
				array(
					"favourite_{$sTargetType}_change",
					"favourite_{$sTargetType}_change_user_{$sUserId}"
				), 
				60*60*24*1
			);
		}		
		return $data;
	}
	/**
	 * Возвращает число таргетов определенного типа в избранном по ID пользователя
	 *
	 * @param  string $sUserId
	 * @param  string $sTargetType
	 * @return array
	 */
	public function GetCountFavouritesByUserId($sUserId,$sTargetType,$aExcludeTarget=array()) {
		$s=serialize($aExcludeTarget);
		if (false === ($data = $this->Cache_Get("{$sTargetType}_count_favourite_user_{$sUserId}_{$s}"))) {			
			$data = $this->oMapper->GetCountFavouritesByUserId($sUserId,$sTargetType,$aExcludeTarget);
			$this->Cache_Set(
				$data, 
				"{$sTargetType}_count_favourite_user_{$sUserId}_{$s}", 
				array(
					"favourite_{$sTargetType}_change",
					"favourite_{$sTargetType}_change_user_{$sUserId}"
				), 
				60*60*24*1
			);
		}
		return $data;
	}

	/**
	 * Получает список комментариев к записям открытых блогов 
	 * из избранного указанного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int $iCurrPage
	 * @param  int $iPerPage
	 * @return array
	 */
	public function GetFavouriteOpenCommentsByUserId($sUserId,$iCurrPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("comment_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}_open"))) {			
			$data = array(
				'collection' => $this->oMapper->GetFavouriteOpenCommentsByUserId($sUserId,$iCount,$iCurrPage,$iPerPage),
				'count'      => $iCount
			);
			$this->Cache_Set(
				$data, 
				"comment_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}_open", 
				array(
					"favourite_comment_change",
					"favourite_comment_change_user_{$sUserId}"
				), 
				60*60*24*1
			);
		}		
		return $data;
	}	
	/**
	 * Возвращает число комментариев к открытым блогам в избранном по ID пользователя
	 *
	 * @param  string $sUserId
	 * @return array
	 */
	public function GetCountFavouriteOpenCommentsByUserId($sUserId) {
		if (false === ($data = $this->Cache_Get("comment_count_favourite_user_{$sUserId}_open"))) {			
			$data = $this->oMapper->GetCountFavouriteOpenCommentsByUserId($sUserId);
			$this->Cache_Set(
				$data, 
				"comment_count_favourite_user_{$sUserId}_open", 
				array(
					"favourite_comment_change",
					"favourite_comment_change_user_{$sUserId}"
				), 
				60*60*24*1
			);
		}
		return $data;
	}	
	/**
	 * Получает список топиков из открытых блогов 
	 * из избранного указанного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int $iCurrPage
	 * @param  int $iPerPage
	 * @return array
	 */
	public function GetFavouriteOpenTopicsByUserId($sUserId,$iCurrPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("topic_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}_open"))) {			
			$data = array(
				'collection' => $this->oMapper->GetFavouriteOpenTopicsByUserId($sUserId,$iCount,$iCurrPage,$iPerPage),
				'count'      => $iCount
			);
			$this->Cache_Set(
				$data, 
				"topic_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}_open", 
				array(
					"favourite_topic_change",
					"favourite_topic_change_user_{$sUserId}"
				), 
				60*60*24*1
			);
		}		
		return $data;
	}	
	/**
	 * Возвращает число топиков в открытых блогах 
	 * из избранного по ID пользователя
	 *
	 * @param  string $sUserId
	 * @return array
	 */
	public function GetCountFavouriteOpenTopicsByUserId($sUserId) {
		if (false === ($data = $this->Cache_Get("topic_count_favourite_user_{$sUserId}_open"))) {			
			$data = $this->oMapper->GetCountFavouriteOpenTopicsByUserId($sUserId);
			$this->Cache_Set(
				$data, 
				"topic_count_favourite_user_{$sUserId}_open", 
				array(
					"favourite_topic_change",
					"favourite_topic_change_user_{$sUserId}"
				), 
				60*60*24*1
			);
		}
		return $data;
	}		
	/**
	 * Добавляет таргет в избранное
	 *
	 * @param  ModuleFavourite_EntityFavourite $oFavourite
	 * @return bool
	 */
	public function AddFavourite(ModuleFavourite_EntityFavourite $oFavourite) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("favourite_{$oFavourite->getTargetType()}_change_user_{$oFavourite->getUserId()}"));						
		$this->Cache_Delete("favourite_{$oFavourite->getTargetType()}_{$oFavourite->getTargetId()}_{$oFavourite->getUserId()}");						
		return $this->oMapper->AddFavourite($oFavourite);
	}
	/**
	 * Удаляет таргет из избранного
	 *
	 * @param  ModuleFavourite_EntityFavourite $oFavourite
	 * @return bool
	 */
	public function DeleteFavourite(ModuleFavourite_EntityFavourite $oFavourite) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("favourite_{$oFavourite->getTargetType()}_change_user_{$oFavourite->getUserId()}"));
		$this->Cache_Delete("favourite_{$oFavourite->getTargetType()}_{$oFavourite->getTargetId()}_{$oFavourite->getUserId()}");
		return $this->oMapper->DeleteFavourite($oFavourite);
	}
	/**
	 * Меняет параметры публикации у таргета
	 *
	 * @param  string $sTargetId
	 * @param  string $sTargetType 
	 * @param  string $iPublish
	 * @return bool
	 */
	public function SetFavouriteTargetPublish($aTargetId,$sTargetType,$iPublish) {
		if(!is_array($aTargetId)) $aTargetId = array($aTargetId);
		
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("favourite_{$sTargetType}_change"));
		return $this->oMapper->SetFavouriteTargetPublish($aTargetId,$sTargetType,$iPublish);
	}
	/**
	 * Удаляет избранное по списку идентификаторов таргетов
	 *
	 * @param  array|int $aTargetId
	 * @param  string    $sTargetType
	 * @return bool
	 */
	public function DeleteFavouriteByTargetId($aTargetId, $sTargetType) {
		if(!is_array($aTargetId)) $aTargetId = array($aTargetId);
		/**
		 * Чистим зависимые кеши
		 */
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("favourite_{$sTargetType}_change"));
		return $this->oMapper->DeleteFavouriteByTargetId($aTargetId,$sTargetType);		
	}
}
?>
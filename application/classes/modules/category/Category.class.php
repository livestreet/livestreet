<?php
/**
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Модуль управления универсальными категориями
 */
class ModuleCategory extends ModuleORM {
	/**
	 * Список состояний типов объектов
	 */
	const TARGET_STATE_ACTIVE=1;
	const TARGET_STATE_NOT_ACTIVE=2;
	const TARGET_STATE_REMOVE=3;

	protected $oMapper=null;

	/**
	 * Инициализация
	 */
	public function Init() {
		parent::Init();
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	/**
	 * Возвращает список категорий сущности
	 *
	 * @param $oTarget
	 * @param $sTargetType
	 *
	 * @return array
	 */
	public function GetEntityCategories($oTarget,$sTargetType) {
		$aCategories=$oTarget->_getDataOne('_categories');
		if (is_null($aCategories)) {
			$this->AttachCategoriesForTargetItems($oTarget,$sTargetType);
			return $oTarget->_getDataOne('_categories');
		}
		return $aCategories;
	}
	/**
	 * Обработка фильтра ORM запросов
	 *
	 * @param array $aFilter
	 * @param array $sEntityFull
	 * @param string $sTargetType
	 *
	 * @return array
	 */
	public function RewriteFilter($aFilter,$sEntityFull,$sTargetType) {
		$oEntitySample=Engine::GetEntity($sEntityFull);

		if (!isset($aFilter['#join'])) {
			$aFilter['#join']=array();
		}

		if (!isset($aFilter['#select'])) {
			$aFilter['#select']=array();
		}

		if (array_key_exists('#category',$aFilter)) {
			$aCategoryId=$aFilter['#category'];
			if (!is_array($aCategoryId)) {
				$aCategoryId=array($aCategoryId);
			}
			$sJoin="JOIN ".Config::Get('db.table.category_target')." category ON
					t.`{$oEntitySample->_getPrimaryKey()}` = category.target_id and
					category.target_type = '{$sTargetType}' and
					category.category_id IN ( ?a ) ";
			$aFilter['#join'][$sJoin]=array($aCategoryId);
			if (count($aFilter['#select'])) {
				$aFilter['#select'][]="distinct t.`{$oEntitySample->_getPrimaryKey()}`";
			} else {
				$aFilter['#select'][]="distinct t.`{$oEntitySample->_getPrimaryKey()}`";
				$aFilter['#select'][]='t.*';
			}
		}
		return $aFilter;
	}
	/**
	 * Переопределяем метод для возможности цеплять свои кастомные данные при ORM запросах - свойства
	 *
	 * @param array $aResult
	 * @param array $aFilter
	 * @param string  $sTargetType
	 */
	public function RewriteGetItemsByFilter($aResult,$aFilter,$sTargetType) {
		if (!$aResult) {
			return;
		}
		/**
		 * Список на входе может быть двух видов:
		 * 1 - одномерный массив
		 * 2 - двумерный, если применялась группировка (использование '#index-group')
		 *
		 * Поэтому сначала сформируем линейный список
		 */
		if (isset($aFilter['#index-group']) and $aFilter['#index-group']) {
			$aEntitiesWork=array();
			foreach($aResult as $aItems) {
				foreach($aItems as $oItem) {
					$aEntitiesWork[]=$oItem;
				}
			}
		} else {
			$aEntitiesWork=$aResult;
		}

		if (!$aEntitiesWork) {
			return;
		}
		/**
		 * Проверяем необходимость цеплять категории
		 */
		if (isset($aFilter['#with']['#category'])) {
			$this->AttachCategoriesForTargetItems($aEntitiesWork,$sTargetType);
		}
	}
	/**
	 * Цепляет для списка объектов категории
	 *
	 * @param array $aEntityItems
	 * @param string $sTargetType
	 */
	public function AttachCategoriesForTargetItems($aEntityItems,$sTargetType) {
		if (!is_array($aEntityItems)) {
			$aEntityItems=array($aEntityItems);
		}
		$aEntitiesId=array();
		foreach($aEntityItems as $oEntity) {
			$aEntitiesId[]=$oEntity->getId();
		}
		/**
		 * Получаем категории для всех объектов
		 */
		$sEntityCategory=$this->_NormalizeEntityRootName('Category');
		$sEntityTarget=$this->_NormalizeEntityRootName('Target');
		$aCategories=$this->GetCategoryItemsByFilter(array(
														 '#join'=>array(
															 "JOIN ".Config::Get('db.table.category_target')." category_target ON
																	t.id = category_target.category_id and
																	category_target.target_type = '{$sTargetType}' and
																	category_target.target_id IN ( ?a )
																	"=>array($aEntitiesId)
														 ),
														 '#select'=>array(
															 't.*',
															 'category_target.target_id'
														 ),
														 '#index-group'=>'target_id',
														 '#cache'=>array(
															 null,
															 array($sEntityCategory.'_save',$sEntityCategory.'_delete',$sEntityTarget.'_save',$sEntityTarget.'_delete')
														 )
													 ));
		/**
		 * Собираем данные
		 */
		foreach($aEntityItems as $oEntity) {
			if (isset($aCategories[$oEntity->_getPrimaryKeyValue()])) {
				$oEntity->_setData(array('_categories'=>$aCategories[$oEntity->_getPrimaryKeyValue()]));
			} else {
				$oEntity->_setData(array('_categories'=>array()));
			}
		}
	}
	/**
	 * Возвращает дерево категорий
	 *
	 * @param int $sId Type ID
	 *
	 * @return array
	 */
	public function GetCategoriesTreeByType($sId) {
		$aRow=$this->oMapper->GetCategoriesByType($sId);
		if (count($aRow)) {
			$aRec=$this->Tools_BuildEntityRecursive($aRow);
		}
		if (!isset($aRec['collection'])) {
			return array();
		}
		$aResult=$this->Category_GetCategoryItemsByFilter(array('id in'=>array_keys($aRec['collection']),'#index-from-primary','#order'=>array('FIELD:id'=>array_keys($aRec['collection']))));
		foreach ($aResult as $oCategory) {
			$oCategory->setLevel($aRec['collection'][$oCategory->getId()]);
		}
		return $aResult;
	}
	/**
	 * Возвращает дерево категорий
	 *
	 * @param string $sCode Type code
	 *
	 * @return array
	 */
	public function GetCategoriesTreeByTargetType($sCode) {
		if ($oType=$this->Category_GetTypeByTargetType($sCode)) {
			return $this->GetCategoriesTreeByType($oType->getId());
		}
		return array();
	}
	/**
	 * Валидирует список категория
	 *
	 * @param array $aCategoryId
	 * @param int $iType
	 *
	 * @return array|bool
	 */
	public function ValidateCategoryArray($aCategoryId,$iType) {
		if (!is_array($aCategoryId)) {
			return false;
		}
		$aIds=array();
		foreach($aCategoryId as $iId) {
			$aIds[]=(int)$iId;
		}
		if ($aIds and $aCategories=$this->GetCategoryItemsByFilter(array('id in'=>$aIds,'type_id'=>$iType))) {
			$aResultId=array();
			foreach($aCategories as $oCategory) {
				$aResultId[]=$oCategory->getId();
			}
			return $aResultId;
		}
		return false;
	}
	/**
	 * Сохраняет категории для объекта
	 *
	 * @param $oTarget
	 * @param $sTargetType
	 */
	public function SaveCategories($oTarget,$sTargetType) {
		$aCategoriesId=$oTarget->_getDataOne('_categories_for_save');
		if (!is_array($aCategoriesId)) {
			return;
		}
		/**
		 * Удаляем текущие связи
		 */
		$this->RemoveRelation($oTarget->_getPrimaryKeyValue(),$sTargetType);
		/**
		 * Создаем
		 */
		$this->CreateRelation($aCategoriesId,$oTarget->_getPrimaryKeyValue(),$sTargetType);

		$oTarget->_setData(array('_categories_for_save'=>null));
	}
	/**
	 * Удаляет категории у объекта
	 *
	 * @param $oTarget
	 * @param $sTargetType
	 */
	public function RemoveCategories($oTarget,$sTargetType) {
		$this->RemoveRelation($oTarget->_getPrimaryKeyValue(),$sTargetType);
	}
	/**
	 * Создает новую связь конкретного объекта с категориями
	 *
	 * @param array $aCategoryId
	 * @param int $iTargetId
	 * @param int|string $iType type_id или target_type
	 *
	 * @return bool
	 */
	public function CreateRelation($aCategoryId,$iTargetId,$iType) {
		if (!$aCategoryId or (is_array($aCategoryId) and !count($aCategoryId))) {
			return false;
		}
		if (!is_array($aCategoryId)) {
			$aCategoryId=array($aCategoryId);
		}
		if (is_numeric($iType)) {
			$oType=$this->GetTypeById($iType);
		} else {
			$oType=$this->GetTypeByTargetType($iType);
		}
		if (!$oType) {
			return false;
		}
		foreach($aCategoryId as $iCategoryId){
			if (!$this->GetTargetByCategoryIdAndTargetIdAndTypeId($iCategoryId,$iTargetId,$oType->getId())) {
				$oTarget=Engine::GetEntity('ModuleCategory_EntityTarget');
				$oTarget->setCategoryId($iCategoryId);
				$oTarget->setTargetId($iTargetId);
				$oTarget->setTargetType($oType->getTargetType());
				$oTarget->setTypeId($oType->getId());
				$oTarget->Add();
			}
		}
		return true;
	}
	/**
	 * Удаляет связь конкретного объекта с категориями
	 *
	 * @param int $iTargetId
	 * @param int|string $iType type_id или target_type
	 *
	 * @return bool
	 */
	public function RemoveRelation($iTargetId,$iType) {
		if (!is_numeric($iType)) {
			if ($oType=$this->GetTypeByTargetType($iType)) {
				$iType=$oType->getId();
			} else {
				return false;
			}
		}
		$aTargets=$this->GetTargetItemsByTargetIdAndTypeId($iTargetId,$iType);
		foreach($aTargets as $oTarget) {
			$oTarget->Delete();
		}
		return true;
	}
	/**
	 * Возвращает список категорий по категории
	 *
	 * @param      $oCategory
	 * @param bool $bIncludeChild	Возвращать все дочернии категории
	 *
	 * @return array|null
	 */
	public function GetCategoriesIdByCategory($oCategory,$bIncludeChild=false) {
		if (is_object($oCategory)) {
			$iCategoryId=$oCategory->getId();
		} else {
			$iCategoryId=$oCategory;
		}
		if ($bIncludeChild) {
			/**
			 * Сначала получаем полный список категорий текущего типа
			 */
			if (!is_object($oCategory)) {
				$oCategory=$this->GetCategoryById($iCategoryId);
			}
			if ($oCategory) {
				$aCategories=$this->oMapper->GetCategoriesByType($oCategory->getTypeId());
				$aCategoriesChild=$this->GetChildItemsFromCategories($aCategories,$iCategoryId);
				$aCategoryId=array_merge(array((int)$iCategoryId),array_keys($aCategoriesChild));
			} else {
				return null;
			}
		} else {
			$aCategoryId=array($iCategoryId);
		}
		return $aCategoryId;
	}
	/**
	 * Обрабатывает дочерние категории
	 *
	 * @param      $aCategories
	 * @param null $iCategoryId
	 *
	 * @return array
	 */
	protected function GetChildItemsFromCategories($aCategories,$iCategoryId=null) {
		static $aResult;
		static $bIsChild;

		foreach($aCategories as $aCategory) {
			if ($aCategory['id']==$iCategoryId) {
				$bIsChild=true;
				$this->GetChildItemsFromCategories($aCategory['childNodes'],$iCategoryId);
				return $aResult ? $aResult : array();
			}
			if ($bIsChild) {
				$aCat=$aCategory;
				unset($aCat['childNodes']);
				$aResult[$aCat['id']]=$aCat;
			}
			if ($aCategory['childNodes']) {
				$this->GetChildItemsFromCategories($aCategory['childNodes'],$iCategoryId);
			}
		}
		return $aResult ? $aResult : array();
	}

}
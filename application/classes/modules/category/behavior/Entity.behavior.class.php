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
 * Поведение, которое необходимо добавлять к сущности (entity) у которой добавляются категории
 */
class ModuleCategory_BehaviorEntity extends Behavior {
	/**
	 * Дефолтные параметры
	 *
	 * @var array
	 */
	protected $aParams=array(
		'target_type'=>'',
		'form_field'=>'categories',
		'multiple'=>false,

		'validate_enable'=>true,
		'validate_field'=>null,
		'validate_require'=>false,
		'validate_from_request'=>true,
		'validate_min'=>1,
		'validate_max'=>5,
	);
	/**
	 * Список хуков
	 *
	 * @var array
	 */
	protected $aHooks=array(
		'validate_after'=>'CallbackValidateAfter',
		'after_save'=>'CallbackAfterSave',
		'after_delete'=>'CallbackAfterDelete',
	);
	/**
	 * Инициализация
	 */
	protected function Init() {
		parent::Init();
		if (!$this->getParam('validate_field')) {
			$this->aParams['validate_field']=$this->getParam('form_field');
		}
	}
	/**
	 * Коллбэк
	 * Выполняется при инициализации сущности
	 *
	 * @param $aParams
	 */
	public function CallbackValidateAfter($aParams) {
		if ($aParams['bResult'] and $this->getParam('validate_enable')) {
			$aFields=$aParams['aFields'];
			if (is_null($aFields) or in_array($this->getParam('validate_field'),$aFields)) {
				$oValidator=$this->Validate_CreateValidator('categories_check',$this,$this->getParam('validate_field'));
				$oValidator->validateEntity($this->oObject,$aFields);
				$aParams['bResult']=!$this->oObject->_hasValidateErrors();
			}
		}
	}
	/**
	 * Коллбэк
	 * Выполняется после сохранения сущности
	 */
	public function CallbackAfterSave() {
		$this->Category_SaveCategories($this->oObject,$this->getParam('target_type'));
	}
	/**
	 * Коллбэк
	 * Выполняется после удаления сущности
	 */
	public function CallbackAfterDelete() {
		$this->Category_RemoveCategories($this->oObject,$this->getParam('target_type'));
	}
	/**
	 * Дополнительный метод для сущности
	 * Запускает валидацию дополнительных полей
	 *
	 * @param $mValue
	 *
	 * @return bool|string
	 */
	public function ValidateCategoriesCheck($mValue) {
		/**
		 * Проверяем тип категрий
		 */
		if (!$oTypeCategory=$this->Category_GetTypeByTargetType($this->getParam('target_type'))) {
			return 'Неверный тип категорий';
		}

		if ($this->getParam('validate_from_request')) {
			$mValue=getRequest($this->getParam('form_field'));
		}
		/**
		 * Значение может быть числом, массивом, строкой с разделением через запятую
		 */
		if (!is_array($mValue)) {
			if ($this->getParam('multiple')) {
				$mValue=explode(',',$mValue);
			} else {
				$mValue=array($mValue);
			}
		}
		/**
		 * Проверяем наличие категорий в БД
		 */
		$aCategoriesId=$this->Category_ValidateCategoryArray($mValue,$oTypeCategory->getId());
		if (!$aCategoriesId) {
			$aCategoriesId=array();
		}

		if ($this->getParam('validate_require') and !$aCategoriesId) {
			return 'Необходимо выбрать категорию';
		}
		if (!$this->getParam('multiple') and count($aCategoriesId)>1) {
			$aCategoriesId=array_slice($aCategoriesId,0,1);
		}
		if ($this->getParam('multiple') and $aCategoriesId and ( count($aCategoriesId)<$this->getParam('validate_min') or count($aCategoriesId)>$this->getParam('validate_max'))) {
			return 'Количество категорий должно быть от '.$this->getParam('validate_min').' до '.$this->getParam('validate_max');
		}
		/**
		 * Сохраняем необходимый список категорий для последующего сохранения в БД
		 */
		$this->oObject->_setData(array('_categories_for_save'=>$aCategoriesId));
		return true;
	}
	/**
	 * Возвращает список категорий сущности
	 *
	 * @return array
	 */
	public function getCategories() {
		return $this->Category_GetEntityCategories($this->oObject,$this->getCategoryTargetType());
	}
	/**
	 * Возвращает количество категорий
	 *
	 * @return array
	 */
	public function getCountCategories() {
		return count($this->getCategories());
	}
	/**
	 * Возвращает одну категорию сущности
	 * Если объект может иметь несколько категорий, то вернется первая
	 *
	 * @return ModuleCategory_EntityCategory|null
	 */
	public function getCategory() {
		$aCategories=$this->getCategories();
		$oCategory=reset($aCategories);
		return $oCategory ? $oCategory : null;
	}
	/**
	 * Возвращает тип объекта для категорий
	 *
	 * @return string
	 */
	public function getCategoryTargetType() {
		if ($sType=$this->getParam('target_type')) {
			return $sType;
		}
		/**
		 * Иначе дополнительно смотрим на наличие данного метода у сущности
		 * Это необходимо, если тип вычисляется динамически по какой-то своей логике
		 */
		if (func_method_exists($this->oObject,'getCategoryTargetType','public')) {
			return call_user_func(array($this->oObject,'getCategoryTargetType'));
		}
	}
}
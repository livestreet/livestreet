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
 * Хук для работы свойств(дополнительных полей сущности)
 */
class HookProperty extends Hook {

	public function RegisterHook() {
		$this->AddHook('lang_init_start','InitStart',null,-10000);
		$this->AddHook('module_orm_GetItemsByFilter_after','GetItemsByFilterAfter',null,10000);
		$this->AddHook('module_orm_GetItemsByFilter_before','GetItemsByFilterBefore',null,10000);
		$this->AddHook('module_orm_GetByFilter_before','GetItemsByFilterBefore',null,10000);
	}

	public function InitStart() {
		/**
		 * Регистрируем кастомный загрузчик классов
		 */
		spl_autoload_register(array($this,'autoload'),true,true);
		/**
		 * Добавляем через наследование в объекты необходимый функционал по работе со свойствами EAV
		 */
		$aTargetTypes=$this->Property_GetTargetTypes();
		foreach($aTargetTypes as $sType=>$aParams) {
			$this->Plugin_Inherit($aParams['entity'],'ModuleProperty_Target_'.$aParams['entity'],'ModuleProperty');
		}
	}
	/**
	 * Дополнительная пост-обработка результатов запроса ORM
	 *
	 * @param $aParams
	 */
	public function GetItemsByFilterAfter($aParams) {
		$aEntities=$aParams['aEntities'];
		$aFilter=$aParams['aFilter'];
		$this->Property_RewriteGetItemsByFilter($aEntities,$aFilter);
	}
	/**
	 * Обработка фильтра для запросов к ORM
	 *
	 * @param $aParams
	 */
	public function GetItemsByFilterBefore($aParams) {
		$aFilter=$this->Property_RewriteFilter($aParams['aFilter'],$aParams['sEntityFull']);
		$aParams['aFilter']=$aFilter;
	}
	/**
	 * Автозагрузчик классов
	 * Создает новый фейковый класс для создания цепочки наследования
	 *
	 * @param string $sClassName
	 */
	public function autoload($sClassName) {
		if (preg_match("#^ModuleProperty_Target_(.+)$#i",$sClassName,$aMatch)) {
			$sClass="
			class {$sClassName} extends ModuleProperty_Inherit_{$aMatch[1]} {
				public function Init() {
					parent::Init();
					\$this->aValidateRules[]=array('properties','properties_check');
				}

				public function ValidatePropertiesCheck() {
					return \$this->Property_ValidateEntityPropertiesCheck(\$this);
				}

				protected function afterSave() {
					parent::afterSave();
					\$this->Property_UpdatePropertiesValue(\$this->getPropertiesObject(),\$this);
				}

				protected function afterDelete() {
					parent::afterDelete();
					\$this->Property_RemovePropertiesValue(\$this);
				}

				public function getPropertyValue(\$sPropertyId) {
					return \$this->Property_GetEntityPropertyValue(\$this,\$sPropertyId);
				}

				public function getProperty(\$sPropertyId) {
					return \$this->Property_GetEntityProperty(\$this,\$sPropertyId);
				}

				public function getPropertyList() {
					return \$this->Property_GetEntityPropertyList(\$this);
				}
			}";
			eval($sClass);
		}
	}
}
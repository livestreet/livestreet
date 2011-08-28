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
 * Абстрактный класс сущности ORM - аналог active record
 *
 */
abstract class EntityORM extends Entity {

	/**
	 * Типы связей сущностей
	 *
	 */
	const RELATION_TYPE_BELONGS_TO='belongs_to';
	const RELATION_TYPE_HAS_MANY='has_many';
	const RELATION_TYPE_HAS_ONE='has_one';
	const RELATION_TYPE_MANY_TO_MANY='many_to_many';
	const RELATION_TYPE_TREE='tree';

	protected $_aOriginalData=array();

	/**
	 * Список полей таблицы сущности
	 *
	 * @var unknown_type
	 */
	protected $aFields=array();

	/**
	 * Список связей
	 *
	 * @var unknown_type
	 */
	protected $aRelations=array();
	/**
	 * Список данных связей
	 *
	 * @var unknown_type
	 */
	protected $aRelationsData=array();

    // Объекты связей many_to_many
    protected $_aManyToManyRelations = array();
	/**
	 * Primary key таблицы сущности
	 *
	 * @var unknown_type
	 */
	protected $sPrimaryKey='id';
	/**
	 * Флаг новая или нет сущность
	 *
	 * @var unknown_type
	 */
	protected $bIsNew=true;


	public function __construct($aParam=false) {
		parent::__construct($aParam);
		$this->aRelations=$this->_getRelations();
	}

	/**
	 * Получение primary key из схемы таблицы
	 *
	 * @return unknown
	 */
	public function _getPrimaryKey() {
		if(!$this->_getDataOne($this->sPrimaryKey)) {
			if($this->_getFields()) {
				if(array_key_exists('#primary_key',$this->aFields)) {
					$this->sPrimaryKey = $this->aFields['#primary_key'];
				} else {
					$this->sPrimaryKey = $this->_getField($this->sPrimaryKey,2);
				}
			}
		}
		return $this->sPrimaryKey;
	}

	/**
	 * Получение значения primary key
	 *
	 * @return unknown
	 */
    public function _getPrimaryKeyValue() {
        return $this->_getDataOne($this->_getPrimaryKey());
    }

    /**
     * Новая или нет сущность
     *
     * @return unknown
     */
	public function _isNew() {
		return $this->bIsNew;
	}

	/**
	 * Установка флага "новая"
	 *
	 * @param unknown_type $bIsNew
	 */
	public function _SetIsNew($bIsNew) {
		$this->bIsNew=$bIsNew;
	}

	/**
	 * Добавление сущности в БД
	 *
	 * @return unknown
	 */
	public function Add() {
		if ($this->beforeSave())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterSave();
				return $res;
			}
		return false;
	}

	/**
	 * Обновление сущности в БД
	 *
	 * @return unknown
	 */
	public function Update() {
		if ($this->beforeSave())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterSave();
				return $res;
			}
		return false;
	}

	/**
	 * Сохранение сощности в БД (если новая то создается)
	 *
	 * @return unknown
	 */
	public function Save() {
		if ($this->beforeSave())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterSave();
				return $res;
			}
		return false;
	}

	/**
	 * Удаление сущности из БД
	 *
	 * @return unknown
	 */
	public function Delete() {
		if ($this->beforeDelete())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterDelete();
				return $res;
			}
		return false;
	}

	/**
	 * Обновляет данные сущности из БД
	 *
	 * @return unknown
	 */
	public function Reload() {
		return $this->_Method(__FUNCTION__);
	}

	/**
	 * Список полей сущности
	 *
	 * @return unknown
	 */
	public function ShowColumns() {
		return $this->_Method(__FUNCTION__ .'From');
	}

	/**
	 * Хук, срабатывает перед сохранением сущности
	 *
	 * @return unknown
	 */
	protected function beforeSave() {
		return true;
	}

	/**
	 * Хук, срабатывает после сохранением сущности
	 *
	 */
	protected function afterSave() {

	}

	/**
	 * Хук, срабатывает перед удалением сущности
	 *
	 * @return unknown
	 */
	protected function beforeDelete() {
		return true;
	}

	/**
	 * Хук, срабатывает после удаления сущности
	 *
	 */
	protected function afterDelete() {

	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE возвращает список прямых потомков
	 *
	 * @return unknown
	 */
	public function getChildren() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE возвращает список всех потомков
	 *
	 * @return unknown
	 */
	public function getDescendants() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE возвращает предка
	 *
	 * @return unknown
	 */
	public function getParent() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE возвращает список всех предков
	 *
	 * @return unknown
	 */
	public function getAncestors() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE устанавливает потомков
	 *
	 * @param unknown_type $aChildren
	 * @return unknown
	 */
	public function setChildren($aChildren=array()) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['children'] = $aChildren;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE устанавливает потомков
	 *
	 * @param unknown_type $aDescendants
	 * @return unknown
	 */
	public function setDescendants($aDescendants=array()) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['descendants'] = $aDescendants;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE устанавливает предка
	 *
	 * @param unknown_type $oParent
	 * @return unknown
	 */
	public function setParent($oParent=null) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['parent'] = $oParent;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}

	/**
	 * Для сущности со связью RELATION_TYPE_TREE устанавливает предков
	 *
	 * @param unknown_type $oParent
	 * @return unknown
	 */
	public function setAncestors($oParent=null) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['ancestors'] = $oParent;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}

	/**
	 * Проксирует вызов методов в модуль сущности
	 *
	 * @param unknown_type $sName
	 * @return unknown
	 */
	protected function _Method($sName) {
		$sModuleName=Engine::GetModuleName($this);
		$sEntityName=Engine::GetEntityName($this);
		$sPluginPrefix=Engine::GetPluginPrefix($this);
		/**
		 * If Module not exists, try to find its root Delegater
		 */
		$aClassInfo = Engine::GetClassInfo($sPluginPrefix.'Module_'.$sModuleName,Engine::CI_MODULE);
		if(empty($aClassInfo[Engine::CI_MODULE]) && $sRootDelegater=$this->Plugin_GetRootDelegater('entity',get_class($this))) {
			$sModuleName=Engine::GetModuleName($sRootDelegater);
			$sPluginPrefix=Engine::GetPluginPrefix($sRootDelegater);
		}
		return Engine::GetInstance()->_CallModule("{$sPluginPrefix}{$sModuleName}_{$sName}{$sEntityName}",array($this));
	}

	/**
	 * Устанавливает данные сущности
	 *
	 * @param unknown_type $aData
	 */
	public function _setData($aData) {
		if(is_array($aData)) {
			foreach ($aData as $sKey => $val) {
				if (array_key_exists($sKey,$this->aRelations)) {
					$this->aRelationsData[$sKey]=$val;
				} else {
					$this->_aData[$sKey] = $val;
				}
			}
			$this->_aOriginalData = $this->_aData;
		}
	}

	/**
	 * Возвращает все данные сущности
	 *
	 * @return unknown
	 */
	public function _getOriginalData() {
		return $this->_aOriginalData;
	}

	/**
	 * Возвращает список полей сущности
	 *
	 * @return unknown
	 */
	public function _getFields() {
		if(empty($this->aFields)) {
			$this->aFields=$this->ShowColumns();
		}
		return $this->aFields;
	}

	/**
	 * Возвращает поле в нужном формате
	 *
	 * @param unknown_type $sField
	 * @param unknown_type $iPersistence
	 * @return unknown
	 */
	public function _getField($sField,$iPersistence=3) {
		if($aFields=$this->_getFields()) {
			if(in_array($sField,$aFields)) {
				return $sField;
			}
			if($iPersistence==0) {
				return null;
			}
			$sFieldU = func_camelize($sField);
			$sEntityField = func_underscore(Engine::GetEntityName($this).$sFieldU);
			if(in_array($sEntityField,$aFields)) {
				return $sEntityField;
			}
			if($iPersistence==1) {
				return null;
			}
			$sModuleEntityField = func_underscore(Engine::GetModuleName($this).Engine::GetEntityName($this).$sFieldU);
			if(in_array($sModuleEntityField,$aFields)) {
				return $sModuleEntityField;
			}
			if($iPersistence==2) {
				return null;
			}
			$sModuleField = func_underscore(Engine::GetModuleName($this).$sFieldU);
			if(in_array($sModuleField,$aFields)) {
				return $sModuleField;
			}
		}
		return $sField;
	}

	/**
	 * Возвращает список связей
	 *
	 * @return unknown
	 */
	public function _getRelations() {
		$sParent=get_parent_class($this);
		if(substr_count($sParent,'_Inherits_') || substr_count($sParent,'_Inherit_')) {
			$sParent = get_parent_class($sParent);
		}
		$aParentRelations=array();
		if(!in_array($sParent,array('Entity','EntityORM'))) {
			$oEntityParent=new $sParent();
			$aParentRelations=$oEntityParent->_getRelations();
		}
		return array_merge($aParentRelations,$this->aRelations);
	}

	/**
	 * Возвращает список данный связей
	 *
	 * @return unknown
	 */
	public function _getRelationsData() {
		return $this->aRelationsData;
	}

	/**
	 * Устанавливает данные связей
	 *
	 * @param unknown_type $aData
	 */
	public function _setRelationsData($aData) {
		$this->aRelationsData=$aData;
	}

	/**
	 * Вызов методов сущности
	 *
	 * @param unknown_type $sName
	 * @param unknown_type $aArgs
	 * @return unknown
	 */
	public function __call($sName,$aArgs) {
        $sType=substr($sName,0,strpos(func_underscore($sName),'_'));
		if (!strpos($sName,'_') and in_array($sType,array('get','set','reload'))) {
			$sKey=func_underscore(preg_replace('/'.$sType.'/','',$sName, 1));
			if ($sType=='get') {
				if (isset($this->_aData[$sKey])) {
					return $this->_aData[$sKey];
				} else {
					$sField=$this->_getField($sKey);
					if($sField!=$sKey && isset($this->_aData[$sField])) {
						return $this->_aData[$sField];
					}
				}
				/**
				 * Проверяем на связи
				 */
				if (array_key_exists($sKey,$this->aRelations)) {
					$sEntityRel=$this->aRelations[$sKey][1];
					$sRelationType=$this->aRelations[$sKey][0];
					$sRelationKey=$this->aRelations[$sKey][2];
					$sRelationJoinTable=null;
					$sRelationJoinTableKey=0;	// foreign key в join-таблице для текущей сущности
					if($sRelationType == self::RELATION_TYPE_MANY_TO_MANY && array_key_exists(3, $this->aRelations[$sKey])) {
						$sRelationJoinTable=$this->aRelations[$sKey][3];
						$sRelationJoinTableKey=isset($this->aRelations[$sKey][4]) ? $this->aRelations[$sKey][4] : $this->_getPrimaryKey();
					}

					/**
					 * Если связь уже загруженна, то возвращаем сразу результат
					 */
					if (array_key_exists($sKey,$this->aRelationsData)) {
						return $this->aRelationsData[$sKey];
					}


					$sRelModuleName=Engine::GetModuleName($sEntityRel);
					$sRelEntityName=Engine::GetEntityName($sEntityRel);
					$sRelPluginPrefix=Engine::GetPluginPrefix($sEntityRel);
					$sRelPrimaryKey='id';
					if($oRelEntity=Engine::GetEntity($sEntityRel) and method_exists($oRelEntity,'_getPrimaryKey')) { // для совместимости с сущностями Entity
						$sRelPrimaryKey=$oRelEntity->_getPrimaryKey();
					}

					$iPrimaryKeyValue=$this->_getDataOne($this->_getPrimaryKey());
					$sCmd='';
					$mCmdArgs=array();
					switch ($sRelationType) {
						case self::RELATION_TYPE_BELONGS_TO :
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}By".func_camelize($sRelPrimaryKey);
							$mCmdArgs=$this->_getDataOne($sRelationKey);
							break;
						case self::RELATION_TYPE_HAS_ONE :
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}By".func_camelize($sRelationKey);
							$mCmdArgs=$iPrimaryKeyValue;
							break;
						case self::RELATION_TYPE_HAS_MANY :
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}ItemsByFilter";
							$mCmdArgs=array($sRelationKey => $iPrimaryKeyValue);
							break;
						case self::RELATION_TYPE_MANY_TO_MANY :
						  $sCmd="{$sRelPluginPrefix}Module{$sRelModuleName}_get{$sRelEntityName}ItemsByJoinTable";
							$mCmdArgs=array(
								'#join_table'		=> Config::Get($sRelationJoinTable),
								'#relation_key'		=> $sRelationKey,
								'#by_key'			=> $sRelationJoinTableKey,
								'#by_value'			=> $iPrimaryKeyValue,
                                '#index-from-primary' => true // Для MANY_TO_MANY необходимо индексами в $aRelationsData иметь первичные ключи сущностей
							);
							break;
						default:
							break;
					}
					// Нужно ли учитывать дополнительный фильтр
					$bUseFilter = is_array($mCmdArgs) && array_key_exists(0,$aArgs) && is_array($aArgs[0]);
					if($bUseFilter) {
						$mCmdArgs = array_merge($mCmdArgs, $aArgs[0]);
					}
					$res=Engine::GetInstance()->_CallModule($sCmd, array($mCmdArgs));

					// Сохраняем данные только в случае "чистой" выборки
					if(!$bUseFilter) {
						$this->aRelationsData[$sKey]=$res;
						// Создаём объекты-обёртки для связей MANY_TO_MANY
						if ($sRelationType == self::RELATION_TYPE_MANY_TO_MANY) {
							$this->_aManyToManyRelations[$sKey] = new LS_ManyToManyRelation($this->aRelationsData[$sKey]);
						}
					}
					return $res;
				}

				return null;
			} elseif ($sType=='set' and array_key_exists(0,$aArgs)) {
				if (array_key_exists($sKey,$this->aRelations)) {
					$this->aRelationsData[$sKey]=$aArgs[0];
				} else {
					$this->_aData[$this->_getField($sKey)]=$aArgs[0];
				}
			} elseif ($sType=='reload') {
				if (array_key_exists($sKey,$this->aRelationsData)) {
					unset($this->aRelationsData[$sKey]);
					return $this->__call('get'.func_camelize($sKey),$aArgs);
				}
			}
		} else {
			return Engine::getInstance()->_CallModule($sName,$aArgs);
		}
	}

    public function __get($sName) {
        // Обработка обращений к обёрткам связей MANY_TO_MANY
        // Если связь загружена, возвращаем объект связи
        if (isset($this->_aManyToManyRelations[func_underscore($sName)])) {
            return $this->_aManyToManyRelations[func_underscore($sName)];
        // Есл не загружена, но связь с таким именем существет, пробуем загрузить и вернуть объект связи
        } elseif (isset($this->aRelations[func_underscore($sName)]) && $this->aRelations[func_underscore($sName)][0] == self::RELATION_TYPE_MANY_TO_MANY) {
            $sMethod = 'get' . func_camelize($sName);
            $this->__call($sMethod, array());
            if (isset($this->_aManyToManyRelations[func_underscore($sName)])) {
                 return $this->_aManyToManyRelations[func_underscore($sName)];
            }
        // В противном случае возвращаем то, что просили у объекта
        } else {
            return $this->$sName;
        }
    }

    /**
     * Сбрасывает данные необходимой связи
     *
     * @param unknown_type $sKey
     */
    public function resetRelationsData($sKey) {
        if (isset($this->aRelationsData[$sKey])) {
            unset($this->aRelationsData[$sKey]);
        }
    }
}
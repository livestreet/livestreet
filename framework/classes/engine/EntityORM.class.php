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
 * Позволяет без написания SQL запросов работать с базой данных.
 * <pre>
 * $oUser=$this->User_GetUserById(1);
 * $oUser->setName('Claus');
 * $oUser->Update();
 * </pre>
 * Возможно получать списки объектов по фильтру:
 * <pre>
 * $aUsers=$this->User_GetUserItemsByAgeAndSex(18,'male');
 * // эквивалентно
 * $aUsers=$this->User_GetUserItemsByFilter(array('age'=>18,'sex'=>'male'));
 * // эквивалентно
 * $aUsers=$this->User_GetUserItemsByFilter(array('#where'=>array('age = ?d and sex = ?' => array(18,'male'))));
 * </pre>
 *
 * @package engine.orm
 * @since 1.0
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

	/**
	 * Массив исходных данных сущности
	 *
	 * @var array
	 */
	protected $_aOriginalData=array();
	/**
	 * Список полей таблицы сущности
	 *
	 * @var array
	 */
	protected $aFields=array();
	/**
	 * Список связей
	 *
	 * @var array
	 */
	protected $aRelations=array();
	/**
	 * Список данных связей
	 *
	 * @var array
	 */
	protected $aRelationsData=array();
	/**
	 * Объекты связей many_to_many
	 *
	 * @var array
	 */
	protected $_aManyToManyRelations = array();
	/**
	 * Флаг новая или нет сущность
	 *
	 * @var bool
	 */
	protected $bIsNew=true;

	/**
	 * Установка связей
	 * @see Entity::__construct
	 *
	 * @param bool $aParam Ассоциативный массив данных сущности
	 */
	public function __construct($aParam=false) {
		parent::__construct($aParam);
		$this->aRelations=$this->_getRelations();
	}
	/**
	 * Получение primary key из схемы таблицы
	 *
	 * @return string|array	Если индекс составной, то возвращает массив полей
	 */
	public function _getPrimaryKey() {
		if(!$this->sPrimaryKey) {
			if ($aIndex=$this->ShowPrimaryIndex()) {
				if (count($aIndex)>1) {
					// Составной индекс
					$this->sPrimaryKey=$aIndex;
				} else {
					$this->sPrimaryKey=$aIndex[1];
				}
			}
		}
		return $this->sPrimaryKey;
	}
	/**
	 * Получение значения primary key
	 *
	 * @return string
	 */
	public function _getPrimaryKeyValue() {
		return $this->_getDataOne($this->_getPrimaryKey());
	}
	/**
	 * Новая или нет сущность
	 * Новая - еще не сохранялась в БД
	 *
	 * @return bool
	 */
	public function _isNew() {
		return $this->bIsNew;
	}
	/**
	 * Установка флага "новая"
	 *
	 * @param bool $bIsNew	Флаг - новая сущность или нет
	 */
	public function _SetIsNew($bIsNew) {
		$this->bIsNew=$bIsNew;
	}
	/**
	 * Добавление сущности в БД
	 *
	 * @return Entity|false
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
	 * @return Entity|false
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
	 * Сохранение сущности в БД (если новая то создается)
	 *
	 * @return Entity|false
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
	 * @return Entity|false
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
	 * @return Entity|false
	 */
	public function Reload() {
		return $this->_Method(__FUNCTION__);
	}
	/**
	 * Возвращает список полей сущности
	 *
	 * @return array
	 */
	public function ShowColumns() {
		return $this->_Method(__FUNCTION__ .'From');
	}
	/**
	 * Возвращает primary индекс сущности
	 *
	 * @return array
	 */
	public function ShowPrimaryIndex() {
		return $this->_Method(__FUNCTION__ .'From');
	}
	/**
	 * Хук, срабатывает перед сохранением сущности
	 *
	 * @return bool
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
	 * @return bool
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
	 * @return array
	 */
	public function getChildren() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__,array());
	}
	/**
	 * Для сущности со связью RELATION_TYPE_TREE возвращает список всех потомков
	 *
	 * @return array
	 */
	public function getDescendants() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__,array());
	}
	/**
	 * Для сущности со связью RELATION_TYPE_TREE возвращает предка
	 *
	 * @return Entity
	 */
	public function getParent() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__,array());
	}
	/**
	 * Для сущности со связью RELATION_TYPE_TREE возвращает список всех предков
	 *
	 * @return array
	 */
	public function getAncestors() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__,array());
	}
	/**
	 * Для сущности со связью RELATION_TYPE_TREE устанавливает потомков
	 *
	 * @param array $aChildren	Список потомков
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
	 * @param array $aDescendants	Список потомков
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
	 * @param Entity $oParent	Родитель
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
	 * @param array $oParent	Родитель
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
	 * @param string $sName	Название метода
	 * @return mixed
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
	 * @param array $aData	Ассоциативный массив данных сущности
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
	 * @return array
	 */
	public function _getOriginalData() {
		return $this->_aOriginalData;
	}
	/**
	 * Возвращает данные для списка полей сущности
	 *
	 * @return array
	 */
	public function _getDataFields() {
		return $this->_getData($this->_getFields());
	}
	/**
	 * Возвращает список полей сущности
	 *
	 * @return array
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
	 * @param string $sField	Название поля
	 * @param int $iPersistence	Тип "глубины" определения поля
	 * @return null|string
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
	 * @return array
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
	 * @return array
	 */
	public function _getRelationsData() {
		return $this->aRelationsData;
	}
	/**
	 * Устанавливает данные связей
	 *
	 * @param array $aData	Список связанных данных
	 */
	public function _setRelationsData($aData) {
		$this->aRelationsData=$aData;
	}
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 * Также производит обработку методов set* и get*
	 * Учитывает связи и может возвращать связанные данные
	 * @see Engine::_CallModule
	 *
	 * @param string $sName Имя метода
	 * @param array $aArgs Аргументы
	 * @return mixed
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
					if($oRelEntity=Engine::GetEntity($sEntityRel)) {
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
							if (isset($this->aRelations[$sKey][3])) {
								$aFilterAdd=$this->aRelations[$sKey][3];
							} else {
								$aFilterAdd=array();
							}
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}ItemsByFilter";
							$mCmdArgs=array_merge(array($sRelationKey => $iPrimaryKeyValue),$aFilterAdd);
							break;
						case self::RELATION_TYPE_MANY_TO_MANY :
							if (isset($this->aRelations[$sKey][5])) {
								$aFilterAdd=$this->aRelations[$sKey][5];
							} else {
								$aFilterAdd=array();
							}
							$sCmd="{$sRelPluginPrefix}Module{$sRelModuleName}_get{$sRelEntityName}ItemsByJoinTable";
							$mCmdArgs=array(
								'#join_table'		=> Config::Get($sRelationJoinTable),
								'#relation_key'		=> $sRelationKey,
								'#by_key'			=> $sRelationJoinTableKey,
								'#by_value'			=> $iPrimaryKeyValue,
								'#index-from-primary' => true // Для MANY_TO_MANY необходимо индексами в $aRelationsData иметь первичные ключи сущностей
							);
							$mCmdArgs=array_merge($mCmdArgs,$aFilterAdd);
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
					}
					// Создаём объекты-обёртки для связей MANY_TO_MANY
					if ($sRelationType == self::RELATION_TYPE_MANY_TO_MANY) {
						$this->_aManyToManyRelations[$sKey] = new LS_ManyToManyRelation($res);
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
	/**
	 * Используется для доступа к связанным данным типа MANY_TO_MANY
	 *
	 * @param string $sName	Название свойства к которому обращаемсяя
	 * @return mixed
	 */
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
	 * @param string $sKey	Ключ(поле) связи
	 */
	public function resetRelationsData($sKey) {
		if (isset($this->aRelationsData[$sKey])) {
			unset($this->aRelationsData[$sKey]);
		}
	}
}